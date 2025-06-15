<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-primary elevation-4">
    <!-- Brand Logo -->
    @php
        $settings = App\Models\SiteSetting::first();
    @endphp
    <div class="d-flex justify-content-center align-items-center">
        <a href="{{ URL::to('/') }}" class="brand-link d-flex justify-content-center align-items-center">
            <span class="h4 fw-bold text-blue">{{ strtoupper(config('app.name', 'Laravel')) }}</span>
        </a>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex justify-content-center flex-column align-items-center">
            <div class="flex flex-column align-items-center">
                <div class="image">
                    @if(auth()->user()->avatar != null)
                        @if (file_exists(auth()->user()->avatar))
                            <img src="{{ asset(auth()->user()->avatar) }}" class="img-circle elevation-2" alt="User Image">
                        @else
                            <img src="{{ auth()->user()->avatar }}" class="img-circle elevation-2" alt="User Image">
                        @endif
                    @else
                        <img src="{{ asset('default.png') }}" class="img-circle elevation-2" alt="User Image">
                    @endif
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ auth()->user()->name }}</a>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column pb-4" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i> <!-- Dashboard icon is good -->
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin_users.index') }}" class="nav-link {{ request()->routeIs('admin_users.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i> <!-- Users icon -->
                        <p>Users</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.employee.index') }}" class="nav-link {{ request()->routeIs('admin.employee.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-tie"></i> <!-- Employee icon: user-tie -->
                        <p>Employees</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.services.index') }}" class="nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tags"></i> <!-- Services icon plural tags -->
                        <p>Services</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.service_assigns.create') }}" class="nav-link {{ request()->routeIs('admin.service_assigns.create') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cart-plus"></i> <!-- Add service as Sell Service -->
                        <p>Sell Service</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.service_assigns.index') }}" class="nav-link {{ request()->routeIs('admin.service_assigns.index') || request()->routeIs('admin.assign_task.index')  || request()->routeIs('admin.service_assigns.edit') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-clipboard-list"></i> <!-- Sold services: clipboard-list -->
                        <p>Sold Services</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.payment_history.index') }}" class="nav-link {{ request()->routeIs('admin.payment_history.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-wallet"></i> <!-- Payments icon -->
                        <p>All Payments</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.media.index') }}" class="nav-link {{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
                        <i class="nav-icon fab fa-youtube"></i> <!-- Media YouTube icon -->
                        <p>Media</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.createRecordedClass') }}" class="nav-link {{ request()->routeIs('admin.createRecordedClass') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-plus-circle"></i> <!-- Add Recorded Class -->
                        <p>Add Recorded Class</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.recordedClass') }}" class="nav-link {{ request()->routeIs('admin.recordedClass') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-video"></i> <!-- Recorded class: video icon -->
                        <p>Recorded Class</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('live_class.index') }}" class="nav-link {{ request()->routeIs('live_class.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-broadcast-tower"></i> <!-- Live class: broadcast tower -->
                        <p>Live Class</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.site-settings.edit') }}" class="nav-link {{ request()->routeIs('admin.site-settings.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cogs"></i> <!-- Settings icon -->
                        <p>Settings</p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
