<?php

use Illuminate\Support\Facades\Route;

Route::post('login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
Route::get('me', [\App\Http\Controllers\Api\AuthController::class, 'me'])->middleware('auth:sanctum');
Route::get('logout', [\App\Http\Controllers\Api\AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('relate', [\App\Http\Controllers\Api\AuthController::class, 'relate'])->middleware('auth:sanctum');
Route::get('relationships', [\App\Http\Controllers\Api\AuthController::class, 'relationships'])->middleware('auth:sanctum');
