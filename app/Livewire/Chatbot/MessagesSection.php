<?php

namespace App\Livewire\Chatbot;

use App\Http\Controllers\OpenIaController;
use Livewire\Component;
use Livewire\Attributes\On;

class MessagesSection extends Component
{
    public $messages = [];
    public $isTyping = false;
    public $threadId = null;
    public $assistantId = null;

    public function mount()
    {
        $this->threadId = $this->getThreadId();
        $this->assistantId = "asst_z8xZnRX0ytGtzV8SAEW06ds3";
    }

    private function getThreadId()
    {
        $thread = (new OpenIaController())->createThread();
        return data_get($thread, "id");
    }

    #[On('chatbot-new-message')]
    public function receiveNewMessage($message)
    {
        $this->messages[] = [
            'role' => 'user',
            'content' => $message
        ];
        $controller = new OpenIaController();
        $controller->addMessageToThread($this->threadId, $message);
        $this->dispatch("start-typing");
    }

    #[On('start-typing')]
    public function startTyping()
    {
        $this->isTyping = true;
        $this->dispatch("process-answerd");
        $this->dispatch("scroll-bottom");
    }

    #[On('process-answerd')]
    public function processAnswer()
    {
        $controller = new OpenIaController();
        $run = $controller->runThreadOnAssistant($this->threadId, $this->assistantId);
        $runIsCompleted = false;
        $runResponse = [];
        while (!$runIsCompleted) {
            $runResponse = $controller->checkRunStatus($this->threadId, data_get($run, "id"));
            $runIsCompleted = data_get($runResponse, "status") === "completed";
            sleep(.5);
        };
        $threadMessages = $controller->getThreadMessages($this->threadId);
        $this->messages[] = [
            'role' => 'assistant',
            'content' => data_get($threadMessages, "data.0.content.0.text.value")
        ];
        $this->isTyping = false;
        $this->dispatch("stop-typing");
        $this->dispatch("scroll-bottom");
    }

    public function render()
    {
        return view('livewire.chatbot.messages_section');
    }
}
