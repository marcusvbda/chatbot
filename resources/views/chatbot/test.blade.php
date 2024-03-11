@extends(config('supernova.modules_template', 'supernova::templates.default'))
@section('title', 'Chatbot')
@section('content')
    @livewire('supernova::breadcrumb', [
        'items' => [
            [
                'title' => 'Assistentes',
                'route' => '/assistants',
            ],
            [
                'title' => 'Assistente #' . $assistant->id,
                'route' => '/assistants/' . $assistant->id,
            ],
            [
                'title' => 'Chatbot',
                'route' => '/assistants/' . $assistant->id . '/test-bot',
            ],
        ],
    ])
    <style>
        #message-sections-parent {
            height: calc(100% - 230px);
        }

        @media (max-width: 640px) {
            #message-sections-parent {
                height: calc(100% - 400px);
            }
        }
    </style>
    <h4
        class="text-2xl md:text-3xl text-neutral-800 font-bold dark:text-neutral-200 flex items-center gap-3 flex justify-between flex-col md:flex-row gap-2 md:gap-3 mt-6 mb-2">
        <span class="order-2 md:order-1">Teste assistente : {{ $assistant->name }}</span>
    </h4>
    <div class="mb-20 flex flex-col bg-white shadow-xl rounded-lg mt-4 dark:bg-gray-800 overflow-hidden"
        id="message-sections-parent">
        @livewire('chatbot.messages-section', ['assistantId' => $assistant->openia_id])
        @livewire('chatbot.messages-input')
    </div>
@endsection
