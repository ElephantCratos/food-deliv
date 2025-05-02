<!-- resources/views/custom_components/dialogue.blade.php -->
<x-app-layout>
    <div class="py-12 flex justify-center items-center min-h-screen bg-gray-50">
        <div class="max-w-4xl w-full sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-2xl p-6 space-y-6 border border-gray-200">

                <!-- Заголовок -->
                <div class="text-center">
                    <h1 class="text-2xl font-bold text-gray-800">
                        Чат с {{ $client->id === \App\Http\Controllers\ChatController::SUPPORT_USER_ID
                            ? 'Поддержкой'
                            : $client->name }}
                    </h1>
                </div>

                <!-- Сообщения -->
                <div id="messages" class="space-y-4 max-h-[400px] overflow-y-auto px-2">
                    @forelse ($messages as $message)
                        @php
                            $supportId = \App\Http\Controllers\ChatController::SUPPORT_USER_ID;
                            $me        = Auth::user();
                            $meId      = $me->id;

                            // Сообщение "моё", если:
                            //  - sender_id = мой реальный ID
                            //  - или я support/manager и sender_id = supportId
                            $isMe = ($message->sender_id === $meId)
                                  || ($me->hasAnyRole(['support','manager'])
                                      && $message->sender_id === $supportId);

                            $isSupport = $message->sender_id === $supportId;
                        @endphp

                        <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[75%] px-5 py-3 rounded-2xl shadow-sm text-sm leading-snug
                                {{ $isMe
                                    ? 'bg-white text-gray-800 border border-gray-300 rounded-bl-none'
                                    : ($isSupport
                                        ? 'bg-blue-50 text-blue-900 border border-blue-200 rounded-br-none'
                                        : 'bg-gray-100 text-gray-900 rounded-br-none') }}">
                                <p class="font-semibold mb-1">
                                    {{ $isMe
                                        ? 'Я'
                                        : ($isSupport
                                            ? 'Поддержка'
                                            : ($message->sender->name ?? 'Пользователь')) }}
                                </p>
                                <p>{{ $message->content }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center">Нет сообщений</p>
                    @endforelse
                </div>

                <!-- Форма отправки -->
                <form
                    x-data="chatForm()"
                    @submit.prevent="sendMessage"
                    class="flex items-center gap-4"
                >
                    @csrf
                    <textarea
                        x-model="content"
                        placeholder="Введите сообщение..."
                        class="flex-grow px-4 py-2 rounded-full border border-gray-300
                               focus:outline-none focus:ring-2 focus:ring-yellow-400
                               text-sm resize-none h-12"
                    ></textarea>

                    <button
                        type="submit"
                        :disabled="isSending"
                        class="bg-yellow-500 hover:bg-yellow-600 transition text-white
                               px-5 py-2 rounded-full text-sm font-semibold
                               disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        ➤
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Alpine.js -->
    <script>
        const CURRENT_USER_ID   = {{ Auth::id() }};
        const SUPPORT_USER_ID   = {{ \App\Http\Controllers\ChatController::SUPPORT_USER_ID }};
        const IS_SUPPORT_USER   = @json(Auth::user()->hasAnyRole(['support','manager']));
        const CLIENT_NAME       = @json($client->name);

        function chatForm() {
            return {
                content: '',
                isSending: false,

                sendMessage() {
                    if (this.isSending || !this.content.trim()) return;

                    this.isSending = true;

                    fetch('{{ route('chats.send', $chat->id) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ content: this.content })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.content = '';
                        this.isSending = false;

                        const container = document.getElementById('messages');
                        const bubble    = document.createElement('div');

                        // Логика "моё" сообщение
                        const isMe      = (data.sender_id === CURRENT_USER_ID)
                                       || (IS_SUPPORT_USER && data.sender_id === SUPPORT_USER_ID);
                        const isSupport = data.is_support;

                        const justify = isMe ? 'justify-end' : 'justify-start';

                        let bubbleStyle;
                        if (isMe) {
                            bubbleStyle = 'bg-white text-gray-800 border border-gray-300 rounded-bl-none';
                        } else if (isSupport) {
                            bubbleStyle = 'bg-blue-50 text-blue-900 border border-blue-200 rounded-br-none';
                        } else {
                            bubbleStyle = 'bg-gray-100 text-gray-900 rounded-br-none';
                        }

                        const senderLabel = isMe
                            ? 'Я'
                            : (isSupport
                                ? 'Поддержка'
                                : CLIENT_NAME);

                        bubble.className = `flex ${justify}`;
                        bubble.innerHTML = `
                            <div class="max-w-[75%] px-5 py-3 rounded-2xl shadow-sm text-sm leading-snug ${bubbleStyle}">
                                <p class="font-semibold mb-1">${senderLabel}</p>
                                <p>${data.content}</p>
                            </div>
                        `;
                        container.appendChild(bubble);
                        container.scrollTop = container.scrollHeight;
                    })
                    .catch(() => {
                        this.isSending = false;
                        alert('Ошибка при отправке сообщения');
                    });
                }
            }
        }
    </script>
</x-app-layout>
