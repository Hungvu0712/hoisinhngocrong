<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Backend\V1\ServerController;

Route::prefix('v1')->group(function () {
    Route::apiResource('servers', ServerController::class);
});
