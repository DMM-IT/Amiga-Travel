@php use App\Filament\Pages\AdminNotifications; @endphp

<x-filament-panels::page>
    <div x-data="adminNotificationsPage()" x-init="init()" class="space-y-6">
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Notifications</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage your unread alerts and review recent admin activity.</p>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
                        <span class="font-semibold" x-text="totalCount"></span> total
                        · <span class="font-semibold" x-text="unreadCount"></span> unread
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" @click.prevent="toggleSelectAll()" class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800">Select all</button>
                        <button type="button" @click.prevent="markRead()" :disabled="selectedCount === 0" class="rounded-xl border border-amber-500 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-700 disabled:opacity-50 dark:border-amber-500/50 dark:bg-amber-900/10 dark:text-amber-200">Mark as read</button>
                        <button type="button" @click.prevent="markUnread()" :disabled="selectedCount === 0" class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 disabled:opacity-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800">Mark as unread</button>
                        <button type="button" @click.prevent="deleteSelected()" :disabled="selectedCount === 0" class="rounded-xl border border-red-500 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 disabled:opacity-50 dark:border-red-500/50 dark:bg-red-900/10 dark:text-red-200">Delete selected</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <div class="flex flex-col gap-4 border-b border-gray-100 px-6 py-5 dark:border-gray-800 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                        <input type="checkbox" class="h-4 w-4 rounded border-gray-300 text-amber-500 focus:ring-amber-500" @click="toggleSelectAll()" :checked="allSelected">
                        Select all visible
                    </label>
                    <span x-show="selectedCount > 0" class="text-sm font-medium text-gray-700 dark:text-gray-300" x-text="selectedCount + ' selected'"></span>
                </div>
                <div class="flex items-center gap-3">
                    <input type="search" placeholder="Search notifications" x-model.debounce.300ms="search" @keydown.enter.prevent="searchNotifications()" class="w-full min-w-[200px] rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100" />
                    <button type="button" @click.prevent="searchNotifications()" class="rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-600">Search</button>
                </div>
            </div>

            <div class="space-y-2 p-6">
                <template x-if="notifications.length === 0">
                    <div class="rounded-2xl border border-dashed border-gray-200 bg-gray-50 px-6 py-12 text-center text-sm text-gray-500 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-400">
                        <p class="font-semibold text-gray-900 dark:text-white">No notifications yet.</p>
                        <p class="mt-2">Your notifications will appear here as new activity occurs.</p>
                    </div>
                </template>

                <template x-for="notification in notifications" :key="notification.id">
                    <div class="rounded-3xl border border-gray-200 bg-white p-4 shadow-sm transition hover:border-amber-300 hover:shadow-md dark:border-gray-800 dark:bg-gray-950">
                        <div class="flex items-start gap-4">
                            <input type="checkbox" class="mt-2 h-5 w-5 rounded border-gray-300 text-amber-500 focus:ring-amber-500" :value="notification.id" @change="toggleSelection(notification.id)" :checked="selectedIds.includes(notification.id)">
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <h2 class="text-base font-semibold text-gray-900 dark:text-white" x-text="notification.title"></h2>
                                            <span x-show="!notification.is_read" class="inline-flex rounded-full bg-amber-500 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-white">Unread</span>
                                        </div>
                                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400" x-text="notification.message"></p>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400" x-text="notification.created_at"></div>
                                </div>
                                <div class="mt-4 flex flex-wrap items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                    <button type="button" @click.prevent="openNotification(notification)" class="rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-900 dark:hover:bg-gray-800">Open</button>
                                    <button type="button" @click.prevent="notification.is_read ? markUnread([notification.id]) : markRead([notification.id])" class="rounded-xl border border-gray-200 bg-white px-3 py-2 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:hover:bg-gray-800" x-text="notification.is_read ? 'Mark as unread' : 'Mark as read'"></button>
                                    <button type="button" @click.prevent="deleteNotification(notification.id)" class="rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-red-700 hover:bg-red-100 dark:border-red-500/20 dark:bg-red-900/10 dark:text-red-200">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="flex flex-col gap-3 border-t border-gray-100 px-6 py-4 text-sm text-gray-500 dark:border-gray-800 dark:text-gray-400 sm:flex-row sm:items-center sm:justify-between">
                <div x-text="notifications.length === 0 ? 'No notifications to display.' : ( 'Showing ' + notifications.length + ' notifications on this page.' )"></div>
                <div class="flex items-center gap-2">
                    <button type="button" @click.prevent="changePage(page - 1)" :disabled="page <= 1" class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-semibold disabled:opacity-50 dark:border-gray-700 dark:bg-gray-900">Previous</button>
                    <span>Page <span x-text="page"></span> of <span x-text="lastPage"></span></span>
                    <button type="button" @click.prevent="changePage(page + 1)" :disabled="page >= lastPage" class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-semibold disabled:opacity-50 dark:border-gray-700 dark:bg-gray-900">Next</button>
                </div>
            </div>
        </div>

        <div x-show="confirmingDelete" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4 py-6">
            <div class="w-full max-w-lg rounded-3xl bg-white p-6 shadow-2xl dark:bg-gray-950">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Confirm deletion</h2>
                <p class="mt-3 text-sm leading-6 text-gray-600 dark:text-gray-300" x-text="deleteTitle"></p>
                <div class="mt-6 flex gap-3">
                    <button type="button" @click.prevent="confirmDelete()" class="inline-flex items-center justify-center rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Delete</button>
                    <button type="button" @click.prevent="confirmingDelete = false; deleteTargetIds = []" class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    @include('filament.admin.notification-scripts')
</x-filament-panels::page>
