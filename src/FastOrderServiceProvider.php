<?php

namespace SmartCms\FastOrders;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use SmartCms\FastOrders\Admin\Actions\Navigation\Resources;
use SmartCms\FastOrders\Admin\Actions\Navigation\SettingsPages;

class FastOrderServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadRoutesFrom(__DIR__.'/Routes/web.php');
        Event::listen('cms.admin.navigation.resources', Resources::class);
        Event::listen('cms.admin.navigation.settings_pages', SettingsPages::class);
    }

    public function boot()
    {

    }
}
