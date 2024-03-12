<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OpenIaController;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::prefix('chatbot')->group(function () {
        Route::prefix('assistants')->group(function () {
            Route::get('', [OpenIaController::class, 'getAssistants']);
            Route::get('{id}', [OpenIaController::class, 'findAssistant']);
            Route::delete('{id}', [OpenIaController::class, 'deleteAssistant']);
        });
        Route::prefix('threads')->group(function () {
            Route::post('', [OpenIaController::class, 'createThread']);
            Route::post('{id}/message', [OpenIaController::class, 'addMessageToThread']);
            Route::get('{id}/messages', [OpenIaController::class, 'getThreadMessages']);
            Route::get('{id}/run-status/{runId}', [OpenIaController::class, 'runThreadOnAssistant']);
            Route::post('{id}/{assistantId}/run', [OpenIaController::class, 'runThreadOnAssistant']);
        });
    });
});
