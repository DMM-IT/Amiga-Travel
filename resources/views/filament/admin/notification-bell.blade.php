<div x-data="adminNotificationBell({ initialNotifications: [], initialTotalCount: 0, initialUnreadCount: 0 })"
     x-init="fetchDropdown()"
     @keydown.escape.window="actionMenuOpen = false; itemMenuOpen = null"
     class="relative">

    {{-- ───── Bell Trigger Button ───── --}}
    <div class="relative inline-flex">
        <button
            id="adminNotificationBellBtn"
            x-ref="trigger"
            type="button"
            @click.prevent="toggleDropdown()"
            class="relative flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-600 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-400/50 ring-offset-0"
            aria-label="Admin notifications"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6.002 6.002 0 0 0-4-5.659V4a2 2 0 10-4 0v1.341A6.002 6.002 0 0 0 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 1 1-6 0v-1m6 0H9" />
            </svg>
            {{-- Unread badge --}}
            <span
                x-show="unreadCount > 0"
                x-cloak
                class="absolute -right-1 -top-1 inline-flex h-[18px] min-w-[18px] items-center justify-center rounded-full bg-primary-500 px-1 text-[10px] font-bold leading-none text-white ring-2 ring-white dark:ring-gray-900"
                x-text="unreadCount > 99 ? '99+' : unreadCount"
            ></span>
        </button>

        {{-- ───── Dropdown Panel ───── --}}
        <div
            id="adminNotificationDropdown"
            x-ref="dropdown"
            x-show="dropdownStyles.opacity === 1 && dropdownOpen"
            x-cloak
            @click.outside="dropdownOpen = false; actionMenuOpen = false; itemMenuOpen = null"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-1 scale-[0.98]"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-1 scale-[0.98]"
            x-bind:style="dropdownStyles"
            class="rounded-2xl bg-white dark:bg-gray-900 shadow-2xl border border-gray-200 dark:border-gray-700 z-[11000] flex flex-col overflow-hidden"
        >

            {{-- ── Panel Header ── --}}
            <div class="shrink-0 px-6 pt-6 pb-0 bg-white dark:bg-gray-900">
                {{-- Title row --}}
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-[17px] font-bold tracking-tight text-gray-900 dark:text-white pl-1">Notifications</h2>

                    {{-- Three-dot global actions --}}
                    <div class="relative" @click.stop>
                        <button
                            type="button"
                            @click.prevent="actionMenuOpen = !actionMenuOpen; itemMenuOpen = null"
                            :class="actionMenuOpen
                                ? 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 ring-2 ring-primary-400/60 dark:ring-primary-500/50'
                                : 'text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-800 bg-transparent'"
                            class="flex h-9 w-9 items-center justify-center rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-primary-400/50 ring-offset-0"
                            aria-label="Notification actions"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="5" r="1.4"/>
                                <circle cx="12" cy="12" r="1.4"/>
                                <circle cx="12" cy="19" r="1.4"/>
                            </svg>
                        </button>

                        {{-- Actions dropdown --}}
                        <div
                            x-show="actionMenuOpen"
                            @click.outside="actionMenuOpen = false"
                            x-cloak
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 top-full mt-2 w-56 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-2xl py-1 overflow-hidden z-[11001]"
                        >
                            {{-- Enable / Disable selection --}}
                            <button type="button"
                                @click.prevent="bulkMode = !bulkMode; actionMenuOpen = false"
                                class="group w-full flex items-center gap-3 px-4 py-2.5 text-left text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="16" height="16" rx="2" stroke-linecap="round" stroke-linejoin="round"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 3v4M8 3v4M3 11h18"/></svg>
                                <span x-text="bulkMode ? 'Disable selection' : 'Enable selection'"></span>
                            </button>

                            {{-- Select all --}}
                            <button type="button"
                                @click.prevent="bulkMode = true; toggleSelectAll(); actionMenuOpen = false"
                                class="group w-full flex items-center gap-3 px-4 py-2.5 text-left text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Select all
                            </button>

                            <div class="my-1 border-t border-gray-100 dark:border-gray-800"></div>

                            {{-- Mark selected as read --}}
                            <button type="button"
                                @click.prevent="markRead(); actionMenuOpen = false"
                                :disabled="selectedCount === 0"
                                class="group w-full flex items-center gap-3 px-4 py-2.5 text-left text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Mark selected as read
                            </button>

                            {{-- Mark selected as unread --}}
                            <button type="button"
                                @click.prevent="markUnread(); actionMenuOpen = false"
                                :disabled="selectedCount === 0"
                                class="group w-full flex items-center gap-3 px-4 py-2.5 text-left text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                Mark selected as unread
                            </button>

                            {{-- Delete selected --}}
                            <button type="button"
                                @click.prevent="deleteSelected(); actionMenuOpen = false"
                                :disabled="selectedCount === 0"
                                class="group w-full flex items-center gap-3 px-4 py-2.5 text-left text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/40 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-400 dark:text-red-500 group-hover:text-red-600 dark:group-hover:text-red-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Delete selected
                            </button>

                            <div class="my-1 border-t border-gray-100 dark:border-gray-800"></div>

                            {{-- Open all notifications --}}
                            <a
                                href="{{ \App\Filament\Pages\AdminNotifications::getUrl() }}"
                                @click="actionMenuOpen = false; dropdownOpen = false"
                                class="group flex items-center gap-3 px-4 py-2.5 text-left text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                Open all notifications
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Tab bar --}}
                <div class="flex items-center gap-3 border-b border-gray-200 dark:border-gray-800 -mx-6 px-6 pb-3 bg-white dark:bg-gray-900">
                    <button
                        type="button"
                        @click="activeTab = 'all'"
                        :class="activeTab === 'all'
                            ? 'border border-primary-500 bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300 font-semibold shadow-sm'
                            : 'border border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'"
                        class="inline-flex items-center gap-1.5 rounded-full px-3 py-2 text-sm transition-all focus:outline-none focus:ring-2 focus:ring-primary-400/40 ring-offset-0"
                    >All</button>
                    <button
                        type="button"
                        @click="activeTab = 'unread'"
                        :class="activeTab === 'unread'
                            ? 'border border-primary-500 bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300 font-semibold shadow-sm'
                            : 'border border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'"
                        class="inline-flex items-center gap-2 rounded-full px-3 py-2 text-sm transition-all focus:outline-none focus:ring-2 focus:ring-primary-400/40 ring-offset-0"
                    >
                        Unread
                        <span
                            x-show="unreadCount > 0"
                            x-cloak
                            class="inline-flex items-center justify-center h-[18px] min-w-[18px] px-1 rounded-full bg-primary-500 text-[10px] font-bold text-white leading-none"
                            x-text="unreadCount"
                        ></span>
                    </button>
                </div>

                {{-- Bulk selection bar --}}
                <div x-show="bulkMode" x-cloak class="flex items-center justify-between py-3 text-sm border-b border-gray-100 dark:border-gray-800 -mx-6 px-6 bg-white dark:bg-gray-900">
                    <label class="inline-flex items-center gap-2.5 cursor-pointer select-none text-gray-700 dark:text-gray-300">
                        <input
                            type="checkbox"
                            class="h-4 w-4 rounded border-gray-300 text-primary-500 focus:ring-primary-500 focus:ring-offset-0 dark:border-gray-600 dark:bg-gray-800 accent-primary-500"
                            @click="toggleSelectAll()"
                            :checked="allSelected"
                            :indeterminate="selectedCount > 0 && !allSelected"
                        >
                        <span class="font-medium">Select all</span>
                    </label>
                    <span x-show="selectedCount > 0" class="text-xs font-semibold text-primary-600 dark:text-primary-400" x-text="selectedCount + ' selected'"></span>
                </div>
            </div>

            {{-- ── Scrollable Notification List ── --}}
            <div class="flex-1 overflow-y-auto min-h-0 py-1.5 px-2 bg-white dark:bg-gray-900">

                {{-- Empty State --}}
                <template x-if="visibleNotifications.length === 0">
                    <div class="flex flex-col items-center justify-center py-14 px-4 text-center">
                        <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341A6.002 6.002 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200"
                           x-text="activeTab === 'unread' ? 'All caught up!' : 'No notifications'"></p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400"
                           x-text="activeTab === 'unread' ? 'No unread notifications right now.' : 'New notifications will appear here.'"></p>
                    </div>
                </template>

                {{-- Notification rows --}}
                <template x-for="notification in visibleNotifications" :key="notification.id">
                    <div
                        class="group relative flex items-start gap-2.5 rounded-xl px-3 py-3 mb-0.5 cursor-pointer transition-all select-none"
                        :class="!notification.is_read
                            ? 'border-l-[3px] border-primary-400 bg-primary-50 dark:bg-primary-950/20 hover:bg-primary-100/70 dark:hover:bg-primary-950/30 pl-[9px]'
                            : 'hover:bg-gray-50 dark:hover:bg-gray-800/60'"
                        @click="openNotification(notification)"
                    >
                        {{-- Checkbox (bulk mode only) --}}
                        <div x-show="bulkMode" @click.stop class="shrink-0 pt-0.5">
                            <input
                                type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 text-primary-500 focus:ring-primary-500 focus:ring-offset-0 dark:border-gray-600 dark:bg-gray-800 accent-primary-500"
                                :value="notification.id"
                                @change="toggleSelection(notification.id)"
                                :checked="selectedIds.includes(notification.id)"
                            >
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0 pr-8">
                            {{-- Title + message on same visual line --}}
                            <p class="text-[13px] leading-snug text-gray-900 dark:text-white">
                                <span class="font-semibold" x-text="notification.title"></span><span class="text-gray-500 dark:text-gray-400 ml-1" x-text="notification.message"></span>
                            </p>
                            {{-- Timestamp --}}
                            <p class="mt-1 text-[11px] font-medium"
                               :class="!notification.is_read ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400 dark:text-gray-500'"
                               x-text="formatTimeAgo(notification.created_at)"></p>
                        </div>

                        {{-- Per-item three-dot menu --}}
                        <div class="absolute right-2 top-2.5 opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity" @click.stop>
                            <div class="relative">
                                <button
                                    type="button"
                                    @click.prevent="itemMenuOpen = (itemMenuOpen === notification.id ? null : notification.id); actionMenuOpen = false"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 dark:text-gray-500 hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-gray-600 dark:hover:text-gray-300 transition-all focus:outline-none focus:ring-2 focus:ring-primary-400/40 ring-offset-0"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="5" r="1.3"/>
                                        <circle cx="12" cy="12" r="1.3"/>
                                        <circle cx="12" cy="19" r="1.3"/>
                                    </svg>
                                </button>

                                <div
                                    x-show="itemMenuOpen === notification.id"
                                    @click.outside="itemMenuOpen = null"
                                    x-cloak
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    class="absolute right-0 top-full mt-1 w-44 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-xl z-[11002] py-1 overflow-hidden"
                                >
                                    <button type="button"
                                        @click.prevent="notification.is_read ? markUnread([notification.id]) : markRead([notification.id]); itemMenuOpen = null"
                                        class="w-full px-4 py-2.5 text-left text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                                        x-text="notification.is_read ? 'Mark as unread' : 'Mark as read'"
                                    ></button>
                                    <button type="button"
                                        @click.prevent="deleteNotification(notification.id); itemMenuOpen = null"
                                        class="w-full px-4 py-2.5 text-left text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/40 transition-colors"
                                    >Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            {{-- ── Footer ── --}}
            <div class="shrink-0 border-t border-gray-100 dark:border-gray-800 px-2 py-2 bg-white dark:bg-gray-900">
                <a
                    href="{{ \App\Filament\Pages\AdminNotifications::getUrl() }}"
                    @click="dropdownOpen = false"
                    class="flex items-center justify-center gap-1.5 w-full rounded-xl py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                >
                    View all notifications
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    {{-- ───── Delete Confirmation Modal ───── --}}
    <div
        x-show="confirmingDelete"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[11003] flex items-center justify-center bg-black/40 backdrop-blur-sm px-4"
    >
        <div
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            class="w-full max-w-sm rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 p-6 shadow-2xl"
        >
            <div class="flex items-start gap-4">
                <div class="h-10 w-10 shrink-0 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Confirm deletion</h3>
                    <p class="mt-1.5 text-sm text-gray-600 dark:text-gray-400 leading-relaxed" x-text="deleteTitle"></p>
                </div>
            </div>
            <div class="mt-5 flex gap-3">
                <button type="button"
                    @click.prevent="confirmDelete()"
                    :disabled="busy"
                    class="flex-1 rounded-xl bg-red-600 hover:bg-red-700 disabled:opacity-70 px-4 py-2.5 text-sm font-semibold text-white transition-colors focus:outline-none focus:ring-2 focus:ring-red-400/60 ring-offset-0"
                >Delete</button>
                <button type="button"
                    @click.prevent="confirmingDelete = false; deleteTargetIds = []"
                    :disabled="busy"
                    class="flex-1 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 px-4 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-200 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400/50 ring-offset-0"
                >Cancel</button>
            </div>
        </div>
    </div>

    @include('filament.admin.notification-scripts')
</div>
