<?php

namespace App\Filament\Pages;

use App\Models\PaymentSetting;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class ManagePaymentSettings extends Page implements HasForms
{
    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->hasAdminPermission('manage_payment_settings');
    }
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Payment Settings';

    protected static ?string $title = 'Payment Settings';

    protected static string $view = 'filament.pages.manage-payment-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = PaymentSetting::current();

        $this->form->fill([
            'fee_per_person' => $settings->fee_per_person,
            'fee_per_accommodation' => $settings->fee_per_accommodation,
            'qr_code_path' => $settings->qr_code_path,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Service Fee')
                    ->description('Added to every booking\'s total on the final review page, before payment. Not shown to clients while they\'re still browsing schedules and accommodations.')
                    ->schema([
                        TextInput::make('fee_per_person')
                            ->label('Fee per traveler (₱)')
                            ->helperText('Charged for every adult and child. Infants are not charged.')
                            ->numeric()
                            ->prefix('₱')
                            ->minValue(0)
                            ->required(),
                        TextInput::make('fee_per_accommodation')
                            ->label('Fee per accommodation (₱)')
                            ->helperText('Charged for each accommodation the client selects.')
                            ->numeric()
                            ->prefix('₱')
                            ->minValue(0)
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Payment QR Code')
                    ->description('This single QR code (e.g. your GCash QR) is shown to every client on the payment page.')
                    ->schema([
                        FileUpload::make('qr_code_path')
                            ->label('QR code image')
                            ->image()
                            ->directory('payment-qr')
                            ->visibility('public')
                            ->maxFiles(1),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        PaymentSetting::current()->update([
            'fee_per_person' => $state['fee_per_person'],
            'fee_per_accommodation' => $state['fee_per_accommodation'],
            'qr_code_path' => $state['qr_code_path'],
        ]);

        Notification::make()
            ->title('Payment settings saved')
            ->success()
            ->send();
    }
}
