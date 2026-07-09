<x-filament-panels::page>
    <link rel="stylesheet" href="{{ asset('css/admin-proofs.css') }}">

    <div class="space-y-6">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <form wire:submit="saveSettings" class="space-y-4">
                {{ $this->form }}

                <x-filament::button type="submit">
                    Save settings
                </x-filament::button>
            </form>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                        <input
                            type="checkbox"
                            wire:model.live="selectAll"
                            class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800"
                        />
                        Select all
                    </label>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        {{ count($selectedTransactions) }} selected
                    </span>
                </div>

                {{ $this->deleteSelectedAction }}
            </div>
        </div>

        @if ($this->proofs->isEmpty())
            <div class="rounded-xl border border-dashed border-gray-300 bg-white p-10 text-center dark:border-gray-600 dark:bg-gray-900">
                <p class="text-base font-medium text-gray-900 dark:text-white">No payment proofs uploaded yet</p>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Proof images uploaded by clients will appear here.</p>
            </div>
        @else
            <div class="proofs-grid">
                @foreach ($this->proofs as $proof)
                    @php
                        $statusClass = match ($proof->payment_status) {
                            'paid' => 'proofs-status-paid',
                            'pending' => 'proofs-status-pending',
                            'cancelled' => 'proofs-status-cancelled',
                            default => 'proofs-status-default',
                        };
                    @endphp

                    <div
                        wire:key="proof-{{ $proof->id }}"
                        class="flex min-w-0 flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900"
                    >
                        <div class="flex items-center border-b border-gray-200 px-3 py-2 dark:border-gray-700">
                            <label class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-700 dark:text-gray-200">
                                <input
                                    type="checkbox"
                                    wire:model.live="selectedTransactions"
                                    value="{{ $proof->id }}"
                                    class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800"
                                />
                                Select
                            </label>
                        </div>

                        <div class="proof-image bg-gray-100 dark:bg-gray-800">
                            <img
                                src="{{ $proof->proof_url }}"
                                alt="Proof for {{ $proof->booking?->transaction_number ?? 'transaction' }}"
                            />
                        </div>

                        <div class="flex flex-1 flex-col gap-1 p-3 text-xs">
                            <div class="flex items-start justify-between gap-1">
                                <span class="truncate font-semibold text-gray-900 dark:text-white" title="{{ $proof->booking?->transaction_number }}">
                                    {{ $proof->booking?->transaction_number ?? 'Unknown' }}
                                </span>
                                <span class="shrink-0 rounded-full px-2 py-0.5 text-[10px] font-semibold uppercase {{ $statusClass }}">
                                    {{ $proof->payment_status }}
                                </span>
                            </div>

                            <p class="truncate text-gray-600 dark:text-gray-300" title="{{ $proof->booking?->client_name }}">
                                {{ $proof->booking?->client_name ?? '—' }}
                            </p>
                            <p class="truncate text-gray-500 dark:text-gray-400" title="{{ $proof->booking?->client_email }}">
                                {{ $proof->booking?->client_email ?? '—' }}
                            </p>
                            <p class="truncate text-gray-500 dark:text-gray-400">
                                {{ $proof->booking?->origin ?? '—' }} → {{ $proof->booking?->destination ?? '—' }}
                            </p>
                            <p class="font-medium text-gray-700 dark:text-gray-200">
                                ₱{{ number_format((float) ($proof->booking?->total_price ?? 0), 2) }}
                            </p>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500">
                                {{ $proof->updated_at?->format('M d, Y g:i A') }}
                            </p>
                        </div>

                        <div class="mt-auto flex gap-2 border-t border-gray-200 p-2 dark:border-gray-700">
                            <x-filament::button
                                tag="a"
                                href="{{ $this->viewTransactionUrl($proof) }}"
                                color="gray"
                                size="sm"
                                class="flex-1"
                            >
                                View
                            </x-filament::button>

                            <div class="flex flex-1">
                                {{ ($this->deleteProofAction)(['transactionId' => $proof->id]) }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <x-filament-actions::modals />
</x-filament-panels::page>
