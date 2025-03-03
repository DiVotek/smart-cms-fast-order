<?php

namespace SmartCms\FastOrders\Admin\Actions\Navigation;

use SmartCms\FastOrders\Admin\Pages\FastOrdersSettings;

class SettingsPages
{
    public function __invoke(array &$items)
    {
        $items = array_merge([
            FastOrdersSettings::class,
        ], $items);
    }
}
