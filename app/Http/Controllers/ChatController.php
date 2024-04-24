<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;


class ChatController extends Controller
{
    public function showUsers()
    {
        $users = User::all();
        return view('custom_components.user-list', compact('users'));
    }

    public function showChat($userId)
    {
        $user = User::findOrFail($userId);
        $messages = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->orderBy('created_at')
            ->get();
        return view('custom_components.dialogue', compact('user', 'messages'));
    }

    public function sendMessage(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $message = new Message();
        $message->sender_id = auth()->user()->id;
        $message->receiver_id = $request->input('receiver_id');
        $message->content = $request->input('content');
        $message->save();
        return redirect('/chat/'.$userId);
    }
}
