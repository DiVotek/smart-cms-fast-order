<?php

namespace SmartCms\FastOrders;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use SmartCms\Core\SmartCmsPanelManager;
use SmartCms\FastOrders\Admin\Actions\Navigation\Pages;
use SmartCms\FastOrders\Admin\Actions\Navigation\Resources;
use SmartCms\FastOrders\Components\FastOrder;
use SmartCms\FastOrders\Events\NavigationResources;
use SmartCms\FastOrders\Events\ProductEntityTransform;

class FastOrderServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'fast-orders');
        SmartCmsPanelManager::registerHook('navigation.resources', NavigationResources::class);
    }
}
