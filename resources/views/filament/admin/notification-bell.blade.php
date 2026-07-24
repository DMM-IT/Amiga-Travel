<div x-data="adminNotificationBell({ initialNotifications: [], initialTotalCount: 0, initialUnreadCount: 0 })" x-init="fetchDropdown()" class="relative">
    <div class="relative">
        <button
            type="button"
            @click.prevent="toggleDropdown()"
            class="relative flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-600 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
            aria-label="Admin notifications"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6.002 6.002 0 0 0-4-5.659V4a2 2 0 10-4 0v1.341A6.002 6.002 0 0 0 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 1 1-6 0v-1m6 0H9" />
            </svg>

            <span x-show="unreadCount > 0" class="absolute -right-1 -top-1 inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-amber-500 px-1 text-[10px] font-semibold text-white" x-text="unreadCount"></span>
        </button>

        <div
            x-show="dropdownOpen"
            x-cloak
            @click.outside="dropdownOpen = false"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed inset-x-4 top-20 mx-auto sm:absolute sm:inset-auto sm:right-0 sm:top-[calc(100%+0.5rem)] sm:mt-0 w-auto sm:w-[420px] rounded-[1.25rem] overflow-hidden bg-white dark:bg-gray-900 shadow-2xl border border-gray-200 dark:border-gray-700 z-[9999]"
        >
            <div class="sticky top-0 z-10 border-b border-gray-100 bg-white px-4 py-3.5 dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <div class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 dark:text-gray-400">Notifications</div>
                        <div class="mt-0.5 text-xs text-gray-700 dark:text-gray-300">Total <span class="font-semibold" x-text="totalCount"></span> &bull; Unread <span class="font-semibold" x-text="unreadCount"></span></div>
                    </div>
                    <div class="relative">
                        <button type="button" @click.prevent="actionMenuOpen = !actionMenuOpen" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 bg-white px-2.5 py-1.5 text-[12px] font-semibold text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800">
                            Actions
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.25 8.27a.75.75 0 01-.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="actionMenuOpen" @click.outside="actionMenuOpen = false" x-cloak class="absolute right-0 mt-1.5 w-44 rounded-xl border border-gray-200 bg-white py-1.5 shadow-lg dark:border-gray-700 dark:bg-gray-900 z-[10000]">
                            <button type="button" @click.prevent="toggleSelectAll(); actionMenuOpen = false" class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-800">Select all</button>
                            <button type="button" @click.prevent="markRead(); actionMenuOpen = false" :disabled="selectedCount === 0" class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-40 dark:text-gray-200 dark:hover:bg-gray-800">Mark as read</button>
                            <button type="button" @click.prevent="markUnread(); actionMenuOpen = false" :disabled="selectedCount === 0" class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-40 dark:text-gray-200 dark:hover:bg-gray-800">Mark as unread</button>
                            <button type="button" @click.prevent="deleteSelected(); actionMenuOpen = false" :disabled="selectedCount === 0" class="w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50 disabled:opacity-40 dark:text-red-400 dark:hover:bg-red-900/50">Delete selected</button>
                        </div>
                    </div>
                </div>
                <div class="mt-2.5 flex items-center justify-between gap-3 text-[12px] text-gray-500 dark:text-gray-400">
                    <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" class="h-4 w-4 rounded border-gray-300 text-amber-500 focus:ring-amber-500" @click="toggleSelectAll()" :checked="allSelected">
                        Select all
                    </label>
                    <span x-show="selectedCount > 0" class="font-medium text-amber-600 dark:text-amber-400" x-text="selectedCount + ' selected'"></span>
                </div>
            </div>

            <div class="divide-y divide-gray-100 dark:divide-gray-800 max-h-[340px] sm:max-h-[380px] overflow-y-auto pb-10">
                <template x-if="notifications.length === 0">
                    <div class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                        You have no notifications.
                    </div>
                </template>

                <template x-for="notification in notifications" :key="notification.id">
                    <div class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800/60 transition-colors">
                        <input type="checkbox" class="mt-1 h-4 w-4 rounded border-gray-300 text-amber-500 focus:ring-amber-500 shrink-0" :value="notification.id" @change="toggleSelection(notification.id)" :checked="selectedIds.includes(notification.id)">
                        <div class="flex-1 min-w-0">
                            <button type="button" @click.prevent="openNotification(notification)" class="w-full text-left focus:outline-none">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex items-center gap-1.5 min-w-0">
                                        <span class="text-[14px] font-semibold leading-tight text-gray-900 dark:text-gray-100 break-words" x-text="notification.title"></span>
                                        <span x-show="!notification.is_read" class="inline-flex h-2 w-2 shrink-0 rounded-full bg-amber-500"></span>
                                    </div>
                                    <span class="shrink-0 text-[11.5px] text-gray-400 dark:text-gray-500 mt-0.5" x-text="notification.created_at"></span>
                                </div>
                                <div class="mt-1 text-[13px] leading-relaxed text-gray-600 dark:text-gray-400 whitespace-normal break-words" x-text="notification.message"></div>
                            </button>
                        </div>
                        <div class="relative shrink-0">
                            <button type="button" @click.prevent="itemMenuOpen = itemMenuOpen === notification.id ? null : notification.id" class="rounded-full p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-500 dark:hover:bg-gray-800 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </button>
                            <div x-show="itemMenuOpen === notification.id" @click.outside="itemMenuOpen = null" x-cloak class="absolute right-0 mt-1 w-40 rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-900 z-[10000]">
                                <button type="button" @click.prevent="notification.is_read ? markUnread([notification.id]) : markRead([notification.id]); itemMenuOpen = null" class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-800" x-text="notification.is_read ? 'Mark as unread' : 'Mark as read'"></button>
                                <button type="button" @click.prevent="deleteNotification(notification.id); itemMenuOpen = null" class="w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/50">Delete</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="border-t border-gray-100 bg-gray-50 px-4 py-3.5 dark:border-gray-800 dark:bg-gray-900/50">
                <a href="{{ \App\Filament\Pages\AdminNotifications::getUrl() }}" class="text-xs font-bold text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300">View all notifications</a>
            </div>
        </div>
    </div>

    @include('filament.admin.notification-scripts')
</div>
