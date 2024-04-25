<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ChatController extends Controller
{
    public function showUsers()
    {
        $userId = Auth::id();

        $users = User::where('id', '<>', $userId)
            ->whereHas('roles', function ($query) {
                $query->whereIn('role_id', [2, 4]);
            })
            ->get();
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
