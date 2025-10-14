<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <span class="brand-text font-weight-light">Morya Cars Admin</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('app-users.index') }}" class="nav-link {{ request()->is('app-users*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            App Users
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.car-listings.index') }}" class="nav-link {{ request()->is('car-listings*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-car"></i>
                        <p>
                            Car Listings
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.subscriptions.plans.index') }}" class="nav-link {{ request()->routeIs('admin.subscriptions.plans.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-list-alt"></i>
                        <p>Subscription Plans</p>
                    </a>
                </li>


                <!-- Add more menu items here -->
            </ul>
        </nav>
    </div>
</aside>
