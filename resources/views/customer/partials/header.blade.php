<header class="main-header">
    <!-- Logo -->
    <a href="javascript;;" class="logo">
        <!-- mini logo -->
        <div class="logo-mini">
            <span class="light-logo"><img src="{{ asset('assets/imgs/logo.jpg') }}" alt="logo"></span>
            <span class="dark-logo"><img src="{{ asset('assets/imgs/logo.jpg') }}" alt="logo"></span>
        </div>
        <!-- logo-->
        <div class="logo-lg">
            <span class="light-logo"><img src="{{ asset('assets/imgs/logo.jpg') }}" alt="logo"></span>
            <span class="dark-logo"><img src="{{ asset('assets/imgs/logo.jpg') }}" alt="logo"></span>
        </div>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <div>
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
        </div>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                <!-- Notifications -->
                <li class="dropdown notification-bell-wrap">
                    <a href="javascript:void(0);"
                       class="dropdown-toggle nav-link position-relative"
                       id="notificationBellToggle"
                       aria-expanded="false">
                        <i class="mdi mdi-bell" style="font-size: 20px;"></i>
                        <span id="notificationBadge"
                              class="badge badge-danger position-absolute"
                              style="top: 5px; right: 5px; font-size: 10px; display: none;">
                            0
                        </span>
                    </a>

                    <ul class="dropdown-menu animated flipInY notification-dropdown"
                        id="notificationDropdown"
                        style="width: 350px; padding: 0; right: 0; left: auto;">
                        <li class="p-2 border-bottom d-flex justify-content-between align-items-center">
                            <strong>Notifications</strong>
                            <button type="button" id="markAllNotificationsRead" class="btn btn-sm btn-light">
                                Mark all
                            </button>
                        </li>

                        <li id="notificationDropdownBody" style="max-height: 300px; overflow-y: auto; list-style: none;">
                            <div class="text-center p-3 text-muted">Loading...</div>
                        </li>

                        <li class="text-center border-top p-2">
                            <a href="{{ route('customer.messages.index') }}">View all</a>
                        </li>
                    </ul>
                </li>

                <!-- User Account-->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ asset('assets/imgs/user.png') }}" class="user-image rounded-circle"
                            alt="User Image">
                    </a>
                    <ul class="dropdown-menu animated flipInY">
                        <!-- User image -->
                        <li class="user-header bg-img"
                            style="background-image: url({{ asset('assets/imgs/user.png') }})" data-overlay="3">
                            <div class="flexbox align-self-center">
                                <img src="{{ asset('assets/imgs/user.png') }}" class="float-left rounded-circle"
                                    alt="User Image">
                                <h4 class="user-name align-self-center">
                                    <span>{{ auth()->user()->nickname }}</span>
                                    <small>{{ auth()->user()->email }}</small>
                                </h4>
                            </div>
                        </li>

                        <!-- Menu Body -->
                        <li class="user-body">
                            <a class="dropdown-item" href="{{ route('customer.profile') }}">
                                <i class="ion ion-person"></i> My Profile
                            </a>

                            <a class="dropdown-item" href="{{ route('vendor.balance') }}">
                                <i class="ion ion-bag"></i> My Balance
                            </a>

                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item" href="javascript:void(0)">
                                <i class="ion ion-settings"></i> Account Setting
                            </a>

                            <div class="dropdown-divider"></div>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>

                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="ion-log-out"></i> Logout
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </nav>
</header>

@push("scripts")
    <style>
.notification-bell-wrap .badge {
    border-radius: 50%;
    padding: 4px 6px;
    line-height: 1;
}

.notification-dropdown {
    min-width: 350px;
    max-width: 350px;
}

.notification-item {
    padding: 10px 15px;
    border-bottom: 1px solid #eee;
    display: block;
    color: #333;
    text-decoration: none;
    background: #fff;
}

.notification-item.unread {
    background: #eef5ff;
}

.notification-item:hover {
    background: #f5f5f5;
    color: #333;
    text-decoration: none;
}

.notification-item strong {
    display: block;
    margin-bottom: 3px;
    font-size: 13px;
}

.notification-item small {
    display: block;
    line-height: 1.4;
}

@media (max-width: 767.98px) {
    .notification-dropdown {
        min-width: 300px;
        max-width: 300px;
        right: 0 !important;
        left: auto !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const bell = document.getElementById('notificationBellToggle');
    const dropdown = document.getElementById('notificationDropdown');
    const body = document.getElementById('notificationDropdownBody');
    const badge = document.getElementById('notificationBadge');
    const markAllBtn = document.getElementById('markAllNotificationsRead');

    if (!bell || !dropdown || !body || !badge) {
        return;
    }

    let loaded = false;

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.innerText = text ?? '';
        return div.innerHTML;
    }

    function renderBadge(count) {
        badge.innerText = count;
        badge.style.display = count > 0 ? 'inline-block' : 'none';
    }

    function renderNotifications(messages) {
        if (!messages || !messages.length) {
            body.innerHTML = `<div class="text-center p-3 text-muted">No notifications</div>`;
            return;
        }

        body.innerHTML = messages.map(msg => `
            <a href="${msg.url}" class="notification-item ${msg.is_read ? '' : 'unread'}">
                <strong>${escapeHtml(msg.title)}</strong>
                <small>${escapeHtml(msg.message)}</small>
                <small class="text-muted">${escapeHtml(msg.created_at)}</small>
            </a>
        `).join('');
    }

    async function loadNotifications() {
        try {
            const res = await fetch("{{ route('customer.messages.dropdown') }}", {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await res.json();

            if (!data.status) {
                body.innerHTML = `<div class="text-center p-3 text-muted">No notifications</div>`;
                renderBadge(0);
                return;
            }

            renderBadge(data.unread_count || 0);
            renderNotifications(data.messages || []);
            loaded = true;
        } catch (e) {
            body.innerHTML = `<div class="text-center p-3 text-danger">Error loading notifications</div>`;
        }
    }

    bell.addEventListener('click', async function (e) {
        e.preventDefault();
        e.stopPropagation();

        const isOpen = dropdown.classList.contains('show');

        document.querySelectorAll('.notification-dropdown.show').forEach(el => {
            el.classList.remove('show');
        });

        if (!isOpen) {
            dropdown.classList.add('show');

            if (!loaded) {
                await loadNotifications();
            }
        }
    });

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.notification-bell-wrap')) {
            dropdown.classList.remove('show');
        }
    });

    if (markAllBtn) {
        markAllBtn.addEventListener('click', async function (e) {
            e.preventDefault();
            e.stopPropagation();

            try {
                const res = await fetch("{{ route('customer.messages.read_all') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await res.json();

                if (data.status) {
                    renderBadge(data.unread_count || 0);
                    await loadNotifications();
                }
            } catch (e) {}
        });
    }

    loadNotifications();
    setInterval(loadNotifications, 30000);
});
</script>
@endpush