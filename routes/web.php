<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CameraController;
use App\Http\Controllers\ImageController;

Route::get('/', [CameraController::class, 'index']);
Route::post('/capture', [CameraController::class, 'capture'])->name('capture');
Route::get('/images', [ImageController::class, 'index'])->name('images.index');
