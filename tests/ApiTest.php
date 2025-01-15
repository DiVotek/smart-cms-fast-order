<?php

use Illuminate\Support\Facades\Route;
use SmartCms\FastOrders\Models\FastOrder;
use SmartCms\FastOrders\Routes\FastOrderController;

beforeEach(function () {
   Route::post('/api/fast-order', [FastOrderController::class, 'add']);
});

it('can create a fast order through the API', function () {
   $productId = \SmartCms\Store\Database\Factories\ProductFactory::new()->state(['status' => 1])->create()->id;
   $data = [
       'name' => 'John Doe',
   ];

   $response = $this->postJson('/api/fast-order', [
       'product_id' => $productId,
       'data' => $data,
   ]);

   $response->assertStatus(201);

   $this->assertDatabaseHas(FastOrder::getDb(), [
       'product_id' => $productId,
       'data' => json_encode($data),
   ]);
});

it('returns validation error for invalid product_id', function () {
   $data = [
       'product_id' => 9999,
       'data' => [
           'name' => 'Jane Doe',
       ],
   ];

   $response = $this->postJson('/api/fast-order', $data);

   $response->assertStatus(422);
});

it('returns validation error for missing required data', function () {
   $productId = \SmartCms\Store\Database\Factories\ProductFactory::new()->state(['status' => 1])->create()->id;

   $response = $this->postJson('/api/fast-order', [
      'product_id' => $productId,
   ]);

   $response->assertStatus(422);
});

it('returns validation error for empty state', function () {
   $data = [
   ];

   $response = $this->postJson('/api/fast-order', $data);

   $response->assertStatus(422);
});
