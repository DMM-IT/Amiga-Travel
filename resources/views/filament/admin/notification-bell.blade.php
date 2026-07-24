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
            class="fixed inset-x-4 top-20 mx-auto sm:absolute sm:inset-auto sm:right-0 sm:top-[calc(100%+0.5rem)] sm:mt-0 w-auto sm:w-[270px] rounded-2xl overflow-hidden bg-white dark:bg-gray-900 shadow-2xl border border-gray-200 dark:border-gray-800 z-[9999]"
        >
            <!-- Header (FB Style) -->
            <div class="px-4 pt-4 pb-2 text-gray-900 dark:text-white">
                <div class="flex items-center justify-between">
                    <h2 class="text-[20px] font-bold tracking-tight">Notifications</h2>
                    
                    <!-- Action menu toggle (...) -->
                    <div class="relative">
                        <button 
                            type="button" 
                            @click.prevent="actionMenuOpen = !actionMenuOpen" 
                            class="flex h-8 w-8 items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300 transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </button>
                        
                        <div x-show="actionMenuOpen" @click.outside="actionMenuOpen = false" x-cloak class="absolute right-0 mt-2 w-48 rounded-xl border border-gray-200 bg-white py-1.5 shadow-lg dark:border-gray-800 dark:bg-gray-900 z-[10000]">
                            <button type="button" @click.prevent="bulkMode = !bulkMode; actionMenuOpen = false" class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-800" x-text="bulkMode ? 'Disable Selection' : 'Enable Selection'"></button>
                            <button type="button" @click.prevent="toggleSelectAll(); actionMenuOpen = false" class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-800">Select all</button>
                            <button type="button" @click.prevent="markRead(); actionMenuOpen = false" :disabled="selectedCount === 0" class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-40 dark:text-gray-200 dark:hover:bg-gray-800">Mark as read</button>
                            <button type="button" @click.prevent="markUnread(); actionMenuOpen = false" :disabled="selectedCount === 0" class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-40 dark:text-gray-200 dark:hover:bg-gray-800">Mark as unread</button>
                            <button type="button" @click.prevent="deleteSelected(); actionMenuOpen = false" :disabled="selectedCount === 0" class="w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50 disabled:opacity-40 dark:text-red-400 dark:hover:bg-red-900/50">Delete selected</button>
                        </div>
                    </div>
                </div>

                <!-- Tabs (All / Unread) -->
                <div class="flex gap-2 mt-3">
                    <button 
                        type="button" 
                        @click="activeTab = 'all'" 
                        :class="activeTab === 'all' ? 'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-white' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800/50'" 
                        class="px-3.5 py-1.5 rounded-full text-[13px] font-semibold transition"
                    >
                        All
                    </button>
                    <button 
                        type="button" 
                        @click="activeTab = 'unread'" 
                        :class="activeTab === 'unread' ? 'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-white' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800/50'" 
                        class="px-3.5 py-1.5 rounded-full text-[13px] font-semibold transition"
                    >
                        Unread
                        <span x-show="unreadCount > 0" class="ml-1 px-1.5 py-0.5 rounded-full text-[11px] bg-amber-500 text-white" x-text="unreadCount"></span>
                    </button>
                </div>

                <!-- Bulk Selection Stats (only in bulk mode) -->
                <div x-show="bulkMode" class="mt-2.5 flex items-center justify-between text-[12px] text-gray-500 dark:text-gray-400 border-t border-gray-100 dark:border-gray-800 pt-2">
                    <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" class="h-4 w-4 rounded border-gray-300 text-amber-500 focus:ring-amber-500" @click="toggleSelectAll()" :checked="allSelected">
                        Select all
                    </label>
                    <span x-show="selectedCount > 0" class="font-medium text-amber-600 dark:text-amber-400" x-text="selectedCount + ' selected'"></span>
                </div>
            </div>

            <!-- Scrollable Content -->
            <div class="max-h-[380px] overflow-y-auto px-2 pb-4">
                <!-- Group helper properties to dynamically show headers -->
                <div x-data="{ 
                    hasUnread(notifs) { return notifs.some(n => !n.is_read) },
                    hasRead(notifs) { return notifs.some(n => n.is_read) }
                }">
                    <!-- Empty State -->
                    <template x-if="notifications.length === 0 || (activeTab === 'unread' && unreadCount === 0)">
                        <div class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                            You have no notifications.
                        </div>
                    </template>

                    <!-- All Tab Layout (Divided into "New" and "Earlier") -->
                    <template x-if="activeTab === 'all' && notifications.length > 0">
                        <div>
                            <!-- "New" Section Heading (only if there are unread notifications) -->
                            <template x-if="hasUnread(notifications)">
                                <div class="px-2 py-1 flex items-center justify-between">
                                    <span class="text-[13px] font-semibold text-gray-900 dark:text-white">New</span>
                                    <a href="{{ \App\Filament\Pages\AdminNotifications::getUrl() }}" class="text-xs text-blue-500 hover:underline">See all</a>
                                </div>
                            </template>
                            
                            <!-- "New" Notifications List -->
                            <div class="space-y-1">
                                <template x-for="notification in notifications.filter(n => !n.is_read)" :key="notification.id">
                                    <div class="group flex items-center gap-3 p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors relative cursor-pointer" @click="openNotification(notification)">
                                        <!-- Bulk Checkbox (only shown in bulkMode) -->
                                        <input x-show="bulkMode" @click.stop type="checkbox" class="h-4 w-4 rounded border-gray-300 text-amber-500 focus:ring-amber-500 shrink-0" :value="notification.id" @change="toggleSelection(notification.id)" :checked="selectedIds.includes(notification.id)">
                                        
                                        <!-- Text/Content -->
                                        <div class="flex-1 min-w-0 pr-6">
                                            <p class="text-[13px] leading-snug text-gray-900 dark:text-white break-words">
                                                <span class="font-bold text-gray-950 dark:text-gray-100" x-text="notification.title"></span>
                                                <span class="text-gray-700 dark:text-gray-300" x-text="notification.message"></span>
                                            </p>
                                            <p class="text-[12px] font-semibold text-blue-500 dark:text-blue-400 mt-1" x-text="formatTimeAgo(notification.created_at)"></p>
                                        </div>

                                        <!-- Unread Dot Indicator (Blue dot) -->
                                        <div class="h-3.5 w-3.5 rounded-full bg-blue-500 shrink-0 self-center"></div>

                                        <!-- Individual Options Trigger (hidden by default, shown on hover/click) -->
                                        <div class="absolute right-2 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity" @click.stop>
                                            <button type="button" @click.prevent="itemMenuOpen = itemMenuOpen === notification.id ? null : notification.id" class="rounded-full bg-white dark:bg-gray-900 p-1.5 text-gray-500 shadow-md hover:bg-gray-100 dark:hover:bg-gray-800 dark:text-gray-300 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                            </button>
                                            <div x-show="itemMenuOpen === notification.id" @click.outside="itemMenuOpen = null" x-cloak class="absolute right-0 mt-1.5 w-40 rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-800 dark:bg-gray-900 z-[10000]">
                                                <button type="button" @click.prevent="notification.is_read ? markUnread([notification.id]) : markRead([notification.id]); itemMenuOpen = null" class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-800" x-text="notification.is_read ? 'Mark as unread' : 'Mark as read'"></button>
                                                <button type="button" @click.prevent="deleteNotification(notification.id); itemMenuOpen = null" class="w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/50">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- "Earlier" Section Heading -->
                            <template x-if="hasRead(notifications)">
                                <div class="px-2 py-1 mt-3 flex items-center justify-between">
                                    <span class="text-[13px] font-semibold text-gray-900 dark:text-white">Earlier</span>
                                </div>
                            </template>

                            <!-- "Earlier" Notifications List -->
                            <div class="space-y-1">
                                <template x-for="notification in notifications.filter(n => n.is_read)" :key="notification.id">
                                    <div class="group flex items-center gap-3 p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors relative cursor-pointer opacity-75" @click="openNotification(notification)">
                                        <!-- Bulk Checkbox (only shown in bulkMode) -->
                                        <input x-show="bulkMode" @click.stop type="checkbox" class="h-4 w-4 rounded border-gray-300 text-amber-500 focus:ring-amber-500 shrink-0" :value="notification.id" @change="toggleSelection(notification.id)" :checked="selectedIds.includes(notification.id)">
                                        
                                        <!-- Text/Content -->
                                        <div class="flex-1 min-w-0 pr-6">
                                            <p class="text-[13px] leading-snug text-gray-900 dark:text-white break-words">
                                                <span class="font-bold text-gray-955 dark:text-gray-100" x-text="notification.title"></span>
                                                <span class="text-gray-700 dark:text-gray-300" x-text="notification.message"></span>
                                            </p>
                                            <p class="text-[12px] text-gray-400 dark:text-gray-500 mt-1" x-text="formatTimeAgo(notification.created_at)"></p>
                                        </div>

                                        <!-- Individual Options Trigger (hidden by default, shown on hover/click) -->
                                        <div class="absolute right-2 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity" @click.stop>
                                            <button type="button" @click.prevent="itemMenuOpen = itemMenuOpen === notification.id ? null : notification.id" class="rounded-full bg-white dark:bg-gray-900 p-1.5 text-gray-500 shadow-md hover:bg-gray-100 dark:hover:bg-gray-800 dark:text-gray-300 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                            </button>
                                            <div x-show="itemMenuOpen === notification.id" @click.outside="itemMenuOpen = null" x-cloak class="absolute right-0 mt-1.5 w-40 rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-800 dark:bg-gray-900 z-[10000]">
                                                <button type="button" @click.prevent="notification.is_read ? markUnread([notification.id]) : markRead([notification.id]); itemMenuOpen = null" class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-800" x-text="notification.is_read ? 'Mark as unread' : 'Mark as read'"></button>
                                                <button type="button" @click.prevent="deleteNotification(notification.id); itemMenuOpen = null" class="w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/50">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Unread Tab Layout -->
                    <template x-if="activeTab === 'unread' && unreadCount > 0">
                        <div class="space-y-1">
                            <template x-for="notification in notifications.filter(n => !n.is_read)" :key="notification.id">
                                <div class="group flex items-center gap-3 p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors relative cursor-pointer" @click="openNotification(notification)">
                                    <!-- Bulk Checkbox (only shown in bulkMode) -->
                                    <input x-show="bulkMode" @click.stop type="checkbox" class="h-4 w-4 rounded border-gray-300 text-amber-500 focus:ring-amber-500 shrink-0" :value="notification.id" @change="toggleSelection(notification.id)" :checked="selectedIds.includes(notification.id)">
                                    
                                    <!-- Text/Content -->
                                    <div class="flex-1 min-w-0 pr-6">
                                        <p class="text-[13px] leading-snug text-gray-900 dark:text-white break-words">
                                            <span class="font-bold text-gray-955 dark:text-gray-100" x-text="notification.title"></span>
                                            <span class="text-gray-700 dark:text-gray-300" x-text="notification.message"></span>
                                        </p>
                                        <p class="text-[12px] font-semibold text-blue-500 dark:text-blue-400 mt-1" x-text="formatTimeAgo(notification.created_at)"></p>
                                    </div>

                                    <!-- Unread Dot Indicator (Blue dot) -->
                                    <div class="h-3.5 w-3.5 rounded-full bg-blue-500 shrink-0 self-center"></div>

                                    <!-- Individual Options Trigger (hidden by default, shown on hover/click) -->
                                    <div class="absolute right-2 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity" @click.stop>
                                        <button type="button" @click.prevent="itemMenuOpen = itemMenuOpen === notification.id ? null : notification.id" class="rounded-full bg-white dark:bg-gray-900 p-1.5 text-gray-500 shadow-md hover:bg-gray-100 dark:hover:bg-gray-800 dark:text-gray-300 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </button>
                                        <div x-show="itemMenuOpen === notification.id" @click.outside="itemMenuOpen = null" x-cloak class="absolute right-0 mt-1.5 w-40 rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-800 dark:bg-gray-900 z-[10000]">
                                            <button type="button" @click.prevent="notification.is_read ? markUnread([notification.id]) : markRead([notification.id]); itemMenuOpen = null" class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-800" x-text="notification.is_read ? 'Mark as unread' : 'Mark as read'"></button>
                                            <button type="button" @click.prevent="deleteNotification(notification.id); itemMenuOpen = null" class="w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/50">Delete</button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Footer (FB Style) -->
            <div class="border-t border-gray-100 bg-gray-50 px-4 py-3.5 dark:border-gray-800 dark:bg-gray-900 text-center">
                <a href="{{ \App\Filament\Pages\AdminNotifications::getUrl() }}" class="text-sm font-bold text-[#1877f2] hover:underline">See all in settings</a>
            </div>
        </div>
    </div>

    @include('filament.admin.notification-scripts')
</div>
