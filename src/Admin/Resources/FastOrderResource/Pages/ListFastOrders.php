<?php

namespace SmartCms\FastOrders\Admin\Resources\FastOrderResource\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\ListRecords;
use SmartCms\Core\Models\Field;
use SmartCms\FastOrders\Admin\Resources\FastOrderResource;
use SmartCms\Store\Models\OrderStatus;

class ListFastOrders extends ListRecords
{
   protected static string $resource = FastOrderResource::class;

   public function getBreadcrumbs(): array
   {
      if (config('shared.admin.breadcrumbs', false)) {
         return parent::getBreadcrumbs();
      }

      return [];
   }

   protected function getHeaderActions(): array
   {
      return [
         Action::make('help')->help('fast order help'),
         Action::make('settings')
            ->label('Settings')
            ->settings()
            ->form([
               Select::make('fastorder.default_order_status')
                  ->label(__('fast-orders::trans.default_order_status'))
                  ->options(OrderStatus::query()->pluck('name', 'id')->toArray())
                  ->required()
                  ->default(OrderStatus::query()->first()->id),
               Toggle::make('fastorder.send_notification')
                  ->label(__('fast-orders::trans.send_notification'))
                  ->default(false),
               Repeater::make('fastorder.fields')
                  ->label(__('fast-orders::trans.fields'))
                  ->schema([
                     Select::make('field_id')
                        ->label(__('fast-orders::trans.field'))
                        ->options(Field::query()->pluck('name', 'id')->toArray())
                        ->required(),
                  ])->required(),
            ])
            ->fillForm(function () {
               return [
                  'fastorder' => [
                     'default_order_status' => setting('fastorder.default_order_status'),
                     'send_notification' => setting('fastorder.send_notification'),
                     'fields' => setting('fastorder.fields'),
                  ],
               ];
            })
            ->action(function (array $data) {
               setting([
                  'fastorder.default_order_status' => $data['fastorder']['default_order_status'],
                  'fastorder.send_notification' => $data['fastorder']['send_notification'],
                  'fastorder.fields' => $data['fastorder']['fields'],
               ]);
            }),
      ];
   }
}
