<div class="flex flex-col flex-grow w-full overflow-hidden">
    <style>
        .text-sm p {
            font-size: 0.875rem;
            line-height: 1.25rem;
        }
    </style>

    <div class="flex flex-col flex-grow p-4 overflow-auto" id="message-section" assistant_id="{{ $assistantId }}"
        thread_id="{{ $threadId }}">
        @foreach ($messages as $message)
            @php
                $isUser = data_get($message, 'role') === 'user';
            @endphp
            <div class="flex w-full mt-2 space-x-3 max-w-xs {{ $isUser ? 'ml-auto justify-end' : '' }}">
                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600">
                </div>
                <div>
                    <div
                        class="p-3 rounded-r-lg rounded-bl-lg {{ $isUser ? 'text-white bg-blue-600 dark:bg-blue-800' : 'text-black bg-gray-300 dark:bg-gray-600 dark:text-white' }}">
                        <div class="text-sm"> {!! Markdown::parse(data_get($message, 'content')) !!}</div>
                    </div>
                </div>
            </div>
        @endforeach
        @if ($isTyping)
            <div class='flex space-x-2 items-center py-10'>
                <span class='sr-only'>Loading...</span>
                <div class='h-2 w-2 bg-gray-500 rounded-full animate-bounce [animation-delay:-0.3s]'></div>
                <div class='h-2 w-2 bg-gray-500 rounded-full animate-bounce [animation-delay:-0.15s]'></div>
                <div class='h-2 w-2 bg-gray-500 rounded-full animate-bounce'></div>
            </div>
        @endif
    </div>
</div>
@script
    <script>
        const scrollBottom = () => {
            const messagesSection = document.querySelector('#message-section');
            messagesSection.scrollTop = messagesSection.scrollHeight;
        }
        scrollBottom();
        window.Livewire.on('scroll-bottom', () => {
            setTimeout(() => {
                scrollBottom();
            });
        });
    </script>
@endscript
