@php
    $notifications = app(\App\Support\AdminNotificationFeed::class)->getForUser();
    $unreadCount = $notifications->count();
@endphp

<x-filament::dropdown placement="bottom-end" shift>
    <x-slot name="trigger">
        <button
            type="button"
            class="relative flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-600 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
            aria-label="Admin notifications"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6.002 6.002 0 0 0-4-5.659V4a2 2 0 10-4 0v1.341A6.002 6.002 0 0 0 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 1 1-6 0v-1m6 0H9" />
            </svg>

            @if ($unreadCount > 0)
                <span class="absolute -right-1 -top-1 inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-amber-500 px-1 text-[10px] font-semibold text-white">
                    {{ $unreadCount }}
                </span>
            @endif
        </button>
    </x-slot>

    @if ($notifications->isNotEmpty())
        <div class="w-80 sm:w-96 max-w-[calc(100vw-2rem)] rounded-xl overflow-hidden bg-white dark:bg-gray-900 shadow-xl border border-gray-200 dark:border-gray-700">
            <div class="sticky top-0 border-b border-gray-100 bg-white px-4 py-3 dark:border-gray-800 dark:bg-gray-900 rounded-t-xl">
                <div class="text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-400">
                    Notifications ({{ $unreadCount }})
                </div>
            </div>
            <div class="overflow-y-auto divide-y divide-gray-100 dark:divide-gray-800" style="max-height: calc(100vh - 8rem);">
                @foreach ($notifications as $notification)
                    <a href="{{ $notification['url'] }}" class="block px-4 py-3 hover:bg-gray-50 transition dark:hover:bg-gray-800">
                        <div class="flex flex-col gap-1">
                            <div class="flex items-start justify-between gap-3">
                                <div class="font-semibold text-sm text-gray-900 dark:text-gray-100">
                                    {{ $notification['title'] }}
                                </div>
                                <div class="flex-shrink-0 text-[11px] text-gray-400 whitespace-nowrap">
                                    {{ $notification['created_at']?->diffForHumans() ?? 'now' }}
                                </div>
                            </div>
                            <div class="text-xs text-gray-600 dark:text-gray-400 break-words">
                                {{ $notification['message'] }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @else
        <div class="w-80 sm:w-96 max-w-[calc(100vw-2rem)] rounded-xl overflow-hidden bg-white dark:bg-gray-900 shadow-xl border border-gray-200 dark:border-gray-700">
            <div class="sticky top-0 border-b border-gray-100 bg-white px-4 py-3 dark:border-gray-800 dark:bg-gray-900 rounded-t-xl">
                <div class="text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-400">
                    Notifications (0)
                </div>
            </div>
            <div class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                No notifications here!
            </div>
        </div>
    @endif
</x-filament::dropdown>
