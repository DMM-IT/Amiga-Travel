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

    protected $rules = [
        'proof' => 'required|image|max:2048',
    ];

    public function submitProof()
    {
        $this->validate();

        $path = $this->proof->store('proofs', 'public');

        $this->transaction->update([
            'proof_of_payment' => $path,
            'payment_status' => 'pending',
        ]);

        session()->flash('message', 'Proof of payment uploaded successfully.');
    }

    public function render()
    {
        return view('livewire.payment-proof');
    }
}
