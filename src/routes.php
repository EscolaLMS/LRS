<?php

use EscolaLms\Lrs\Http\Controllers\StatementController;
use EscolaLms\Lrs\Http\Controllers\LrsController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api'], function () {
    Route::group(['prefix' => '/cmi5'], function () {
        Route::post('/fetch', [LrsController::class, 'fetch'])->name("cmi5.fetch");
        Route::group(['middleware' => ['auth:api']], function () {
            Route::get('/courses/{id}', [LrsController::class, 'launchParams']);
        });
    });

    Route::group(['prefix' => '/admin/cmi5', 'middleware' => ['auth:api']], function () {
        Route::get('/statements', [StatementController::class, 'statements']);
    });
});

Route::group(['middleware' => ['auth:token']], function () {
    Route::view('/lrs/{any?}', 'trax-front-lrs::app')->where('any', '.*')->middleware('web');
});
