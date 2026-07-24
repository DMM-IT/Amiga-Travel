<script>
window.adminNotificationBell = function (config) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    return {
        notifications: config.initialNotifications ?? [],
        totalCount: config.initialTotalCount ?? 0,
        unreadCount: config.initialUnreadCount ?? 0,
        selectedIds: [],
        dropdownOpen: false,
        actionMenuOpen: false,
        itemMenuOpen: null,
        confirmingDelete: false,
        deleteTargetIds: [],
        deleteTitle: '',
        successMessage: '',
        busy: false,

        init() {
            this.selectedIds = [];
        },

        get selectedCount() {
            return this.selectedIds.length;
        },

        get allSelected() {
            return this.notifications.length > 0
                && this.selectedCount === this.notifications.length;
        },

        toggleSelectAll() {
            if (this.allSelected) {
                this.selectedIds = [];
                return;
            }

            this.selectedIds = this.notifications.map((notification) => notification.id);
        },

        toggleSelection(notificationId) {
            if (this.selectedIds.includes(notificationId)) {
                this.selectedIds = this.selectedIds.filter((id) => id !== notificationId);
                return;
            }

            this.selectedIds = [...this.selectedIds, notificationId];
        },

        async fetchDropdown() {
            if (this.busy) {
                return;
            }

            this.busy = true;

            try {
                const response = await fetch('/admin/notifications/dropdown', {
                    headers: {
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin',
                });

                if (! response.ok) {
                    return;
                }

                const data = await response.json();
                this.notifications = data.notifications;
                this.totalCount = data.total;
                this.unreadCount = data.unread;
                this.selectedIds = [];
            } finally {
                this.busy = false;
            }
        },

        async sendAction(url, method, ids) {
            if (! ids.length) {
                return;
            }

            this.busy = true;

            try {
                const response = await fetch(url, {
                    method,
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ ids }),
                });

                if (! response.ok) {
                    return;
                }

                const data = await response.json();
                await this.fetchDropdown();

                if (data.unread !== undefined) {
                    this.unreadCount = data.unread;
                }

                if (data.total !== undefined) {
                    this.totalCount = data.total;
                }

                this.selectedIds = [];
                this.showSuccess(data.message || 'Action completed successfully.');
            } finally {
                this.busy = false;
                this.confirmingDelete = false;
                this.deleteTargetIds = [];
            }
        },

        async markRead(ids = null) {
            await this.sendAction('/admin/notifications/api/mark-read', 'POST', ids ?? this.selectedIds);
        },

        async markUnread(ids = null) {
            await this.sendAction('/admin/notifications/api/mark-unread', 'POST', ids ?? this.selectedIds);
        },

        async deleteSelected() {
            if (! this.selectedCount) {
                return;
            }
            this.deleteTitle = `Delete ${this.selectedCount} selected notification${this.selectedCount > 1 ? 's' : ''}?`;
            this.deleteTargetIds = [...this.selectedIds];
            this.confirmingDelete = true;
        },

        async deleteNotification(id) {
            this.deleteTitle = 'Delete this notification?';
            this.deleteTargetIds = [id];
            this.confirmingDelete = true;
        },

        async confirmDelete() {
            await this.sendAction('/admin/notifications/api', 'DELETE', this.deleteTargetIds);
        },

        toggleDropdown() {
            this.dropdownOpen = !this.dropdownOpen;
        },

        async openNotification(notification) {
            window.location.href = notification.url;
        },

        showSuccess(message) {
            this.successMessage = message;
            window.setTimeout(() => {
                this.successMessage = '';
            }, 3000);
        },
    };
};

window.adminNotificationsPage = function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    return {
        notifications: [],
        totalCount: 0,
        unreadCount: 0,
        perPage: 10,
        page: 1,
        lastPage: 1,
        search: '',
        selectedIds: [],
        actionMenuOpen: false,
        itemMenuOpen: null,
        confirmingDelete: false,
        deleteTargetIds: [],
        deleteTitle: '',
        successMessage: '',
        busy: false,

        init() {
            this.loadNotifications();
        },

        get selectedCount() {
            return this.selectedIds.length;
        },

        get allSelected() {
            return this.notifications.length > 0
                && this.selectedCount === this.notifications.length;
        },

        toggleSelectAll() {
            if (this.allSelected) {
                this.selectedIds = [];
                return;
            }

            this.selectedIds = this.notifications.map((notification) => notification.id);
        },

        toggleSelection(notificationId) {
            if (this.selectedIds.includes(notificationId)) {
                this.selectedIds = this.selectedIds.filter((id) => id !== notificationId);
                return;
            }

            this.selectedIds = [...this.selectedIds, notificationId];
        },

        async loadNotifications(page = this.page) {
            if (this.busy) {
                return;
            }

            this.busy = true;

            try {
                const params = new URLSearchParams();
                params.set('page', String(page));
                params.set('per_page', String(this.perPage));
                params.set('search', this.search);

                const response = await fetch(`/admin/notifications/api/list?${params.toString()}`, {
                    headers: {
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin',
                });

                if (! response.ok) {
                    return;
                }

                const data = await response.json();

                this.notifications = data.notifications;
                this.totalCount = data.total;
                this.unreadCount = data.unread;
                this.perPage = data.per_page;
                this.page = data.page;
                this.lastPage = data.last_page;
                this.selectedIds = [];
            } finally {
                this.busy = false;
            }
        },

        async sendAction(url, method, ids) {
            if (! ids.length) {
                return;
            }

            this.busy = true;

            try {
                const response = await fetch(url, {
                    method,
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ ids }),
                });

                if (! response.ok) {
                    return;
                }

                const data = await response.json();
                await this.loadNotifications(1);
                this.selectedIds = [];
                if (data.unread !== undefined) {
                    this.unreadCount = data.unread;
                }
                if (data.total !== undefined) {
                    this.totalCount = data.total;
                }
                this.showSuccess(data.message || 'Action completed successfully.');
            } finally {
                this.busy = false;
                this.confirmingDelete = false;
                this.deleteTargetIds = [];
            }
        },

        async markRead(ids = null) {
            await this.sendAction('/admin/notifications/api/mark-read', 'POST', ids ?? this.selectedIds);
        },

        async markUnread(ids = null) {
            await this.sendAction('/admin/notifications/api/mark-unread', 'POST', ids ?? this.selectedIds);
        },

        deleteSelected() {
            if (! this.selectedCount) {
                return;
            }
            this.deleteTitle = `Delete ${this.selectedCount} selected notification${this.selectedCount > 1 ? 's' : ''}?`;
            this.deleteTargetIds = [...this.selectedIds];
            this.confirmingDelete = true;
        },

        deleteNotification(id) {
            this.deleteTitle = 'Delete this notification?';
            this.deleteTargetIds = [id];
            this.confirmingDelete = true;
        },

        async confirmDelete() {
            await this.sendAction('/admin/notifications/api', 'DELETE', this.deleteTargetIds);
        },

        async changePage(page) {
            if (page < 1 || page > this.lastPage || page === this.page) {
                return;
            }
            this.page = page;
            await this.loadNotifications(page);
        },

        async searchNotifications() {
            this.page = 1;
            await this.loadNotifications(1);
        },

        openNotification(notification) {
            window.location.href = notification.url;
        },

        showSuccess(message) {
            this.successMessage = message;
            window.setTimeout(() => {
                this.successMessage = '';
            }, 3000);
        },
    };
};
</script>
