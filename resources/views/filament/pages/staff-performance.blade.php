@php use Filament\Support\Enums\MaxWidth; @endphp

<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Staff Performance Overview</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Performance metrics for each staff member based on when they verified the booking.</p>
            </div>
            
            <div class="w-64">
                {{ $this->form }}
            </div>
        </div>

        <!-- Staff Table -->
        <div class="overflow-hidden rounded-lg border border-gray-200 shadow-sm dark:border-gray-700">
            <table class="w-full">
                <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            Staff Member
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            Total Bookings
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            Completed
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            Pending
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            Cancelled
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            Revenue Handled
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($staffStats as $staff)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $staff['name'] }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $staff['email'] }}</p>
                                    @if($staff['is_admin'])
                                        <span class="mt-1 inline-block rounded-full bg-purple-100 px-2 py-1 text-[10px] font-semibold text-purple-700 dark:bg-purple-900 dark:text-purple-200">
                                            Admin
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                <span class="font-semibold">{{ $staff['total_bookings_handled'] }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="rounded-lg bg-emerald-100 px-2 py-1 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-200">
                                    {{ $staff['completed_bookings'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="rounded-lg bg-amber-100 px-2 py-1 text-amber-700 dark:bg-amber-900 dark:text-amber-200">
                                    {{ $staff['pending_bookings'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="rounded-lg bg-red-100 px-2 py-1 text-red-700 dark:bg-red-900 dark:text-red-200">
                                    {{ $staff['cancelled_bookings'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                                ₱{{ number_format($staff['total_revenue_handled'], 2) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-right">
                                <button type="button" 
                                    x-data 
                                    x-on:click="$dispatch('open-modal', { id: 'staff-bookings-{{ $staff['id'] }}' })"
                                    class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 font-medium">
                                    View Bookings
                                </button>
                                
                                <x-filament::modal id="staff-bookings-{{ $staff['id'] }}" width="4xl">
                                    <x-slot name="heading">
                                        Bookings verified by {{ $staff['name'] }} on {{ $filterDate }}
                                    </x-slot>
                                    
                                    @php
                                        $bookings = $this->getStaffBookings($staff['id']);
                                    @endphp
                                    
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-sm text-left">
                                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                                <tr>
                                                    <th class="px-4 py-3">Transaction #</th>
                                                    <th class="px-4 py-3">Client</th>
                                                    <th class="px-4 py-3">Status</th>
                                                    <th class="px-4 py-3">Price</th>
                                                    <th class="px-4 py-3">Verified Time</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($bookings as $booking)
                                                    <tr class="border-b dark:border-gray-700">
                                                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $booking->transaction_number }}</td>
                                                        <td class="px-4 py-3">{{ $booking->client_name }}</td>
                                                        <td class="px-4 py-3">
                                                            <span class="px-2 py-1 text-xs rounded-full 
                                                                {{ $booking->status === 'confirmed' ? 'bg-emerald-100 text-emerald-800' : 
                                                                   ($booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">
                                                                {{ ucfirst($booking->status) }}
                                                            </span>
                                                        </td>
                                                        <td class="px-4 py-3">₱{{ number_format($booking->total_price, 2) }}</td>
                                                        <td class="px-4 py-3 text-gray-500">{{ $booking->verified_at ? $booking->verified_at->format('h:i A') : '-' }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">No bookings verified on this date.</td>
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
                            <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                No staff members found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Summary Stats -->
        @if($staffStats->isNotEmpty())
            <div class="grid gap-4 md:grid-cols-3">
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Staff</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $staffStats->count() }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Combined Bookings</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $staffStats->sum('total_bookings_handled') }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Combined Revenue</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">₱{{ number_format($staffStats->sum('total_revenue_handled'), 2) }}</p>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
