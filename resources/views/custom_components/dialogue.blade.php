<x-app-layout>
    <div class="py-12 flex justify-center items-center min-h-screen bg-gray-50">
        <div class="max-w-4xl w-full sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-2xl p-6 space-y-6 border border-gray-200">
                <div class="text-center">
                    <h1 class="text-2xl font-bold text-gray-800">Чат с {{ $user->name }}</h1>
                </div>

                <div id="messages" class="space-y-4 max-h-[400px] overflow-y-auto px-2">
                    @forelse ($messages as $message)
                        <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[75%] px-5 py-3 rounded-2xl shadow-sm text-sm leading-snug
                                {{ $message->sender_id === auth()->id() 
                                    ? 'bg-gray-100 text-gray-900 rounded-br-none'
                                    : 'bg-white text-gray-800 border border-gray-200 rounded-bl-none' }}">
                                <p class="font-medium mb-1">
                                    {{ $message->sender_id === auth()->id() ? 'Вы' : $user->name }}
                                </p>
                                <p>{{ $message->content }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center">Нет сообщений</p>
                    @endforelse
                </div>

                <form 
                    x-data="chatForm()" 
                    @submit.prevent="sendMessage"
                    class="flex items-center gap-4"
                >
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                    <input type="hidden" name="chat_id" value="{{ $chat->id }}">

                    <input 
                        type="text" 
                        x-model="content"
                        placeholder="Введите сообщение..." 
                        class="flex-grow px-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-400 text-sm"
                    >

                    <button 
                        type="submit"
                        :disabled="isSending"
                        class="bg-yellow-500 hover:bg-yellow-600 transition text-white px-5 py-2 rounded-full text-sm font-semibold disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        ➤
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function chatForm() {
            return {
                content: '',
                isSending: false,

                sendMessage() {
                    if (this.isSending || !this.content.trim()) return;

                    this.isSending = true;

                    fetch('/chat/{{ $user->id }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            content: this.content,
                            receiver_id: '{{ $user->id }}',
                            chat_id: '{{ $chat->id }}'
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.content = '';
                        this.isSending = false;

                        const container = document.getElementById('messages');
                        const bubble = document.createElement('div');
                        bubble.className = 'flex justify-end';
                        bubble.innerHTML = `
                            <div class="max-w-[75%] px-5 py-3 rounded-2xl shadow-sm text-sm leading-snug bg-gray-100 text-gray-900 rounded-br-none">
                                <p class="font-medium mb-1">Вы</p>
                                <p>${data.content}</p>
                            </div>
                        `;
                        container.appendChild(bubble);
                        container.scrollTop = container.scrollHeight;
                    })
                    .catch(error => {
                        console.error('Ошибка:', error);
                        this.isSending = false;
                        alert('Ошибка при отправке. Проверь сервер.');
                    });
                }
            }
        }
    </script>
</x-app-layout>
