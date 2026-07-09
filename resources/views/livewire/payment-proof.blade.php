<div class="space-y-6">
    <div>
        <label class="block text-sm font-medium text-slate-700">Upload proof of payment</label>
        <input type="file" wire:model="proof" class="mt-3 block w-full text-sm text-slate-600" />
        @error('proof')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
    </div>

    <button type="button" wire:click.prevent="submitProof" class="inline-flex items-center justify-center rounded-3xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-500">
        Upload proof
    </button>

    @if (session()->has('message'))
        <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-700">{{ session('message') }}</div>
    @endif

    @if ($transaction->proof_of_payment)
        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
            <p class="text-sm font-medium text-slate-700">Existing proof</p>
            <img src="{{ $transaction->proof_url }}" alt="Proof of payment" class="mt-3 rounded-3xl max-h-72 object-contain" />
        </div>
    @endif
</div>
