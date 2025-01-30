<?php

namespace SmartCms\FastOrders\Admin\Pages;

use Closure;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Pages\Dashboard;
use Outerweb\FilamentSettings\Filament\Pages\Settings as BaseSettings;
use Illuminate\Contracts\Support\Htmlable;
use SmartCms\Core\Models\Field;
use SmartCms\Store\Models\OrderStatus;

class FastOrdersSettings extends BaseSettings
{
    public static function getNavigationGroup(): ?string
    {
        return _nav('catalog');
    }

    public static function getNavigationLabel(): string
    {
        return __('fast-orders::trans.navigation_label');
    }

    public static function getNavigationIcon(): string|Htmlable|null
    {
        return null;
    }

    public function getBreadcrumbs(): array
    {
        return [
            Dashboard::getUrl() => _nav('dashboard'),
            _nav('settings'),
        ];
    }

    public function schema(): array|Closure
    {
        return [
            Section::make('Fast Orders')
                ->label(__('fast-orders::trans.navigation_label'))
                ->schema([
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
                                ->options(Field::all()->pluck('name', 'id')->toArray())
                                ->required(),
                        ])->required(),
                ])
        ];
    }
}
