<?php

namespace SmartCms\FastOrders;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use SmartCms\FastOrders\Admin\Actions\Navigation\Pages;
use SmartCms\FastOrders\Admin\Actions\Navigation\Resources;
use SmartCms\FastOrders\Components\FastOrder;

class FastOrderServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'fast-orders');
        Event::listen('cms.admin.navigation.resources', Resources::class);
        Event::listen('cms.admin.navigation.settings_pages', Pages::class);
    }

    public function boot()
    {
        $this->callAfterResolving(BladeCompiler::class, function (BladeCompiler $blade) {
            $prefix = config('smart_cms.store_kit.prefix', '');
            $blade->component('fast-order', FastOrder::class, $prefix);
        });
    }
}
