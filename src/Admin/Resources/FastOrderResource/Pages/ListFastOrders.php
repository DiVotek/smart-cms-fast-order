<?php

namespace SmartCms\FastOrders\Admin\Resources\FastOrderResource\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\Actions\Action as ActionsAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
      $user_notification_fields = [];
      $button_name_fields = [];
      $form_button_fields = [];
      foreach (get_active_languages() as $lang) {
         $user_notification_fields[] = Hidden::make('fastorder.user_notification.' . $lang->slug);
         $button_name_fields[] = Hidden::make('fastorder.button_name.' . $lang->slug);
         $form_button_fields[] = Hidden::make('fastorder.form_button.' . $lang->slug);
      }
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
               ...$user_notification_fields,
               ...$button_name_fields,
               ...$form_button_fields,
               TextInput::make('fastorder.user_notification.default')
                  ->label(__('fast-orders::trans.user_notification'))
                  ->suffixAction(ActionsAction::make(_fields('translates'))
                     ->hidden(function () {
                        return ! is_multi_lang();
                     })
                     ->icon(function () {
                        $translates = setting('fastorder.user_notification', []);
                        $translates = array_filter($translates);
                        if (count($translates) == count(get_active_languages()) + 1) {
                           return 'heroicon-o-check-circle';
                        }
                        return 'heroicon-o-exclamation-circle';
                     })->form(function ($form) {
                        $fields = [];
                        $languages = get_active_languages();
                        foreach ($languages as $language) {
                           $fields[] = TextInput::make($language->slug)->label(__('fast-orders::trans.user_notification') . ' (' . $language->name . ')');
                        }

                        return $form->schema($fields);
                     })
                     ->fillForm(function () {
                        return setting('fastorder.user_notification', []);
                     })
                     ->action(function ($data, $set) {
                        foreach ($data as $key => $value) {
                           $set('fastorder.user_notification.' . $key, $value);
                        }
                     })),
               TextInput::make('fastorder.button_name.default')->label(__('fast-orders::trans.button_name'))
                  ->suffixAction(ActionsAction::make(_fields('translates'))
                     ->hidden(function () {
                        return ! is_multi_lang();
                     })
                     ->icon(function () {
                        $translates = setting('fastorder.button_name', []);
                        $translates = array_filter($translates);
                        if (count($translates) == count(get_active_languages()) + 1) {
                           return 'heroicon-o-check-circle';
                        }
                        return 'heroicon-o-exclamation-circle';
                     })->form(function ($form) {
                        $fields = [];
                        $languages = get_active_languages();
                        foreach ($languages as $language) {
                           $fields[] = TextInput::make($language->slug)->label(__('fast-orders::trans.button_name') . ' (' . $language->name . ')');
                        }

                        return $form->schema($fields);
                     })
                     ->fillForm(function () {
                        return setting('fastorder.button_name', []);
                     })
                     ->action(function ($data, $set) {
                        foreach ($data as $key => $value) {
                           $set('fastorder.button_name.' . $key, $value);
                        }
                     })),
               TextInput::make('fastorder.form_button.default')->label(__('fast-orders::trans.form_button'))
                  ->suffixAction(ActionsAction::make(_fields('translates'))
                     ->hidden(function () {
                        return ! is_multi_lang();
                     })
                     ->icon(function () {
                        $translates = setting('fastorder.form_button', []);
                        $translates = array_filter($translates);
                        if (count($translates) == count(get_active_languages()) + 1) {
                           return 'heroicon-o-check-circle';
                        }
                        return 'heroicon-o-exclamation-circle';
                     })->form(function ($form) {
                        $fields = [];
                        $languages = get_active_languages();
                        foreach ($languages as $language) {
                           $fields[] = TextInput::make($language->slug)->label(__('fast-orders::trans.form_button') . ' (' . $language->name . ')');
                        }

                        return $form->schema($fields);
                     })
                     ->fillForm(function () {
                        return setting('fastorder.form_button', []);
                     })
                     ->action(function ($data, $set) {
                        foreach ($data as $key => $value) {
                           $set('fastorder.form_button.' . $key, $value);
                        }
                     })),
            ])
            ->fillForm(function () {
               return [
                  'fastorder' => [
                     'default_order_status' => setting('fastorder.default_order_status'),
                     'send_notification' => setting('fastorder.send_notification'),
                     'fields' => setting('fastorder.fields'),
                     'button_name' => setting('fastorder.button_name', []),
                     'form_button' => setting('fastorder.form_button', []),
                     'user_notification' => setting('fastorder.user_notification', []),
                  ],
               ];
            })
            ->action(function (array $data) {
               setting([
                  'fastorder.default_order_status' => $data['fastorder']['default_order_status'],
                  'fastorder.send_notification' => $data['fastorder']['send_notification'],
                  'fastorder.fields' => $data['fastorder']['fields'],
                  'fastorder.button_name' => $data['fastorder']['button_name'] ?? [],
                  'fastorder.form_button' => $data['fastorder']['form_button'] ?? [],
                  'fastorder.user_notification' => $data['fastorder']['user_notification'] ?? [],
               ]);
            }),
      ];
   }
}
