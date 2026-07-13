<div class="space-y-6">
    @if ($showThankYou)
        <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-6 text-center">
            <h3 class="text-xl font-semibold text-emerald-900">Thank you for your booking!</h3>
            <p class="mt-3 text-sm text-emerald-800">
                Your proof of payment has been received. We will verify your payment and update your booking status shortly.
                A confirmation email has been sent to <span class="font-medium">{{ $transaction->booking->client_email }}</span>.
            </p>
            <p class="mt-2 text-sm text-emerald-700">
                Transaction: <span class="font-semibold">{{ $transaction->booking->transaction_number }}</span>
            </p>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:justify-center">
            <a
                href="{{ url('/?transaction_number=' . urlencode($transaction->booking->transaction_number) . '&show_cancel_suggestion=1') }}"
                class="inline-flex items-center justify-center rounded-3xl px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:opacity-90"
                style="background:#216417;"
            >
                Done
            </a>
            <a
                href="{{ url('/book/status?transaction_number=' . urlencode($transaction->booking->transaction_number)) }}"
                class="inline-flex items-center justify-center rounded-3xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
            >
                Check my booking
            </a>
        </div>
    @else
        <div>
            <label class="block text-sm font-medium text-slate-700">Upload proof of payment</label>
            <input type="file" wire:model="proof" class="mt-3 block w-full text-sm text-slate-600" />
            @error('proof')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <button type="button" wire:click.prevent="submitProof" class="inline-flex items-center justify-center rounded-3xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-500">
            Upload proof
        </button>
    @endif
</div>
