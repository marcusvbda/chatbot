<?php

use App\Http\Controllers\OpenIaController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web']], function () {
    Route::group(['middleware' => ['supernova-default-middleware']], function () {
        Route::get('chatbot', [OpenIaController::class, 'index']);
    });
});
