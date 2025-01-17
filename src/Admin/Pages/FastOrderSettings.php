<?php

namespace SmartCms\FastOrders\Admin\Pages;

use Closure;
use Filament\Forms\Components\Repeater;
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
         Repeater::make('fields')
                ->label('Fields')
                ->schema([
                    Select::make('field_id')
                        ->label('Field')
                        ->options(Field::all()->pluck('name', 'id')->toArray())
                        ->required(),
                ])
                ->required(),

            Toggle::make('send_notification')
                ->label('Send Notification to Admin')
                ->default(false),

            Select::make('default_order_status')
                ->label('Default Order Status')
                ->options(OrderStatus::pluck('name', 'id')->toArray())
                ->required()
                ->default(OrderStatus::first()->id),
      ];
    }
}
