<?php

namespace SmartCms\FastOrders\Routes;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use SmartCms\Core\Models\Field;
use SmartCms\Core\Repositories\Field\FieldRepository;
use SmartCms\Core\Services\ScmsResponse;
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

    public function form(Request $request)
    {
        $fields = [];
        $formFields = setting('fastorder.fields', []);
        $formFields = array_map(function ($field) {
            return $field['field_id'] ?? 0;
        }, $formFields);
        $formFields = array_filter($formFields, function ($field) {
            return $field !== 0;
        });
        foreach (Field::query()->whereIn('id', $formFields)->get() as $field) {
            $fields[] = FieldRepository::make()->find($field->id)->get();
        }
        $fields = ['class' => '', 'fields' => $fields];
        return new ScmsResponse(
            data: [
                'fields' => [$fields],
            ],
        );
    }
}
