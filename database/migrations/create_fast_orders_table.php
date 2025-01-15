<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use SmartCms\FastOrders\Models\FastOrder;
use SmartCms\Store\Models\OrderStatus;
use SmartCms\Store\Models\Product;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(FastOrder::getDb(), function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class, 'product_id');
            $table->foreignIdFor(OrderStatus::class, 'order_status_id');
            $table->json('data');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(FastOrder::getDb());
    }
};
