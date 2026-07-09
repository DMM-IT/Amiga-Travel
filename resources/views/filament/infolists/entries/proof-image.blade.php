@php
    /** @var \App\Models\Transaction $record */
    $record = $getRecord();
@endphp

@if ($record->proof_url)
    <img
        src="{{ $record->proof_url }}"
        alt="Proof of payment for {{ $record->booking?->transaction_number }}"
        class="max-h-80 rounded-xl border border-gray-200 object-contain dark:border-gray-700"
    />
@endif
