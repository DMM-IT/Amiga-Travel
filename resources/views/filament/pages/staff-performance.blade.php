@php use Filament\Support\Enums\MaxWidth; @endphp

<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Staff Performance Overview</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Performance metrics for each staff member based on when they verified the booking.</p>
            </div>
            
            <div class="w-full sm:w-64">
                {{ $this->form }}
            </div>
        </div>

        <!-- Staff Table -->
        <div class="overflow-hidden rounded-xl border border-gray-200 shadow-sm dark:border-gray-700 bg-white dark:bg-gray-900">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">
                                Staff Member
                            </th>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">
                                Total Bookings
                            </th>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">
                                Completed
                            </th>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">
                                Pending
                            </th>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">
                                Cancelled
                            </th>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">
                                Revenue Handled
                            </th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($staffStats as $staff)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-bold text-gray-900 dark:text-white">{{ $staff['name'] }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $staff['email'] }}</p>
                                        @if($staff['is_admin'])
                                            <span class="mt-1 inline-flex items-center rounded bg-purple-100 px-2 py-0.5 text-[10px] font-bold text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                Admin
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    <span class="font-bold text-base">{{ $staff['total_bookings_handled'] }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">
                                        {{ $staff['completed_bookings'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-800 dark:bg-amber-900 dark:text-amber-200">
                                        {{ $staff['pending_bookings'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-semibold text-red-800 dark:bg-red-900 dark:text-red-200">
                                        {{ $staff['cancelled_bookings'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-white tabular-nums">
                                    ₱{{ number_format($staff['total_revenue_handled'], 2) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-right">
                                    <button type="button" 
                                        x-data 
                                        x-on:click="$dispatch('open-modal', { id: 'staff-bookings-{{ $staff['id'] }}' })"
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-amber-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-amber-500 transition">
                                        View Bookings
                                    </button>
                                    
                                    <x-filament::modal 
                                        id="staff-bookings-{{ $staff['id'] }}" 
                                        width="4xl"
                                        :heading="'Bookings verified by ' . $staff['name'] . ' on ' . ($filterDate ?: 'All Time')">
                                        
                                        @php
                                            $bookings = $this->getStaffBookings($staff['id']);
                                        @endphp
                                        
                                        <div class="overflow-x-auto">
                                            <table class="w-full text-sm text-left">
                                                <thead class="text-xs text-gray-600 uppercase bg-gray-50 dark:bg-gray-800 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">
                                                    <tr>
                                                        <th class="px-4 py-3">Transaction #</th>
                                                        <th class="px-4 py-3">Client</th>
                                                        <th class="px-4 py-3">Route</th>
                                                        <th class="px-4 py-3">Status</th>
                                                        <th class="px-4 py-3">Amount</th>
                                                        <th class="px-4 py-3">Verified At</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                                                    @forelse($bookings as $booking)
                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                                            <td class="px-4 py-3 font-mono text-xs font-medium text-gray-900 dark:text-white">{{ $booking->transaction_number ?: "BK-{$booking->id}" }}</td>
                                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $booking->client_name }}</td>
                                                            <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-400">{{ $booking->origin }} → {{ $booking->destination }}</td>
                                                            <td class="px-4 py-3">
                                                                <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-[10px] font-semibold text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">
                                                                    {{ ucfirst($booking->status) }}
                                                                </span>
                                                            </td>
                                                            <td class="px-4 py-3 font-bold text-gray-900 dark:text-white tabular-nums text-xs">₱{{ number_format($booking->total_price, 2) }}</td>
                                                            <td class="px-4 py-3 text-xs text-gray-500 dark:text-gray-400">{{ $booking->verified_at?->format('M d, Y h:i A') ?? 'N/A' }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-400 dark:text-gray-500">
                                                                No bookings found for this staff member.
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </x-filament::modal>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-400 dark:text-gray-500">
                                No staff performance data found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>
