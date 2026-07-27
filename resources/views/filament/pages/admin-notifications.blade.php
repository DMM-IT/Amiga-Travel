@php use App\Filament\Pages\AdminNotifications; @endphp

<x-filament-panels::page>
    <div x-data="adminNotificationsPage()" x-init="init()" class="space-y-5">

        {{-- ─────────────────── Toast ─────────────────── --}}
        <div
            x-show="successMessage"
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2"
            class="fixed bottom-6 right-6 z-50 flex items-center gap-3 rounded-xl border border-primary-200 dark:border-primary-800 bg-primary-50 dark:bg-primary-950/60 px-5 py-3.5 shadow-lg"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-600 dark:text-primary-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium text-primary-800 dark:text-primary-200" x-text="successMessage"></span>
        </div>

        {{-- ─────────────────── Page Header ─────────────────── --}}
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-6 py-5 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">Notifications</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage your alerts and review recent admin activity.</p>
                </div>

                {{-- Count pills --}}
                <div class="flex flex-wrap items-center gap-2 text-sm">
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 px-3.5 py-1.5 font-medium text-gray-700 dark:text-gray-300">
                        <span x-text="totalCount"></span>
                        <span class="text-gray-400 dark:text-gray-500">total</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-primary-200 dark:border-primary-800/60 bg-primary-50 dark:bg-primary-950/40 px-3.5 py-1.5 font-semibold text-primary-700 dark:text-primary-300">
                        <span x-text="unreadCount"></span>
                        <span class="font-normal text-primary-500 dark:text-primary-400">unread</span>
                    </span>
                </div>
            </div>
        </div>

        {{-- ─────────────────── Main Card ─────────────────── --}}
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm overflow-hidden">

            {{-- ── Tabs + Toolbar ── --}}
            <div class="border-b border-gray-100 dark:border-gray-800">

                {{-- Tab bar --}}
                <div class="flex" style="display:flex; align-items:center; gap:0.75rem; border-bottom:1px solid rgba(226,232,240,0.7); padding-left:1.5rem; padding-right:1.5rem; padding-bottom:0.75rem;">
                    <button
                        type="button"
                        @click="switchTab('all')"
                        :style="activeTab === 'all'
                            ? 'background: rgba(59,130,246,0.12); color: #1d4ed8; border: 1px solid rgba(59,130,246,0.25);'
                            : 'background: transparent; color: #6b7280; border: 1px solid transparent;'"
                        style="display:inline-flex; align-items:center; justify-content:center; gap:0.375rem; border-radius:9999px; padding:0.5rem 0.75rem; font-size:0.875rem; transition:color 200ms ease, background-color 200ms ease;"
                    >All</button>
                    <button
                        type="button"
                        @click="switchTab('unread')"
                        :style="activeTab === 'unread'
                            ? 'background: rgba(59,130,246,0.12); color: #1d4ed8; border: 1px solid rgba(59,130,246,0.25);'
                            : 'background: transparent; color: #6b7280; border: 1px solid transparent;'"
                        style="display:inline-flex; align-items:center; justify-content:center; gap:0.5rem; border-radius:9999px; padding:0.5rem 0.75rem; font-size:0.875rem; transition:color 200ms ease, background-color 200ms ease;"
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

                {{-- Toolbar --}}
                <div class="flex flex-col gap-3 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                    {{-- Left: select + bulk actions --}}
                    <div class="flex flex-wrap items-center gap-3">
                        <label class="inline-flex items-center gap-2.5 cursor-pointer select-none text-sm font-medium text-gray-700 dark:text-gray-300">
                            <input
                                type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 text-primary-500 focus:ring-primary-500 focus:ring-offset-0 dark:border-gray-600 dark:bg-gray-800"
                                style="accent-color: #0ea5e9;"
                                @click="toggleSelectAll()"
                                :checked="allSelected"
                                :indeterminate="selectedCount > 0 && !allSelected"
                            >
                            Select all
                        </label>

                        <template x-if="selectedCount > 0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs font-semibold text-primary-600 dark:text-primary-400" x-text="selectedCount + ' selected'"></span>

                                <button type="button"
                                    @click.prevent="markRead()"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-primary-300 dark:border-primary-700 bg-primary-50 dark:bg-primary-950/40 px-3 py-1.5 text-xs font-semibold text-primary-700 dark:text-primary-300 hover:bg-primary-100 dark:hover:bg-primary-950/60 transition-colors"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Mark read
                                </button>

                                <button type="button"
                                    @click.prevent="markUnread()"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    Mark unread
                                </button>

                                <button type="button"
                                    @click.prevent="deleteSelected()"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-red-300 dark:border-red-800 bg-red-50 dark:bg-red-950/30 px-3 py-1.5 text-xs font-semibold text-red-700 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-950/50 transition-colors"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Delete
                                </button>
                            </div>
                        </template>
                    </div>

                    {{-- Right: Search --}}
                    <div class="flex items-center gap-2">
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input
                                type="search"
                                placeholder="Search notifications…"
                                x-model.debounce.400ms="search"
                                @input.debounce.400ms="searchNotifications()"
                                @keydown.enter.prevent="searchNotifications()"
                                class="w-full sm:w-64 pl-10 pr-4 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-primary-400 focus:outline-none focus:ring-2 focus:ring-primary-200 dark:focus:ring-primary-800 transition"
                            >
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Notification List ── --}}
            <div class="divide-y divide-gray-100 dark:divide-gray-800">

                {{-- Loading indicator --}}
                <div x-show="busy && notifications.length === 0" class="flex items-center justify-center py-16">
                    <svg class="h-6 w-6 animate-spin text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </div>

                {{-- Empty state --}}
                <template x-if="!busy && notifications.length === 0">
                    <div class="flex flex-col items-center justify-center py-20 px-6 text-center">
                        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341A6.002 6.002 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                        <p class="text-base font-semibold text-gray-800 dark:text-gray-200"
                           x-text="activeTab === 'unread' ? 'No unread notifications' : (search ? 'No results found' : 'No notifications yet')"></p>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400"
                           x-text="activeTab === 'unread' ? 'You\'re all caught up! Switch to All to see past notifications.' : (search ? 'Try a different search term.' : 'New notifications will appear here as activity occurs.')"></p>
                    </div>
                </template>

                {{-- Notification items --}}
                <template x-for="notification in notifications" :key="notification.id">
                    <div
                        class="group relative flex items-start gap-4 px-6 py-4 transition-all"
                        :class="!notification.is_read
                            ? 'border-l-[3px] border-primary-400 bg-primary-50/60 dark:bg-primary-950/20 hover:bg-primary-50 dark:hover:bg-primary-950/30'
                            : 'hover:bg-gray-50 dark:hover:bg-gray-800/40'"
                    >
                        {{-- Checkbox --}}
                        <div class="shrink-0 pt-0.5">
                            <input
                                type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 text-primary-500 focus:ring-primary-500 focus:ring-offset-0 dark:border-gray-600 dark:bg-gray-800"
                                style="accent-color: #0ea5e9;"
                                :value="notification.id"
                                @change="toggleSelection(notification.id)"
                                :checked="selectedIds.includes(notification.id)"
                            >
                        </div>

                        {{-- Main content --}}
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                                <div class="min-w-0 flex-1">
                                    {{-- Title + unread badge --}}
                                    <div class="flex flex-wrap items-center gap-2 mb-1">
                                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white leading-tight" x-text="notification.title"></h2>
                                        <span
                                            x-show="!notification.is_read"
                                            class="inline-flex items-center rounded-full bg-primary-100 dark:bg-primary-900/40 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-primary-700 dark:text-primary-300"
                                        >Unread</span>
                                    </div>
                                    {{-- Message --}}
                                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed line-clamp-2" x-text="notification.message"></p>
                                </div>

                                {{-- Timestamp --}}
                                <span
                                    class="shrink-0 text-xs font-medium"
                                    :class="!notification.is_read ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400 dark:text-gray-500'"
                                    x-text="formatTimeAgo(notification.created_at)"
                                ></span>
                            </div>

                            {{-- Action buttons --}}
                            <div class="mt-3 flex flex-wrap items-center gap-2">
                                <button type="button"
                                    @click.prevent="openNotification(notification)"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 px-3 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    Open
                                </button>
                                <button type="button"
                                    @click.prevent="notification.is_read ? markUnread([notification.id]) : markRead([notification.id])"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                                    x-text="notification.is_read ? 'Mark as unread' : 'Mark as read'"
                                ></button>
                                <button type="button"
                                    @click.prevent="deleteNotification(notification.id)"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 dark:border-red-800/60 bg-red-50 dark:bg-red-950/20 px-3 py-1.5 text-xs font-medium text-red-700 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-950/40 transition-colors"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            {{-- ── Pagination ── --}}
            <div class="flex flex-col gap-3 border-t border-gray-100 dark:border-gray-800 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    <template x-if="notifications.length === 0">
                        <span>No notifications to display.</span>
                    </template>
                    <template x-if="notifications.length > 0">
                        <span>Showing <span class="font-semibold text-gray-700 dark:text-gray-300" x-text="notifications.length"></span> on page <span class="font-semibold text-gray-700 dark:text-gray-300" x-text="page"></span> of <span class="font-semibold text-gray-700 dark:text-gray-300" x-text="lastPage"></span>.</span>
                    </template>
                </p>
                <div class="flex items-center gap-2">
                    <button type="button"
                        @click.prevent="changePage(page - 1)"
                        :disabled="page <= 1 || busy"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        Previous
                    </button>
                    <button type="button"
                        @click.prevent="changePage(page + 1)"
                        :disabled="page >= lastPage || busy"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                    >
                        Next
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- ─────────────────── Delete Confirmation Modal ─────────────────── --}}
        <div
            x-show="confirmingDelete"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4 py-6"
        >
            <div
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="w-full max-w-md rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 p-6 shadow-2xl"
            >
                <div class="flex items-start gap-4">
                    <div class="h-11 w-11 shrink-0 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Confirm deletion</h2>
                        <p class="mt-1.5 text-sm text-gray-600 dark:text-gray-400 leading-relaxed" x-text="deleteTitle"></p>
                    </div>
                </div>
                <div class="mt-5 flex gap-3">
                    <button type="button"
                        @click.prevent="confirmDelete()"
                        :disabled="busy"
                        class="flex-1 rounded-xl bg-red-600 hover:bg-red-700 disabled:opacity-70 px-4 py-2.5 text-sm font-semibold text-white transition-colors"
                    >Delete</button>
                    <button type="button"
                        @click.prevent="confirmingDelete = false; deleteTargetIds = []"
                        :disabled="busy"
                        class="flex-1 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 px-4 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-200 transition-colors"
                    >Cancel</button>
                </div>
            </div>
        </div>

    </div>

    @include('filament.admin.notification-scripts')
</x-filament-panels::page>
