<?php

use Illuminate\Support\Facades\Route;
use SmartCms\FastOrders\Routes\FastOrderController;

Route::post('/api/fast-order', [FastOrderController::class, 'add'])->middleware(['lang', 'web'])->name('fast-order.create');
Route::get('/api/fast-order/form', [FastOrderController::class, 'form'])->middleware(['lang', 'web'])->name('fast-order.form');
