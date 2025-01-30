<?php

use Illuminate\Support\Facades\Route;
use SmartCms\FastOrders\Routes\FastOrderController;

Route::post('/api/fast-order', [FastOrderController::class, 'add'])->name('fast-order.create');
