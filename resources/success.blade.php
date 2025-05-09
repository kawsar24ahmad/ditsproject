<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('backend/plugins/fontawesome-free/css/all.min.css')}}">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('css')
</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
    <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
        @if (Route::has('login'))
            <nav class="flex items-center justify-end gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal">
                        Log in
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                            Register
                        </a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>

    <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
        <main class="w-full">

            <!-- Breadcrumb Section Start -->
            <section class="breadcrumb-section pt-0">
                <div class="container-fluid-lg">
                    <div class="row ">
                        <div class="col-12 flex justify-content-center">
                            <div class="breadcrumb-contain breadcrumb-order">
                                <div class="order-box d-flex flex-column align-items-center text-center">
                                    <div class="order-image mb-3">
                                        <div class="checkmark bg-success position-relative d-inline-block rounded-full p-3 text-white">
                                            <!-- Checkmark SVG -->
                                           <i class="fas fa-check"></i>
                                        </div>
                                    </div>
                                    <div class="order-contain">
                                        <h3 class="text-success">Order Success</h3>
                                        <h5 class="text-muted">Payment was successful and your order is on the way.</h5>
                                        <h6>Transaction ID: <span class="text-dark fw-semibold">{{ $tran_id}}</span></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Breadcrumb Section End -->

            <!-- Cart Section Start -->
            <section class="cart-section section-b-space mt-5">
                <div class="container-fluid-lg">
                    <div class="row g-sm-4 justify-center">
                        <div class="col-xxl-9 col-lg-8">
                            <div class="card shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="mb-0">Order Summary</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Customer Name
                                            <span>{{ $order_details->user->name }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Customer email
                                            <span>{{ $order_details->user->email }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Transaction ID
                                            <span>{{ $tran_id }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Product Name
                                            <span>{{ $order_details->service->title }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Price
                                            <span> {{ $currency }} {{  $order_details->amount }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                                            Total
                                            <span>{{ $currency }}{{ $amount }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="row mt-4">
                        <div class="col-12 d-flex justify-content-center">
                            <a href="{{ route('customer.dashboard') }}" class="btn btn-primary">Check Dashboard</a>
                        </div>
                    </div>

                </div>
            </section>
            <!-- Cart Section End -->

        </main>
    </div>

    @yield('scripts')

    <!-- JS dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
