<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
     @php
     $settings = App\Models\SiteSetting::first();
     @endphp
    <a href="{{URL::to('/')}}" class="brand-link">
        @if (isset($settings->favicon) && $settings->favicon != null)
            @if (file_exists($settings->favicon))
                <img src="{{asset($settings->favicon)}}" alt="VGD Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
            @endif
        @endif
        <span class="brand-text text-white  font-bold">{{ strtoupper($settings->site_name ?? "DITS") }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @if (auth()->user()->avatar != null)
                    @if (file_exists(auth()->user()->avatar))
                    <img  src="{{ asset(auth()->user()->avatar) }}" class="img-circle elevation-2" alt="User Image">
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

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column pb-4" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item has-treeview">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link ">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('admin_users.index') }}" class="nav-link ">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Users
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('admin_categories.index') }}" class="nav-link ">
                        <i class="nav-icon fas fa-pen"></i>
                        <p>
                            Categories
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('admin.services.index') }}" class="nav-link ">
                        <i class="nav-icon fas fa-tag"></i>
                        <p>
                            Services
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('admin.service_assigns.create') }}" class="nav-link ">
                        <i class="nav-icon fas fa-plus"></i>
                        <p>
                            Sell Service
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('admin.service_assigns.index') }}" class="nav-link ">
                        <i class="nav-icon fas fa-plus"></i>
                        <p>
                            Assigned Services
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.wallet.transactions') }}" class="nav-link">
                        <i class="nav-icon fas fa-wallet"></i>
                        <p>Transactions</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.service.purchases') }}" class="nav-link">
                        <i class="nav-icon fas fa-wallet"></i>
                        <p>Service Purchases</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.facebook-ad-requests') }}" class="nav-link">
                        <i class="nav-icon fas fa-wallet"></i>
                        <p>Facebook Ads Orders</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.facebook-pages.index') }}" class="nav-link">
                        <i class="nav-icon fab fa-facebook-f"></i>
                        <p>Facebook Pages</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.site-settings.edit') }}" class="nav-link">
                        <i class="nav-icon fas fa-tools"></i>
                        <p>Settings</p>
                    </a>
                </li>


            </ul>

        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
