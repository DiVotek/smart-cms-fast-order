<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use SmartCms\FastOrders\Database\Factories\FastOrderFactory;
use SmartCms\FastOrders\Models\FastOrder;

uses(RefreshDatabase::class);

it('can create a fast order', function () {
   $fastOrder = FastOrderFactory::new()->create();

    expect($fastOrder)->toBeInstanceOf(FastOrder::class);
    expect(FastOrder::find($fastOrder->id))->not()->toBeNull();
});

it('validates required fields', function () {
    $this->expectException(\Illuminate\Database\QueryException::class);

    FastOrder::create([]);
});

it('has a valid product_id', function () {
   $fastOrder = FastOrderFactory::new()->create();

    expect($fastOrder->product_id)->not()->toBeNull();
    expect($fastOrder->product_id)->toBeInt();
});

it('stores data as a JSON array', function () {
    $data = ['key' => 'value'];
    $fastOrder = FastOrderFactory::new()->create(['data' => $data]);

    expect($fastOrder->data)->toBeArray();
    expect($fastOrder->data)->toMatchArray($data);
});

it('can retrieve related product', function () {
    $fastOrder = FastOrderFactory::new()->create();

    expect($fastOrder->product)->not()->toBeNull();
    expect($fastOrder->product->id)->toBe($fastOrder->product_id);
});
