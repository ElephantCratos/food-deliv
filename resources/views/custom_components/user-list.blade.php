<!DOCTYPE html>
<html>
<head>
    <title>Список пользователей</title>
</head>
<body>
<h1>Список пользователей</h1>
<ul>
    @foreach ($users as $user)
        <li><a href="/chat/{{ $user->id }}">{{ $user->name }}</a></li>
    @endforeach
</ul>
</body>
</html>
