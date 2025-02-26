<?php

namespace SmartCms\FastOrders\Events;

use SmartCms\Store\Repositories\Product\ProductEntityDto as ProductProductEntityDto;

class ProductEntityTransform
{
   public function __invoke(ProductProductEntityDto $dto)
   {
      $buttonName = setting('fastorder.button_name', []);
      $buttonName = $buttonName[current_lang()] ?? $buttonName['default'] ?? '';
      if ($buttonName) {
         $dto->setExtraValue('fast_order_button', $buttonName);
      }
   }
}
