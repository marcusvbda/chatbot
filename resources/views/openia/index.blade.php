@extends(config('supernova.modules_template', 'supernova::templates.default'))
@section('title', 'Chatbot')
@section('content')
    @livewire('supernova::breadcrumb', [
        'items' => [
            [
                'title' => 'Chatbot',
                'route' => '/chatbot',
            ],
        ],
    ])
    <style>
        #message-sections-parent {
            height: calc(100% - 170px);
        }

        @media (max-width: 640px) {
            #message-sections-parent {
                height: calc(100% - 400px);
            }
        }
    </style>
    <div class="mb-20 flex flex-col bg-white shadow-xl rounded-lg mt-4 dark:bg-gray-800 overflow-hidden"
        id="message-sections-parent">
        @livewire('chatbot.messages-section')
        @livewire('chatbot.messages-input')
    </div>
@endsection
