
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a  href="{{URL::to('/')}}" class="brand-link d-flex justify-content-center align-items-center">
        <!-- <img src="{{asset('backend/dist/img/AdminLTELogo.png')}}" alt="VGD Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8"> -->
        <span class="h4 fw-bold text-white">{{ strtoupper(config('app.name', 'Laravel')) }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
       <!-- Sidebar user panel (optional) -->
       <div class="user-panel mt-3 pb-3 mb-3 d-flex justify-content-center flex-column align-items-center">

<div>
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
<div class="info d-flex justify-content-center  text-center">
    <a href="#" class="d-block">
        Wallet Balance: ৳{{ auth()->user()->wallet_balance }}
    </a>
</div>
</div>


<hr>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview">
            <a href="{{ route('customer.dashboard') }}" class="nav-link ">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>

            </ul>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
