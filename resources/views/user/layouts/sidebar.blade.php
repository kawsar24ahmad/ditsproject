
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-primary elevation-4">
    <!-- Brand Logo -->
    @php
    $settings = App\Models\SiteSetting::first();
    @endphp
    <div class="d-flex justify-content-center align-items-center">
        <a href="{{ URL::to('/') }}" class="brand-link d-flex justify-content-center align-items-center">
            <!-- <img src="{{ asset('backend/dist/img/AdminLTELogo.png') }}" alt="VGD Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
            <span class="h4 fw-bold text-blue">{{ strtoupper(config('app.name', 'Laravel')) }}</span>
        </a>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
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
            <!-- <div class="info d-flex justify-content-center text-center">
                <a href="#" class="d-block">
                    Wallet Balance: ৳{{ auth()->user()->wallet_balance }}
                </a>
            </div> -->
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item has-treeview">
                    <a href="{{ route('user.dashboard') }}" class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
@php
    use App\Models\ServiceAssign;

    $services = ServiceAssign::with('invoice')->where('customer_id', auth()->id())->get();
    $status = true;

    if ($services->isEmpty()) {
        $status = false;
    } else {
        foreach($services as $service){
            if (($service->invoice && $service->invoice->status === 'unpaid') || $service->status == 'completed') {
                $status = false;
                break;
            }
        }
    }
@endphp


<li class="nav-item has-treeview">
    <a href="{{ $status ? route('user.live_class') : 'javascript:void(0)' }}"
       class="nav-link {{ request()->routeIs('user.live_class') ? 'active' : '' }}"
       style="{{ !$status ? 'pointer-events: none; cursor: not-allowed; opacity: 0.6; color: #6c757d;' : '' }}">
        <i class="nav-icon fas fa-broadcast-tower"></i>
        <p>Live Class</p>
    </a>
</li>

<li class="nav-item has-treeview">
    <a href="{{ $status ? route('user.recorded_class') : 'javascript:void(0)' }}"
       class="nav-link {{ request()->routeIs('user.recorded_class') ? 'active' : '' }}"
       style="{{ !$status ? 'pointer-events: none; cursor: not-allowed; opacity: 0.6; color: #6c757d;' : '' }}">
        <i class="nav-icon fas fa-play-circle"></i>
        <p>Recorded Class</p>
    </a>
</li>

<li class="nav-item has-treeview">
    <a href="{{ $status ? route('user.support') : 'javascript:void(0)' }}"
       class="nav-link {{ request()->routeIs('user.support') ? 'active' : '' }}"
       style="{{ !$status ? 'pointer-events: none; cursor: not-allowed; opacity: 0.6; color: #6c757d;' : '' }}">
        <i class="nav-icon fas fa-shield-alt"></i>
        <p>Copyright Media</p>
    </a>
</li>



          <li class="nav-item has-treeview">
    <a href="{{ $status ? "#" : 'javascript:void(0)' }}"
       class="nav-link {{ request()->routeIs('user.capcut_pro') ? 'active' : '' }}"
       style="{{ !$status ? 'pointer-events: none; cursor: not-allowed; opacity: 0.6; color: #6c757d;' : '' }}">
        <i class="nav-icon fab fa-creative-commons-nd"></i>
        <p>CapCut Pro</p>
    </a>
</li>



                <!-- Uncomment the following for Wallet and Transactions -->

                <!--
                <li class="nav-item">
                    <a href="{{ route('user.wallet.index') }}" class="nav-link {{ request()->routeIs('user.wallet.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-wallet"></i>
                        <p>My Wallet</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user.transactions.index') }}" class="nav-link {{ request()->routeIs('user.transactions.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-history"></i>
                        <p>Transactions</p>
                    </a>
                </li>
                -->

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
