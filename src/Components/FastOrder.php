<?php

namespace SmartCms\FastOrders\Components;

use Illuminate\View\Component;
use SmartCms\Core\Models\Field;
use SmartCms\Store\Models\Product;

class FastOrder extends Component
{
   public int $product_id;
   public array $fields = [];
   public string $button;

   public function __construct(int $product_id, array $values = [], array $errors = [])
   {
      $fields = Field::query()->whereIn('id', _settings('fast_order.fields'))->get();
      $newFields = [];
      foreach ($fields as $field) {
         $name = strtolower($field->html_id);
         if (isset($values[$name])) {
            $field->value = $values[$name];
         }
         if (isset($errors[$name])) {
            $field->error = $errors[$name];
         }
         $newFields[] = $field;
      }
      $this->fields = $newFields;
      $this->button = _settings('fast_order.button', [])[current_lang()] ?? '';
   }

   public function render()
   {
      return <<<'blade'
         <form {{$attributes}} hx-trigger="submit" hx-swap="outerHTML"  hx-get="{{route('fast-order.create')}}" {{$attributes}} x-init="htmx.process($el)">
            <input type="hidden" name="product" value="{{$product_id}}">
            @foreach($fields as $field)
                <x-s::form.field :field="$field" />
            @endforeach
            <button type="submit" class="btn btn-primary">{{$button}}</button>
         </form>
      blade;
   }
}
