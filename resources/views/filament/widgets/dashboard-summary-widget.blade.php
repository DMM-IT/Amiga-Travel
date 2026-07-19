<div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h2 class="text-xl font-semibold text-white">Dashboard Summary</h2>
            <p class="mt-1 text-sm text-gray-300">Quick view of recent bookings, transactions, and exports.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('bookings.export.pdf') }}" class="inline-flex items-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-slate-900 shadow-sm transition hover:bg-slate-100">
                Download PDF
            </a>
            <a href="{{ route('bookings.export.csv') }}" class="inline-flex items-center rounded-lg border border-white/20 bg-transparent px-4 py-2 text-sm font-semibold text-white transition hover:border-white hover:bg-white/10">
                Download CSV
            </a>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-2">
        <div class="rounded-3xl border border-white/10 bg-slate-950/80 p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-white">Recent Bookings</h3>
            <div class="mt-4 overflow-hidden rounded-3xl border border-white/10 bg-slate-950">
                <table class="min-w-full text-left text-sm text-slate-200">
                    <thead class="bg-slate-900/80 text-slate-400">
                        <tr>
                            <th class="px-4 py-3">Transaction</th>
                            <th class="px-4 py-3">Client</th>
                            <th class="px-4 py-3">Route</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($recentBookings as $booking)
                            <tr class="hover:bg-white/5">
                                <td class="px-4 py-3">{{ $booking->transaction_number }}</td>
                                <td class="px-4 py-3">{{ $booking->client_name }}</td>
                                <td class="px-4 py-3">{{ $booking->origin }} → {{ $booking->destination }}</td>
                                <td class="px-4 py-3">{{ ucfirst($booking->status) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-sm text-slate-400">No recent bookings found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-3xl border border-white/10 bg-slate-950/80 p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-white">Recent Transactions</h3>
            <div class="mt-4 overflow-hidden rounded-3xl border border-white/10 bg-slate-950">
                <table class="min-w-full text-left text-sm text-slate-200">
                    <thead class="bg-slate-900/80 text-slate-400">
                        <tr>
                            <th class="px-4 py-3">Transaction</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Rebooking Fee</th>
                            <th class="px-4 py-3">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($recentTransactions as $transaction)
                            <tr class="hover:bg-white/5">
                                <td class="px-4 py-3">{{ $transaction->booking?->transaction_number ?? 'N/A' }}</td>
                                <td class="px-4 py-3">{{ ucfirst($transaction->payment_status) }}</td>
                                <td class="px-4 py-3">{{ $transaction->rebooking_fee ? '₱' . number_format($transaction->rebooking_fee, 2) : '-' }}</td>
                                <td class="px-4 py-3">{{ $transaction->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-sm text-slate-400">No recent transactions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
