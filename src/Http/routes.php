<?php

use Illuminate\Support\Facades\Route;
use Tekquora\Watermark\Http\Controllers\WatermarkController;

Route::get('/', [WatermarkController::class, 'edit'])->name('edit');
Route::post('/', [WatermarkController::class, 'update'])->name('update');
