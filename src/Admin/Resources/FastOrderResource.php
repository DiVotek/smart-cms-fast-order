<?php

namespace SmartCms\FastOrders\Admin\Resources;

use Filament\Forms;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;
use SmartCms\FastOrders\Admin\Resources\FastOrderResource\Pages\ListFastOrders;
use SmartCms\FastOrders\Models\FastOrder;
use SmartCms\Store\Models\OrderStatus;
use SmartCms\Store\Models\Product;

class FastOrderResource extends Resource
{
   protected static ?string $model = FastOrder::class;

   protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

   public static function getNavigationGroup(): ?string
   {
      return _nav('catalog');
   }
   public static function table(Table $table): Table
   {
      return $table
         ->columns([
            Tables\Columns\TextColumn::make('id')
               ->label('Order ID')
               ->sortable(),
            Tables\Columns\TextColumn::make('product.name')
               ->label('Product Name')
               ->sortable(),
            Tables\Columns\SelectColumn::make('orderStatusId.name')
               ->label('Order Status')
               ->options(OrderStatus::query()->pluck('name', 'id'))
               ->sortable(),
            Tables\Columns\TextColumn::make('created_at')
               ->label('Created At')
               ->sortable()
               ->toggleable(),
            Tables\Columns\TextColumn::make('updated_at')
               ->label('Updated At')
               ->sortable()
               ->toggleable(),
         ])
         ->filters([
            Tables\Filters\SelectFilter::make('order_status')
               ->label('Order Status')
               ->options(OrderStatus::query()->pluck('name', 'id')),
            Tables\Filters\SelectFilter::make('product')
               ->label('Product')
               ->options(Product::query()->whereIn('id', FastOrder::query()->distinct('product_id')->pluck('product_id'))->pluck('name', 'id'))
         ])
         ->actions([
            ViewAction::make()
               ->label('View')
               ->modalHeading('Fast Order Details')
               ->modalWidth('lg')
               ->action(function (FastOrder $record) {
                  return [
                     'form' => [
                        Section::make('Order Details')
                           ->schema([
                              TextInput::make('Order ID')
                                 ->default($record->id)
                                 ->required()
                                 ->disabled(),
                              TextInput::make('Product Name')
                                 ->default($record->product->name)
                                 ->required()
                                 ->disabled(),
                              TextInput::make('Status')
                                 ->default($record->orderStatusId->name)
                                 ->required()
                                 ->disabled(),
                              TextInput::make('Created At')
                                 ->default($record->created_at->toFormattedDateString())
                                 ->required()
                                 ->disabled(),
                              TextInput::make('Updated At')
                                 ->default($record->updated_at->toFormattedDateString())
                                 ->required()
                                 ->disabled(),
                              KeyValue::make('Data')
                                 ->keyPlaceholder('Key')
                                 ->valuePlaceholder('Value')
                                 ->default($record->data),
                           ])
                           ->columns(1),
                     ]
                  ];
               })
         ]);
   }

   public static function getPages(): array
   {
      return [
         'index' => ListFastOrders::route('/'),
      ];
   }
}
