<?php

namespace SmartCms\FastOrders\Events;

use SmartCms\FastOrders\Admin\Resources\FastOrderResource;

class NavigationResources
{
    public function __invoke(array &$items)
    {
        $items = array_merge([
            FastOrderResource::class,
        ], $items);
    }
}
