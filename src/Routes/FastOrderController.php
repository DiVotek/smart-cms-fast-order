<?php

namespace SmartCms\FastOrders\Routes;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use SmartCms\FastOrders\Models\FastOrder;
use SmartCms\Store\Models\OrderStatus;

class FastOrderController
{
   public function add(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:SmartCms\Store\Models\Product,id',
            'data' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $fastOrder = FastOrder::create([
            'product_id' => $request->product_id,
            'order_status_id' => OrderStatus::query()->first(),
            'data' => $request->data,
        ]);

        return response()->json([
            'message' => 'Fast order created successfully.',
            'data' => $fastOrder,
        ], 201);
    }
}
