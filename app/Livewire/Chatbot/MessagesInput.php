<?php

namespace App\Livewire\Chatbot;

use Livewire\Component;
use Livewire\Attributes\On;

class MessagesInput extends Component
{
    public $message = "";
    public $disabled = false;

    public function submitMessage()
    {
        $this->validate();
        $this->dispatch("chatbot-new-message", $this->message);
        $this->message = "";
    }

    public function getRules()
    {
        return [
            'message' => 'required|string|max:100'
        ];
    }

    #[On('start-typing')]
    public function startTyping()
    {
        $this->message = "";
        $this->disabled = true;
    }

    #[On('stop-typing')]
    public function stopTyping()
    {
        $this->message = "";
        $this->disabled = false;
        $this->dispatch("scroll-bottom");
        $this->dispatch("focus-on-input");
    }

    public function render()
    {
        return view('livewire.chatbot.message_input');
    }
}
