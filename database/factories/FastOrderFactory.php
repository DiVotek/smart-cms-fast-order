<?php

namespace SmartCms\FastOrders\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use SmartCms\FastOrders\Models\FastOrder;
use SmartCms\Store\Models\OrderStatus;

class FastOrderFactory extends Factory
{
   protected $model = FastOrder::class;

   public function definition(): array
   {
      return [
         'product_id' => \SmartCms\Store\Database\Factories\ProductFactory::new()->state(['status' => 1])->create()->id,
         'order_status_id' => OrderStatus::query()->first(),
         'data' => $this->faker->randomElements(['key' => 'value', 'another_key' => 'another_value']),
      ];
   }
}
