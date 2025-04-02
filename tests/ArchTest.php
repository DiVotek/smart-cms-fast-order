<?php

arch('models should extend BaseModel')
    ->expect('\SmartCms\FastOrders\Models')
    ->toExtend('\SmartCms\Core\Models\BaseModel');

arch('models should use HasFactory trait')
    ->expect('\SmartCms\FastOrders\Models')
    ->toUseTrait('\Illuminate\Database\Eloquent\Factories\HasFactory');

arch('events should be invokable')
    ->expect('\SmartCms\FastOrders\Events')
    ->toHaveMethod('__invoke');
