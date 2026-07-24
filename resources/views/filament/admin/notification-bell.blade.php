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
                x-transition.opacity.duration.150
                class="absolute right-0 z-50 mt-2 w-[36rem] max-w-[calc(100vw-1rem)] rounded-xl overflow-hidden bg-white dark:bg-gray-900 shadow-xl border border-gray-200 dark:border-gray-700"
                style="top:calc(100% + 0.5rem);"
            >
                <div class="sticky top-0 z-10 border-b border-gray-100 bg-white px-4 py-3 dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <div class="text-[10px] font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-400">Notifications</div>
                            <div class="text-sm text-gray-900 dark:text-gray-100">Total <span x-text="totalCount"></span>, unread <span x-text="unreadCount"></span></div>
                        </div>
                        <div class="relative">
                            <button type="button" @click.prevent="actionMenuOpen = !actionMenuOpen" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-[11px] font-semibold text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800">
                                Actions
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.25 8.27a.75.75 0 01-.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="actionMenuOpen" @click.outside="actionMenuOpen = false" x-cloak class="absolute right-0 mt-2 w-44 rounded-xl border border-gray-200 bg-white py-2 shadow-lg dark:border-gray-700 dark:bg-gray-900">
                                <button type="button" @click.prevent="toggleSelectAll(); actionMenuOpen = false" class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-800">Select all</button>
                                <button type="button" @click.prevent="markRead(); actionMenuOpen = false" :disabled="selectedCount === 0" class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-40 dark:text-gray-200 dark:hover:bg-gray-800">Mark as read</button>
                                <button type="button" @click.prevent="markUnread(); actionMenuOpen = false" :disabled="selectedCount === 0" class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-40 dark:text-gray-200 dark:hover:bg-gray-800">Mark as unread</button>
                                <button type="button" @click.prevent="deleteSelected(); actionMenuOpen = false" :disabled="selectedCount === 0" class="w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50 disabled:opacity-40 dark:text-red-400 dark:hover:bg-red-900/50">Delete selected</button>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center justify-between gap-3 text-xs text-gray-500 dark:text-gray-400">
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" class="h-4 w-4 rounded border-gray-300 text-amber-500 focus:ring-amber-500" @click="toggleSelectAll()" :checked="allSelected">
                            Select all
                        </label>
                        <span x-show="selectedCount > 0" class="font-medium text-gray-700 dark:text-gray-200" x-text="selectedCount + ' selected'"></span>
                    </div>
                </div>

                <div class="divide-y divide-gray-100 dark:divide-gray-800 max-h-[calc(100vh-14rem)] overflow-y-auto">
                    <template x-if="notifications.length === 0">
                        <div class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                            You have no notifications.
                        </div>
                    </template>

                    <template x-for="notification in notifications" :key="notification.id">
                        <div class="flex items-start gap-3 px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-800">
                            <input type="checkbox" class="mt-2 h-4 w-4 rounded border-gray-300 text-amber-500 focus:ring-amber-500" :value="notification.id" @change="toggleSelection(notification.id)" :checked="selectedIds.includes(notification.id)">
                            <div class="flex-1 min-w-0">
                                <button type="button" @click.prevent="openNotification(notification)" class="w-full text-left">
                                    <div class="flex items-center justify-between gap-3">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-semibold" :class="notification.is_read ? 'text-gray-900 dark:text-gray-100' : 'text-gray-900 dark:text-white'" x-text="notification.title"></span>
                                                <span x-show="!notification.is_read" class="inline-flex h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                                            </div>
                                            <div class="mt-1 text-[11px] text-gray-500 dark:text-gray-400" x-text="notification.message"></div>
                                        </div>
                                        <div class="shrink-0 text-[10px] text-gray-400" x-text="notification.created_at"></div>
                                    </div>
                                </button>
                            </div>
                            <div class="relative">
                                <button type="button" @click.prevent="itemMenuOpen = itemMenuOpen === notification.id ? null : notification.id" class="rounded-full p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </button>
                                <div x-show="itemMenuOpen === notification.id" @click.outside="itemMenuOpen = null" x-cloak class="absolute right-0 mt-2 w-40 rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-900">
                                    <button type="button" @click.prevent="notification.is_read ? markUnread([notification.id]) : markRead([notification.id]); itemMenuOpen = null" class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-800" x-text="notification.is_read ? 'Mark as unread' : 'Mark as read'"></button>
                                    <button type="button" @click.prevent="deleteNotification(notification.id); itemMenuOpen = null" class="w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/50">Delete</button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="border-t border-gray-100 bg-gray-50 px-4 py-3 dark:border-gray-800 dark:bg-gray-900">
                    <a href="{{ \App\Filament\Pages\AdminNotifications::getUrl() }}" class="text-sm font-semibold text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300">View all notifications</a>
                </div>
            </div>
        </div>

    @include('filament.admin.notification-scripts')
</div>
