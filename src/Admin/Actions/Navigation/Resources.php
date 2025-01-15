<?php

namespace SmartCms\FastOrders\Admin\Actions\Navigation;

use SmartCms\FastOrders\Admin\Resources\FastOrderResource;

class Resources
{
   public function __invoke(array &$items)
   {
      $items = array_merge([
         FastOrderResource::class,
      ], $items);
   }
}
