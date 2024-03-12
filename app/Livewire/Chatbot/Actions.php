<?php

namespace App\Livewire\Chatbot;

use App\Http\Controllers\OpenIaController;
use App\Http\Supernova\Application;
use App\Models\Setting;
use Livewire\Component;

class Actions extends Component
{
    public $assistantId;
    public $canTrain = false;
    public $canTest = false;

    public function trainAssistant()
    {
        (new OpenIaController)->trainAssistant($this->assistantId);
        $application = app()->make(config('supernova.application', Application::class));
        $application::message("success", "Assistente treinada com sucesso !");
        return $this->redirect(route('supernova.modules.details', ['module' => 'assistants', 'id' => $this->assistantId]));
    }

    public function render()
    {
        $this->canTest = Setting::where('key', 'openia_api_key')->count() > 0;
        return view('livewire.chatbot.actions');
    }
}
