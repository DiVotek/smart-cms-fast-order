<?php

namespace SmartCms\FastOrders\Admin\Resources;

use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;
use SmartCms\FastOrders\Admin\Resources\FastOrderResource\Pages\ListFastOrders;
use SmartCms\FastOrders\Models\FastOrder;
use SmartCms\Store\Models\OrderStatus;
use SmartCms\Store\Models\Product;

class FastOrderResource extends Resource
{
    protected static ?string $model = FastOrder::class;

    protected static ?string $navigationIcon = null;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationGroup(): ?string
    {
        return _nav('sales');
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
                    ->infolist(function ($infolist) {
                        return $infolist->schema([
                            TextEntry::make('product.name')
                                ->label('Product Name'),
                            TextEntry::make('orderStatusId.name')
                                ->label('Order Status'),
                            TextEntry::make('created_at')
                                ->label('Created At'),
                            TextEntry::make('updated_at')
                                ->label('Updated At'),
                            KeyValueEntry::make('data')
                                ->label('Data')
                                ->columnSpanFull()
                                ->keyLabel(null)
                                ->valueLabel(null),
                        ])->columns(2);
                    }),
                DeleteAction::make()
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFastOrders::route('/'),
        ];
    }
}
