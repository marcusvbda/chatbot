<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OpenIaController;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::prefix('openia')->group(function () {
        Route::post('/threads', [OpenIaController::class, 'createThread']);
        Route::post('/assistants', [OpenIaController::class, 'createAssistant']);
        Route::post('/threads/{thread_id}/messages', [OpenIaController::class, 'addMessageToThread']);
        Route::get('/threads/{thread_id}/messages', [OpenIaController::class, 'addMessageToThread']);
        Route::post('/threads/{thread_id}/assistants/{assistant_id}/run', [OpenIaController::class, 'runThreadOnAssistant']);
    });
});
