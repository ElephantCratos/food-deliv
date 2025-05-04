<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ChatController extends Controller
{
    // Единый пользователь-саппорт
    public const SUPPORT_USER_ID = 2;

    /** Список клиентов (не себя и не саппорт) */
    public function index()
    {
        $me = Auth::id();

        $users = User::whereHas('roles', fn($q) => $q->where('name', 'user'))
                     ->where('id', '<>', $me)
                     ->get();

        return view('custom_components.user-list', compact('users'));
    }

    /** Открыть или создать чат между мной и другим */
    public function openChat(int $otherUserId)
    {
        $me = Auth::user();
        $other = User::findOrFail($otherUserId);

        // Клиенты могут писать ТОЛЬКО саппорту
        if (! $me->hasRole('support') && ! $other->hasRole('support')) {
            abort(403, 'Клиенты могут писать только в поддержку');
        }

        // Определяем пару ID: всегда один из них — SUPPORT_USER_ID
        $clientId  = $me->hasRole('support') ? $other->id : $me->id;
        $supportId = self::SUPPORT_USER_ID;

        $ids = [$clientId, $supportId];
        sort($ids);
        [$u1, $u2] = $ids;

        $chat = Chat::firstOrCreate([
            'user_1_id' => $u1,
            'user_2_id' => $u2,
        ]);

        // Привязываем участников
        $chat->users()->syncWithoutDetaching([
            $me->id,
            $other->id,
            self::SUPPORT_USER_ID,
        ]);

        return redirect()->route('chats.show', $chat);
    }

    /** Показать диалог */
    public function show(Chat $chat)
    {
        $me = Auth::user();

        // Проверяем, что я привязан к этому чату
        $checkingUserId = $me->hasRole('support')
            ? self::SUPPORT_USER_ID
            : $me->id;

        if (! $chat->users->contains(fn($u) => $u->id === $checkingUserId)) {
            abort(403, 'Доступ запрещён');
        }

        // Помечаем как прочитанное
        $chat->users()->updateExistingPivot(
            $me->id,
            ['last_read_at' => Carbon::now()]
        );

        // Загружаем все сообщения
        $messages = $chat->messages()
                         ->with('sender.roles') // не нужен operator
                         ->orderBy('created_at')
                         ->get();

        // Определяем второго участника (клиента)
        $otherUserId = $chat->user_1_id === $checkingUserId
            ? $chat->user_2_id
            : $chat->user_1_id;

        $otherUser = User::findOrFail($otherUserId);

        return view('custom_components.dialogue', compact('chat', 'messages'))
            ->with('client', $otherUser);
    }

    public function send(Request $request, Chat $chat)
{
    $me = Auth::user();

    // Кто я в контексте чата?
    $isSupport = $me->hasRole('support');
    $senderId  = $isSupport ? self::SUPPORT_USER_ID : $me->id;
    $operatorId = $isSupport ? $me->id : null;

    // Проверка доступа
    $checkingUserId = $isSupport ? self::SUPPORT_USER_ID : $me->id;
    if (!$chat->users->contains(fn($u) => $u->id === $checkingUserId)) {
        abort(403, 'Доступ запрещён');
    }

    $request->validate(['content' => 'required|string|max:2000']);

    // Получатель — тот, кто не senderId (а не обязательно client!)
    $receiverId = ($chat->user_1_id === $senderId)
        ? $chat->user_2_id
        : $chat->user_1_id;

    $message = Message::create([
        'chat_id'     => $chat->id,
        'sender_id'   => $senderId,
        'receiver_id' => $receiverId,
        'operator_id' => $operatorId,
        'content'     => $request->content,
        'is_read'     => false,
    ]);

    return response()->json([
        'id'         => $message->id,
        'sender_id'  => $message->sender_id,
        'content'    => $message->content,
        'is_support' => $isSupport,
    ]);
}

}
