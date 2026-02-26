<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('admin.dashboard') }}" class="brand-link bg-dark">
        <img src="{{asset('admin/img/morya-logo.png')}}" style="width: 50px">
        <span class="brand-text font-weight-light" style="color:#e1a10a">MORYA AUTO HUB</span>
    </a>

   <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" 
                    class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Car Master -->
                <li class="nav-item has-treeview 
                    {{ request()->is('car-makes*') || request()->is('car-models*') ? 'menu-open' : '' }}">

                    <a href="#" 
                    class="nav-link {{ request()->is('car-makes*') || request()->is('car-models*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-car"></i>
                        <p>Car Master <i class="right fas fa-angle-left"></i></p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('car-makes.index') }}"
                            class="nav-link {{ request()->is('car-makes*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i><p>Car Makes</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('car-models.index') }}"
                            class="nav-link {{ request()->is('car-models*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i><p>Car Models</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- App Users -->
                <li class="nav-item">
                    <a href="{{ route('app-users.index') }}"
                    class="nav-link {{ request()->is('app-users*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>App Users</p>
                    </a>
                </li>
                <!-- 🧑‍💼 Dealers -->
                <li class="nav-item has-treeview
                    {{ request()->is('admin/dealers*') ? 'menu-open' : '' }}">

                    <a href="#"
                    class="nav-link {{ request()->is('admin/dealers*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-tie"></i>
                        <p>
                            Dealers
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        <!-- Dealer List -->
                        <li class="nav-item">
                            <a href="{{ route('admin.dealers.index') }}"
                            class="nav-link {{ request()->routeIs('admin.dealers.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dealer List</p>
                            </a>
                        </li>

                        <!-- Dealer KYC -->
                        <li class="nav-item">
                            <a href="{{ route('admin.dealers.kyc') }}"
                            class="nav-link {{ request()->routeIs('admin.dealers.kyc') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>KYC Verification</p>
                            </a>
                        </li>

                    </ul>
                </li>



                <!-- Car Listings -->
                <li class="nav-item">
                    <a href="{{ route('admin.car-listings.index') }}"
                    class="nav-link {{ request()->is('car-listings*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-car"></i>
                        <p>Car Listings</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.car-listings.sold') }}" class="nav-link">
                        <i class="fas fa-tag text-success"></i>
                        Sold Cars
                    </a>
                </li>

                <!-- Subscription Plans -->
                <li class="nav-item">
                    <a href="{{ route('admin.subscriptions_plans.index') }}"
                    class="nav-link {{ request()->routeIs('admin.subscriptions_plans.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>Subscription Plans</p>
                    </a>
                </li>

                <!-- Bookings -->
                <li class="nav-item">
                    <a href="{{ route('bookings.index') }}"
                    class="nav-link {{ request()->is('admin/bookings*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-check"></i>
                        <p>Bookings</p>
                    </a>
                </li>

                <!-- 🔨 Auctions Module -->
                <li class="nav-item has-treeview
                    {{ request()->is('admin/auctions*') || request()->is('admin/auction-deposits*') ? 'menu-open' : '' }}">

                    <a href="#" class="nav-link 
                    {{ request()->is('admin/auctions*') || request()->is('admin/auction-deposits*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-gavel"></i>
                        <p>Auctions <i class="right fas fa-angle-left"></i></p>
                    </a>

                    <ul class="nav nav-treeview">

                        <!-- Requested -->
                        <li class="nav-item">
                            <a href="{{ route('auctions.requested') }}"
                            class="nav-link {{ request()->routeIs('auctions.requested') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i><p>Requested Auctions</p>
                            </a>
                        </li>

                        <!-- Scheduled -->
                        <li class="nav-item">
                            <a href="{{ route('auctions.scheduled') }}"
                            class="nav-link {{ request()->routeIs('auctions.scheduled') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i><p>Scheduled Auctions</p>
                            </a>
                        </li>

                        <!-- Results / History -->
                        <li class="nav-item">
                            <a href="{{ route('auctions.history') }}"
                            class="nav-link {{ request()->routeIs('auctions.history') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i><p>Auction History</p>
                            </a>
                        </li>

                        <!-- 💰 Deposit Verifications -->
                        <li class="nav-item">
                            <a href="{{ route('deposits.index') }}"
                            class="nav-link {{ request()->is('auction-deposits*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i><p>Deposit Approvals</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <!-- 🛠 Services -->
                <li class="nav-item has-treeview
                    {{ request()->is('services*') || request()->is('service-requests*') ? 'menu-open' : '' }}">

                    <a href="#"
                    class="nav-link
                    {{ request()->is('services*') || request()->is('service-requests*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tools"></i>
                        <p>
                            Services
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        <!-- Services Master -->
                        <li class="nav-item">
                            <a href="{{ route('services.index') }}"
                            class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Service Master</p>
                            </a>
                        </li>

                        <!-- Service Requests -->
                        <li class="nav-item">
                            <a href="{{ route('service-requests.index') }}"
                            class="nav-link {{ request()->routeIs('service-requests.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Service Requests</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <!-- 🧩 Accessories -->
                <li class="nav-item has-treeview
                    {{ request()->is('accessories*') || request()->is('accessory-categories*') ? 'menu-open' : '' }}">

                    <a href="#"
                    class="nav-link
                    {{ request()->is('accessories*') || request()->is('accessory-categories*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-box-open"></i>
                        <p>
                            Accessories
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        <!-- Categories -->
                        <li class="nav-item">
                            <a href="{{ route('accessory-categories.index') }}"
                            class="nav-link {{ request()->routeIs('accessory-categories.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Categories</p>
                            </a>
                        </li>

                        <!-- Accessories -->
                        <li class="nav-item">
                            <a href="{{ route('accessories.index') }}"
                            class="nav-link {{ request()->routeIs('accessories.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Accessories</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('accessories.bookings') }}"
                            class="nav-link {{ request()->routeIs('accessories.bookings') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Accessory Bookings</p>
                            </a>
                        </li>

                    </ul>
                </li>

                {{-- Offers --}}
                <li class="nav-item">
                    <a href="{{ route('offers.index') }}"
                    class="nav-link {{ request()->routeIs('offers.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bullhorn"></i>
                        <p>Offers</p>
                    </a>
                </li>


            </ul>
        </nav>
   </div>



</aside>
