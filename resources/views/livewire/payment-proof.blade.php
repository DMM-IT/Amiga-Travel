<div class="space-y-6">
    @if ($showThankYou)
        <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-4 sm:p-6 text-center">
            <h3 class="text-lg sm:text-xl font-semibold text-emerald-900">Thank you for your booking!</h3>
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
                class="inline-flex items-center justify-center rounded-3xl px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:opacity-90 w-full sm:w-auto"
                style="background:#216417;"
            >
                Done
            </a>
            <a
                href="{{ url('/book/status?transaction_number=' . urlencode($transaction->booking->transaction_number)) }}"
                class="inline-flex items-center justify-center rounded-3xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 w-full sm:w-auto"
            >
                Check my booking
            </a>
        </div>
    @else
        <div>
            <label class="block text-sm font-medium text-slate-700">Upload proof of payment</label>
            <div class="mt-3">
                <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-300 border-dashed rounded-2xl cursor-pointer bg-slate-50 hover:bg-slate-100">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        <p class="mb-1 text-sm text-slate-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                        <p class="text-xs text-slate-500">PNG, JPG, GIF up to 2MB</p>
                    </div>
                    <input type="file" wire:model.live="proof" class="hidden" />
                </label>
                @if($proof)
                    <div class="mt-3 text-sm text-emerald-700 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        File selected: {{ $proof->getClientOriginalName() }}
                    </div>
                @endif
                @error('proof')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
            
            @if($isUploading)
                <div class="mt-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-slate-600">Uploading...</span>
                        <span class="text-sm font-semibold text-emerald-700">{{ $uploadProgress }}%</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden">
                        <div 
                            class="bg-emerald-600 h-2.5 rounded-full transition-all duration-300 ease-out"
                            style="width: {{ $uploadProgress }}%"
                        ></div>
                    </div>
                </div>
            @endif
        </div>

        <button 
            type="button" 
            wire:click.prevent="submitProof" 
            class="inline-flex items-center justify-center rounded-3xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed w-full sm:w-auto"
            @disabled($isUploading || !$proof)
        >
            @if ($isUploading)
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Uploading...
            @else
                Upload proof
            @endif
        </button>
    @endif
</div>
