@extends('layouts.app')

@section('title', 'Кухня LIVE')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Камера на кухне</h1>

    <div class="aspect-w-16 aspect-h-9">
        <video id="kitchen-stream" controls autoplay muted class="w-full h-full rounded-lg bg-black"></video>
    </div>
</div>

{{-- Подключаем HLS.js --}}
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const video = document.getElementById('kitchen-stream');
        const hlsSrc = '/live/stream.m3u8';

        if (Hls.isSupported()) {
            const hls = new Hls();
            hls.loadSource(hlsSrc);
            hls.attachMedia(video);
        } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
            video.src = hlsSrc;
        }
    });
</script>
@endsection
