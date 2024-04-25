<!DOCTYPE html>
<html>
<head>
    <title>Чат с {{ $user->name }}</title>
    <style>
        .message {
            margin-bottom: 10px;
        }
        .message .sender {
            font-weight: bold;
        }
        .message .content {
            margin-left: 10px;
        }
    </style>
</head>
<body>
<h1>Чат с {{ $user->name }}</h1>
<div>

    @forelse ($messages as $message)
        <div class="message">
            <span class="sender">{{ $message->sender_id === auth()->user()->id ? 'Вы' : $user->name }}</span>
            <span class="content">{{ $message->content }}</span>
        </div>
    @empty
        <p>Нет сообщений</p>
    @endforelse
</div>
<form method="post" action="/chat/{{ $user->id }}">
    @csrf
    <input type="hidden" name="receiver_id" value="{{ $user->id }}">
    <input type="text" name="content" placeholder="Введите сообщение">
    <button type="submit">Отправить</button>
</form>
</body>
</html>
