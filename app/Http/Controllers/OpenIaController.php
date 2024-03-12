<?php

namespace App\Http\Controllers;

use App\Models\Assistant;
use App\Models\Setting;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OpenIaController extends Controller
{
    private $model = "gpt-3.5-turbo";

    public function testBot($id)
    {
        $assistant = Assistant::findOrFail($id);
        Setting::where('key', 'openia_api_key')->firstOrFail();
        return view('chatbot.test', compact('assistant'));
    }

    public function makeClient($headers = []): PendingRequest
    {
        $apiToken = Setting::where('key', 'openia_api_key')->firstOrFail()->value;
        $baseUrl = Setting::where('key', 'openia_base_url')->firstOrFail()->value;
        return Http::timeout(30)
            ->retry(3, 100)
            ->baseUrl($baseUrl)
            ->withHeaders(array_merge([
                'OpenAI-Beta' => 'assistants=v1',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $apiToken
            ], $headers));
    }

    public function createThread(): array
    {
        $client = $this->makeClient();
        $result = $client->post('/v1/threads');
        return $result->json();
    }

    private function createFileToUpload(Assistant $assistant): string
    {
        $content = '';
        foreach ($assistant->trainRows as $row) {
            $content .= json_encode([
                "prompt" => data_get($row, "prompt"),
                "completion" => data_get($row, "completion")
            ]) . "\n";
        }
        return $content;
    }

    private function uploadFileTraining(Assistant $assistant): string
    {
        $client = new Client();
        $fileName = "assistant_" . $assistant->id . "_training.jsonl";
        $apiToken = Setting::where('key', 'openia_api_key')->firstOrFail()->value;
        $baseUrl = Setting::where('key', 'openia_base_url')->firstOrFail()->value;
        $file = $this->createFileToUpload($assistant);
        $options = [
            'multipart' => [
                ['name' => 'purpose', 'contents' => 'fine-tune'],
                ['name' => 'file', 'contents' => $file, 'filename' => $fileName]
            ]
        ];
        $request = new Psr7Request('POST', "$baseUrl/v1/files", ['Authorization' => "Bearer $apiToken"]);
        $res = $client->sendAsync($request, $options)->wait();
        return data_get(json_decode($res->getBody()->getContents()), 'id');
    }

    public function removeAssistantFile(Assistant $assistant): void
    {
        $client = $this->makeClient();
        $client->delete('/v1/assistants/' . $assistant->openia_id . '/files/' . $assistant->file_id);
        $client->delete('/v1/files/' . $assistant->file_id);
        $assistant->file_id = null;
        $assistant->save();
    }

    private function createUploadAndLinkFile(Assistant $assistant): array
    {
        $client = $this->makeClient();
        if ($assistant->file_id) {
            $this->removeAssistantFile($assistant);
        }
        $fileId = $this->uploadFileTraining($assistant);
        $result = $client->post('/v1/assistants/' . $assistant->openia_id . '/files', [
            'file_id' => $fileId
        ]);
        $assistant->file_id = $fileId;
        $assistant->save();
        return $result->json();
    }

    public function trainAssistant($id)
    {
        $assistant = Assistant::findOrFail($id);
        $this->createUploadAndLinkFile($assistant);
    }

    public function findAssistant($id)
    {
        return Assistant::findOrFail($id);
    }

    public function getAssistants()
    {
        return Assistant::paginate();
    }

    public function apiCreateAssistant(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'instructions' => 'required',
        ]);

        $assistant = $this->createAssistant($request->name, $request->instruction);
        return response()->json($assistant);
    }

    public function deleteAssistant($id): array
    {
        $client = $this->makeClient();
        $result = $client->delete('/v1/assistants/' . $id);
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

    public function apiAddMessageToThread($id, Request $request): array
    {
        $request->validate(['message' => 'required']);
        return $this->addMessageToThread($id, $request->message);
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
