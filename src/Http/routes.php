<?php
use Illuminate\Support\Facades\Route;
use Tekquora\Watermark\Http\Controllers\WatermarkController;

Route::middleware(config('watermark.route.middleware'))
    ->prefix(config('watermark.route.prefix'))
    ->group(function () {
        Route::get('/', [WatermarkController::class, 'edit'])->name('watermark.edit');
        Route::post('/', [WatermarkController::class, 'update'])->name('watermark.update');
    });

