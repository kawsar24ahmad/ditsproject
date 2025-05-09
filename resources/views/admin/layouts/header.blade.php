  <!-- Navbar -->
  @include('admin.layouts.sidebar')
  <!-- /.navbar -->

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
          <li class="nav-item">
              <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
          </li>

      </ul>

      <!-- SEARCH FORM -->
      <form class="form-inline ml-3">
          <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                  <button class="btn btn-navbar" type="submit">
                      <i class="fas fa-search"></i>
                  </button>
              </div>
          </div>
      </form>
      <!-- Right navbar links -->
      <ul class="navbar-nav ms-auto align-items-center">
         
         

          <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                @if (auth()->user()->avatar != null)
                    <img src="{{  asset(auth()->user()->avatar) }}" alt="User" class="rounded-circle" style="height: 32px; width: 32px; object-fit: cover;">
                @else
                    <img src="{{ asset('default.png') }}" alt="User" class="rounded-circle" style="height: 32px; width: 32px; object-fit: cover;">
                @endif

              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                  <li class="dropdown-item-text">
                      <strong>{{ auth()->user()->name }}</strong><br>
                      {{ auth()->user()->email }}
                  </li>
                  <li>
                      <hr class="dropdown-divider">
                  </li>
                  <li><a class="dropdown-item" href="{{ route('admin.profile.edit') }}">Account Settings</a></li>
                  <li>
                      <form method="POST" action="{{ route('logout') }}">
                          @csrf
                          <a onclick="event.preventDefault();
                            this.closest('form').submit();" class="dropdown-item text-danger" href="{{ route('logout') }}">Logout</a>
                      </form>
                  </li>
              </ul>
          </li>
      </ul>
      </li>

      </ul>
  </nav>
