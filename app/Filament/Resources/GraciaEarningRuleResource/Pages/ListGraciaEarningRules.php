<?php

namespace App\Filament\Resources\GraciaEarningRuleResource\Pages;

use App\Filament\Resources\GraciaEarningRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGraciaEarningRules extends ListRecords
{
    protected static string $resource = GraciaEarningRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('referral_settings')
                ->label('Referral Settings')
                ->icon('heroicon-o-cog')
                ->color('warning')
                ->form([
                    \Filament\Forms\Components\TextInput::make('referrer_points')
                        ->label('Points for Referrer (Sharer)')
                        ->numeric()
                        ->required(),
                    \Filament\Forms\Components\TextInput::make('referee_points')
                        ->label('Points for Referee (New User)')
                        ->numeric()
                        ->required(),
                    \Filament\Forms\Components\TextInput::make('welcome_bonus')
                        ->label('First 100 Users Welcome Bonus')
                        ->numeric()
                        ->required(),
                ])
                ->mountUsing(function (\Filament\Forms\Form $form) {
                    $setting = \App\Models\WebsiteSetting::getOrCreateByPage('referrals');
                    $form->fill([
                        'referrer_points' => $setting->content['referrer_points'] ?? 10,
                        'referee_points' => $setting->content['referee_points'] ?? 10,
                        'welcome_bonus' => $setting->content['welcome_bonus'] ?? 50,
                    ]);
                })
                ->action(function (array $data): void {
                    $setting = \App\Models\WebsiteSetting::getOrCreateByPage('referrals');
                    $setting->content = [
                        'referrer_points' => $data['referrer_points'],
                        'referee_points' => $data['referee_points'],
                        'welcome_bonus' => $data['welcome_bonus'],
                    ];
                    $setting->save();
                    \Filament\Notifications\Notification::make()->title('Settings saved')->success()->send();
                }),
            Actions\CreateAction::make(),
        ];
    }
}
