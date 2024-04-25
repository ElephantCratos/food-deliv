<x-app-layout>
    <div class="py-12 flex justify-center items-center" style="margin: 200px;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 w-full">
            <div class="bg-yellow-500 overflow-hidden shadow-sm sm:rounded-lg p-5 w-full text-gray-900"> 
                <div class="flex justify-center items-center">
                    <h1 class="text-xl p-4">Чат с {{ $user->name }}</h1>
                </div>
                <div class="text-left">
                    <div class="bg-white p-5 rounded-lg">
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
                <div class="text-center">
                    <form class="p-4" method="post" action="/chat/{{ $user->id }}">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                        <input type="text" name="content" placeholder="Введите сообщение" style="margin-top: 10px;" class="w-">
                        <button class="bg-white text-yellow-500 py-2 px-4 rounded-lg hover:bg-yellow-400" type="submit" style="margin-top: 10px;">Отправить</button> 
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
