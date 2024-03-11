<?php

namespace App\Http\Controllers;

use App\Models\Assistant;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class OpenIaController extends Controller
{
    private $model = "gpt-3.5-turbo";

    public function testBot($id)
    {
        $assistant = Assistant::findOrFail($id);
        return view('chatbot.test', compact('assistant'));
    }

    public function makeClient($model = "gpt-3.5-turbo"): PendingRequest
    {
        $configOpenIa = config("openia");
        $this->model = $model;
        return Http::timeout(30)
            ->retry(3, 100)
            ->baseUrl(data_get($configOpenIa, "base_url"))
            ->withHeaders([
                'OpenAI-Beta' => 'assistants=v1',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . data_get($configOpenIa, "api_key"),
            ]);
    }

    public function createThread(): array
    {
        $client = $this->makeClient();
        $result = $client->post('/v1/threads');
        return $result->json();
    }

    public function createAssistant($name, $instruction): array
    {
        $client = $this->makeClient();
        $result = $client->post('/v1/assistants', [
            'model' => $this->model,
            'instructions' => $instruction,
            "tools" => [
                ["type" => "code_interpreter"]
            ],
            'name' => $name,
        ]);
        return $result->json();
    }

    public function addMessageToThread($threadId, $message): array
    {
        $client = $this->makeClient();
        $result = $client->post('/v1/threads/' . $threadId . '/messages', [
            'role' => 'user',
            'content' => $message,
        ]);
        return $result->json();
    }

    public function runThreadOnAssistant($threadId, $assistantId): array
    {
        $client = $this->makeClient();
        $result = $client->post('/v1/threads/' . $threadId . '/runs', [
            'assistant_id' =>  $assistantId
        ]);
        return $result->json();
    }

    public function checkRunStatus($threadId, $runId): array
    {
        $client = $this->makeClient();
        $result = $client->get('/v1/threads/' . $threadId . '/runs/' . $runId);
        return $result->json();
    }

    public function getThreadMessages($threadId): array
    {
        $client = $this->makeClient();
        $result = $client->get('/v1/threads/' . $threadId . '/messages');

        return $result->json();
    }
}
