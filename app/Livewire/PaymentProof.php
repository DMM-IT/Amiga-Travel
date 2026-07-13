<?php

namespace App\Livewire;

use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithFileUploads;

class PaymentProof extends Component
{
    use WithFileUploads;

    public Transaction $transaction;

    public $proof;

    public bool $showThankYou = false;

    protected $rules = [
        'proof' => 'required|image|max:2048',
    ];

    public function mount(): void
    {
        $this->showThankYou = filled($this->transaction->proof_of_payment);
    }

    public function submitProof(): void
    {
        $this->validate();

        $path = $this->proof->store('proofs', 'public');

        $this->transaction->update([
            'proof_of_payment' => $path,
            'payment_status' => 'pending',
        ]);

        \Illuminate\Support\Facades\Mail::to($this->transaction->booking->client_email)
            ->send(new \App\Mail\PaymentProofReceived($this->transaction));

        $this->transaction->refresh();
        session(['cancellation_window_expires_for_' . $this->transaction->booking->transaction_number => now()->addMinutes(5)->timestamp]);
        $this->showThankYou = true;
    }

    public function render()
    {
        return view('livewire.payment-proof');
    }
}
