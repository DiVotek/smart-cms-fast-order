<?php

namespace SmartCms\FastOrders\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use SmartCms\Core\Models\BaseModel;
use SmartCms\Store\Models\OrderStatus;
use SmartCms\Store\Models\Product;

class FastOrder extends BaseModel
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'data' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function orderStatusId()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }
}
