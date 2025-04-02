<?php

namespace SmartCms\FastOrders\Admin\Resources\FastOrderResource\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\Actions\Action as ActionsAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use SmartCms\Core\Admin\Base\Pages\BaseListRecords;
use SmartCms\Core\Models\Field;
use SmartCms\FastOrders\Admin\Resources\FastOrderResource;

class ListFastOrders extends BaseListRecords
{
    protected static string $resource = FastOrderResource::class;

    protected function getResourceHeaderActions(): array
    {
        $user_notification_fields = [];
        $form_button_fields = [];
        foreach (get_active_languages() as $lang) {
            $user_notification_fields[] = Hidden::make('fastorder.user_notification.' . $lang->slug);
            $form_button_fields[] = Hidden::make('fastorder.form_button.' . $lang->slug);
        }
        return [
            Action::make('settings')
                ->label('Settings')
                ->settings()
                ->form([
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
                    ...$form_button_fields,
                ])
                ->fillForm(function () {
                    return [
                        'fastorder' => [
                            'send_notification' => setting('fastorder.send_notification'),
                            'fields' => setting('fastorder.fields'),
                            'form_button' => setting('fastorder.form_button', []),
                            'user_notification' => setting('fastorder.user_notification', []),
                        ],
                    ];
                })
                ->action(function (array $data) {
                    setting([
                        'fastorder.send_notification' => $data['fastorder']['send_notification'],
                        'fastorder.fields' => $data['fastorder']['fields'],
                        'fastorder.form_button' => $data['fastorder']['form_button'] ?? [],
                        'fastorder.user_notification' => $data['fastorder']['user_notification'] ?? [],
                    ]);
                }),
        ];
    }
}
