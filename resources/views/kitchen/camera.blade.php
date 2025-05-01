@extends('layouts.app')

@section('title', 'Кухня LIVE')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Камера на кухне</h1>

    <div class="aspect-w-16 aspect-h-9">
        <div id="kitchen-stream" class="w-full h-full rounded-lg bg-black"></div>
    </div>
</div>

{{-- Подключаем WebRTC SDK --}}
<script src="/flashphoner/fcs.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const videoContainer = document.getElementById('kitchen-stream');

        // Инициализация SDK
        Flashphoner.init({});

        // Подключение к серверу
        Flashphoner.connect({
            urlServer: "wss://{{ request()->getHost() }}/ws"  // Или 'http://localhost:8080' если без прокси
        }).on(Flashphoner.constants.EVENT.CONNECTED, function () {
            // Создаем плеер
            Flashphoner.createSession({urlServer: "wss://{{ request()->getHost() }}/ws"})
                .on(Flashphoner.constants.SESSION_EVENT.ESTABLISHED, function (session) {
                    session.createStream({
                        name: "kitchen", // Название потока, который стримит WCS
                        display: videoContainer,
                        remote: true
                    }).on(Flashphoner.constants.STREAM_STATUS.PENDING, function (stream) {
                        console.log("Подключение к камере...");
                    }).on(Flashphoner.constants.STREAM_STATUS.PLAYING, function () {
                        console.log("Камера подключена!");
                    }).on(Flashphoner.constants.STREAM_STATUS.STOPPED, function () {
                        console.warn("Стрим остановлен");
                    }).on(Flashphoner.constants.STREAM_STATUS.FAILED, function () {
                        console.error("Ошибка подключения к камере");
                    }).play();
                });
        });
    });
</script>
@endsection
