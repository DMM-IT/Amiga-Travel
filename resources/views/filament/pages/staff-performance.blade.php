@php use Filament\Support\Enums\MaxWidth; @endphp

<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Staff Performance Overview</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Performance metrics for each staff member</p>
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
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($staffStats as $staff)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
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
