<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-primary elevation-4">
    @php
        $settings = App\Models\SiteSetting::first();
    @endphp

    <!-- Brand Logo -->
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
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <!-- Dashboard Link -->
                <li class="nav-item has-treeview">
                    <a href="{{ route('employee.dashboard') }}" class="nav-link {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Sell Service Link -->
                <li class="nav-item">
                    <a href="{{ route('employee.service_assigns.create') }}" class="nav-link {{ request()->routeIs('employee.service_assigns.create') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-plus"></i>
                        <p>Sell Service</p>
                    </a>
                </li>

                <!-- Assigned Services Link (optional) -->
                <!-- <li class="nav-item has-treeview">
                    <a href="{{ route('employee.service_assigns.index') }}" class="nav-link {{ request()->routeIs('employee.service_assigns.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tools"></i>
                        <p>Assigned Services</p>
                    </a>
                </li> -->

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
