<x-app-layout>
    <div class="py-12 flex justify-center items-center" style="margin: 50px;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 w-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-5 w-full"> <!-- Added a white background container for messages -->
                <div class="flex justify-center items-center">
                    <h1 class="text-xl p-4 text-white">Чат с {{ $user->name }}</h1>
                </div>
                <div style="text-align: left;"> <!-- Centered messages to the left -->
                    <div class="bg-white p-5" style="margin: 10px; border-radius: 10px;"> <!-- Added a white background for messages -->
                        @forelse ($messages as $message)
                            <div class="message">
                                <span class="sender">{{ $message->sender_id === auth()->user()->id ? 'Вы' : $user->name }}:</span>
                                <span class="content">{{ $message->content }}</span>
                            </div>
                        @empty
                            <p>Нет сообщений</p>
                        @endforelse
                    </div>
                </div>
                <div style="text-align: center;">
                    <form class="p-4" method="post" action="/chat/{{ $user->id }}">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                        <input type="text" name="content" placeholder="Введите сообщение" style="margin-top: 10px;">
                        <button class="text-white" type="submit" style="margin-top: 10px;">Отправить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
