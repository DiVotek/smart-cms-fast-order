<?php

namespace SmartCms\FastOrders\Routes;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use SmartCms\Core\Models\Field;
use SmartCms\Core\Repositories\Field\FieldRepository;
use SmartCms\Core\Services\ScmsResponse;
use SmartCms\FastOrders\Models\FastOrder;
use SmartCms\Store\Models\OrderStatus;
use SmartCms\Store\Models\Product;

class FastOrderController
{
    public function add(Request $request)
    {
        $validation = [
            'product_id' => 'required|exists:' . Product::getDb() . ',id',
        ];
        $fastOrderFields = setting('fastorder.fields', []);
        $customAttributes = [];
        foreach ($fastOrderFields as $field) {
            $field = Field::query()->find($field['field_id']);
            if ($field->required) {
                $validation[strtolower($field->html_id)] = 'required';
            }
            if ($field->validation) {
                $validation[strtolower($field->html_id)] = $field->validation;
            }
            $customAttributes[strtolower($field->html_id)] = $field->label[current_lang()] ?? $field->label[main_lang()] ?? '';
        }
        $validator = Validator::make($request->all(), $validation);
        $validator->setAttributeNames($customAttributes);
        if ($validator->fails()) {
            return new ScmsResponse(false, [], $validator->errors()->toArray());
        }
        $data = [];
        foreach ($request->except(['_token', 'product_id']) as $key => $value) {
            $field = Field::query()->where('html_id', $key)->first();
            if ($field) {
                $data[$field->name] = $value;
            }
        }
        FastOrder::query()->create([
            'product_id' => $request->product_id,
            'order_status_id' => OrderStatus::query()->first()->id ?? 0,
            'data' => $data,
        ]);

        return new ScmsResponse();
    }

    public function form()
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
                'groups' => [
                    [
                        'class' => '',
                        'fields' => [$fields],
                    ],
                ]
            ],
        );
    }
}
