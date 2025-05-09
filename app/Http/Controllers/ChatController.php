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

    public function index()
    {
        $supportId = self::SUPPORT_USER_ID;
    
        $users = User::whereHas('roles', fn($q) => $q->where('name', 'user'))
                     ->whereHas('chats', fn($q) =>
                         $q->where(function ($query) use ($supportId) {
                             $query->where('user_1_id', $supportId)
                                   ->orWhere('user_2_id', $supportId);
                         })
                     )
                     ->with(['chats' => function ($q) use ($supportId) {
                         $q->where(function ($query) use ($supportId) {
                             $query->where('user_1_id', $supportId)
                                   ->orWhere('user_2_id', $supportId);
                         })->with('users');
                     }, 'roles'])
                     ->get();
    
        foreach ($users as $user) {
            $chat = $user->chats->first(); 
    
            $pivot = $chat->users->firstWhere('id', $supportId)?->pivot;
            $lastRead = optional($pivot)->last_read_at ?? now()->subYears(10);
    
            $user->unread_count = $chat->messages()
                ->where('sender_id', $user->id) 
                ->where('created_at', '>', $lastRead)
                ->count();
        }
    
        return view('custom_components.user-list', compact('users'));
    }
    

    public function openChat(int $otherUserId)
    {
        $me = Auth::user();
        $other = User::findOrFail($otherUserId);

        if (! $me->hasRole('support') && ! $other->hasRole('support')) {
            abort(403, 'Клиенты могут писать только в поддержку');
        }

        $clientId  = $me->hasRole('support') ? $other->id : $me->id;
        $supportId = self::SUPPORT_USER_ID;

        $ids = [$clientId, $supportId];
        sort($ids);
        [$u1, $u2] = $ids;

        $chat = Chat::firstOrCreate([
            'user_1_id' => $u1,
            'user_2_id' => $u2,
        ]);

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
    
        $readerId = $me->hasRole('support') ? self::SUPPORT_USER_ID : $me->id;
    
        if (! $chat->users->contains(fn($u) => $u->id === $readerId)) {
            abort(403, 'Доступ запрещён');
        }
    
        $chat->users()->updateExistingPivot(
            $readerId,
            ['last_read_at' => Carbon::now()]
        );
    
        $messages = $chat->messages()
                         ->with('sender.roles')
                         ->orderBy('created_at')
                         ->get();
    
        $otherUserId = $chat->user_1_id === $readerId
            ? $chat->user_2_id
            : $chat->user_1_id;
    
        $otherUser = User::findOrFail($otherUserId);
    
        return view('custom_components.dialogue', compact('chat', 'messages'))
            ->with('client', $otherUser);
    }
    

    public function send(Request $request, Chat $chat)
{
    $me = Auth::user();
    $isSupport = $me->hasRole('support');
    $senderId  = $isSupport ? self::SUPPORT_USER_ID : $me->id;
    $operatorId = $isSupport ? $me->id : null;

    $checkingUserId = $isSupport ? self::SUPPORT_USER_ID : $me->id;
    if (!$chat->users->contains(fn($u) => $u->id === $checkingUserId)) {
        abort(403, 'Доступ запрещён');
    }

    $request->validate(['content' => 'required|string|max:2000']);

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
