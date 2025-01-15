<?php

arch('it will not use debugging functions')
    ->expect(['dd', 'dump', 'ray'])
    ->each->not->toBeUsed();

arch('models should extend BaseModel')
    ->expect('\SmartCms\ViewedProducts\Models')
    ->toExtend('\SmartCms\Core\Models\BaseModel');

arch('models should use HasFactory trait')
    ->expect('\SmartCms\ViewedProducts\Models')
    ->toUseTrait('\Illuminate\Database\Eloquent\Factories\HasFactory');

arch('models should has suffix Model')
    ->expect('\SmartCms\FastOrders\Models')
    ->toHaveSuffix('Model');

arch('services should has service suffix')
    ->expect('\SmartCms\FastOrders\Services')
    ->toHaveSuffix('Service');

arch('commands should has Command suffix')
    ->expect('\SmartCms\FastOrders\Commands')
    ->toHaveSuffix('Command');

arch('events should has Event suffix')
    ->expect('\SmartCms\FastOrders\Events')
    ->toHaveSuffix('Event');

arch('events should be invokable')
    ->expect('\SmartCms\FastOrders\Events')
    ->toHaveMethod('__invoke');
