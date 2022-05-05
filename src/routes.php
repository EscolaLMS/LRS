<?php

use EscolaLms\Lrs\Http\Controllers\StatementController;
use EscolaLms\Core\Http\Facades\Route;
use EscolaLms\Lrs\Http\Controllers\LrsController;

Route::group(['prefix' => 'api'], function () {
    Route::group(['prefix' => '/cmi5'], function () {
        Route::post('/fetch', [LrsController::class, 'fetch'])->name("cmi5.fetch");
        Route::group(['middleware' => Route::apply(['auth:api'])], function () {
            Route::get('/courses/{id}', [LrsController::class, 'launchParams']);
        });
    });

    Route::group(['prefix' => '/admin/cmi5', 'middleware' => Route::apply(['auth:api'])], function () {
        Route::get('/statements', [StatementController::class, 'statements']);
    });
});

Route::group(['middleware' => Route::apply(['auth:token'])], function () {
    Route::view('/lrs/{any?}', 'trax-front-lrs::app')->where('any', '.*')->middleware('web');
});
