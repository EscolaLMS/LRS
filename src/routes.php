<?php

use Illuminate\Support\Facades\Route;
use Trax\Auth\TraxRouting;
use EscolaLms\Lrs\Http\Controllers\LrsController;

Route::group(['prefix' => 'api/cmi5'], function () {
    Route::post('/fetch', [LrsController::class, 'fetch'])->name("cmi5.fetch");
    Route::group(['middleware' => ['auth:api']], function () {
        Route::get('/courses/{id}', [LrsController::class, 'lunchParams']);
    });
});

Route::group(['middleware' => ['auth:token']], function () {
    Route::view('/lrs/{any?}', 'trax-front-lrs::app')->where('any', '.*')->middleware('web');
});
