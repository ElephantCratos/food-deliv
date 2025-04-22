<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Показать список пользователей (кроме себя).
     */
    public function showUsers()
    {
        $me = Auth::id();

        $users = User::where('id', '<>', $me)
            ->whereHas('roles', function ($query) {
                $query->whereIn('role_id', [2, 4]);
            })
            ->get();

        return view('custom_components.user-list', compact('users'));
    }

    /**
     * Открыть чат по user_id: найти или создать чат и редиректить на chat/{chat}.
     */
    public function openChat($userId)
    {
        $me = Auth::id();

        // Упорядочиваем ID, чтобы не было (2,5) и (5,2)
        $ids = [$me, $userId];
        sort($ids);
        [$u1, $u2] = $ids;

        // Находим или создаём чат
        $chat = Chat::firstOrCreate([
            'user_1_id' => $u1,
            'user_2_id' => $u2,
        ]);

        // Редирект на URL с chat_id
        return redirect()->route('chats.show', $chat->id);
    }

    /**
     * Показать страницу чата по chat_id.
     */
    public function showChat(Chat $chat)
    {
        $me = Auth::id();
    
        if (!in_array($me, [$chat->user_1_id, $chat->user_2_id])) {
            abort(403, 'Доступ запрещён');
        }
    
        $messages     = $chat->messages()->with('sender')->orderBy('created_at')->get();
        $interlocutor = $chat->getInterlocutor($me);
    
        // Передаём и $interlocutor, и дублируем его в $user:
        return view('custom_components.dialogue', [
            'chat'         => $chat,
            'messages'     => $messages,
            'interlocutor' => $interlocutor,
            'user'         => $interlocutor, // <-- вот это
        ]);
    }
    

    /**
     * Отправить сообщение в чат.
     */
    public function sendMessage(Request $request, $userId)
    {
        $chat = Chat::firstOrCreate([
            'user_1_id' => auth()->id(),
            'user_2_id' => $userId,
        ]);
    
        $message = new Message();
        $message->content = $request->content;
        $message->sender_id = auth()->id(); // Идентификатор отправителя
        $message->receiver_id = $userId; // Идентификатор получателя
        $message->chat_id = $chat->id; // Идентификатор чата
        $message->save();
    
        return response()->json([
            'content' => $message->content,
            'sender_id' => $message->sender_id,
            'receiver_id' => $message->receiver_id,
        ]);
    }
}
