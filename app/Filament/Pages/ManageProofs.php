<?php

namespace App\Filament\Pages;

use App\Filament\Resources\TransactionResource;
use App\Models\PaymentSetting;
use App\Models\Transaction;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\ActionSize;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;

class ManageProofs extends Page implements HasActions, HasForms
{
    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->hasAdminPermission('manage_proofs');
    }
    use InteractsWithActions;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Proofs';

    protected static ?string $title = 'Payment Proofs';

    protected static string $view = 'filament.pages.manage-proofs';

    public ?array $settingsData = [];

    public array $selectedTransactions = [];

    public bool $selectAll = false;

    public function mount(): void
    {
        $settings = PaymentSetting::current();

        $this->form->fill([
            'proof_retention_days' => $settings->proof_retention_days ?? 30,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Proof retention')
                    ->description('Proof images are automatically deleted after this many days. Set to 0 to keep proofs indefinitely.')
                    ->schema([
                        TextInput::make('proof_retention_days')
                            ->label('Delete proofs after (days)')
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                    ]),
            ])
            ->statePath('settingsData');
    }

    #[Computed]
    public function proofs(): Collection
    {
        return Transaction::query()
            ->with('booking')
            ->whereNotNull('proof_of_payment')
            ->latest('updated_at')
            ->get();
    }

    public function saveSettings(): void
    {
        $state = $this->form->getState();

        PaymentSetting::current()->update([
            'proof_retention_days' => (int) $state['proof_retention_days'],
        ]);

        Notification::make()
            ->title('Proof settings saved')
            ->success()
            ->send();
    }

    public function updatedSelectAll(bool $value): void
    {
        if ($value) {
            $this->selectedTransactions = $this->proofs
                ->pluck('id')
                ->map(fn (int $id): string => (string) $id)
                ->all();
        } else {
            $this->selectedTransactions = [];
        }
    }

    public function updatedSelectedTransactions(): void
    {
        $allIds = $this->proofs
            ->pluck('id')
            ->map(fn (int $id): string => (string) $id)
            ->all();

        $this->selectAll = ! empty($allIds)
            && count($this->selectedTransactions) === count($allIds);
    }

    public function deleteSelected(): void
    {
        if (empty($this->selectedTransactions)) {
            return;
        }

        $transactions = Transaction::query()
            ->whereKey($this->selectedTransactions)
            ->whereNotNull('proof_of_payment')
            ->get();

        foreach ($transactions as $transaction) {
            $transaction->deleteProof();
        }

        $count = $transactions->count();
        $this->selectedTransactions = [];
        $this->selectAll = false;

        Notification::make()
            ->title($count === 1 ? '1 proof deleted' : "{$count} proofs deleted")
            ->success()
            ->send();
    }

    public function deleteProof(int $transactionId): void
    {
        $transaction = Transaction::query()
            ->whereKey($transactionId)
            ->whereNotNull('proof_of_payment')
            ->firstOrFail();

        $transaction->deleteProof();

        $this->selectedTransactions = array_values(array_filter(
            $this->selectedTransactions,
            fn (string $id): bool => (int) $id !== $transactionId,
        ));

        $this->updatedSelectedTransactions();

        Notification::make()
            ->title('Proof deleted')
            ->success()
            ->send();
    }

    public function viewTransactionUrl(Transaction $transaction): string
    {
        return TransactionResource::getUrl('view', ['record' => $transaction]);
    }

    public function deleteSelectedAction(): Action
    {
        return Action::make('deleteSelected')
            ->label('Delete selected')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading('Delete selected proofs')
            ->modalDescription('Delete the selected proof images? This cannot be undone.')
            ->modalSubmitActionLabel('Delete')
            ->action(fn () => $this->deleteSelected())
            ->disabled(fn (): bool => empty($this->selectedTransactions));
    }

    public function deleteProofAction(): Action
    {
        return Action::make('deleteProof')
            ->label('Delete')
            ->color('danger')
            ->size(ActionSize::Small)
            ->requiresConfirmation()
            ->modalHeading('Delete proof')
            ->modalDescription('Delete this proof image? This cannot be undone.')
            ->modalSubmitActionLabel('Delete')
            ->action(function (array $arguments): void {
                $this->deleteProof((int) $arguments['transactionId']);
            })
            ->extraAttributes(['class' => 'flex-1']);
    }
}
