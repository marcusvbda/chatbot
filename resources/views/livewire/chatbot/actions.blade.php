<div class="flex gap-4">
    @if ($canTest)
        <a href="/assistants/{{ $assistantId }}/test-bot">
            <button type="button"
                class="bg-blue-500 hover:bg-blue-700 dark:border-gray-300 dark:border text-white font-bold py-2 px-6 rounded transition dark:bg-gray-800 hover:dark:bg-gray-900">
                Testar assistente
            </button>
        </a>
    @endif
    @if ($canTrain)
        <button type="button" wire:click="trainAssistant" wire:confirm="Tem certeza que deseja treinar o assistente?"
            class="border border-blue-500 hover:bg-blue-700 text-blue-500 font-bold hover:text-white py-2 px-6 rounded transition dark:bg-gray-800 hover:dark:bg-gray-900">
            treinar assistente
        </button>
    @endif
</div>
