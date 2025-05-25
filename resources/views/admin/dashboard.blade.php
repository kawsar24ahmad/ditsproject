@extends('admin.layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"> Hello {{ auth()->user()->name }}, 👋🏻 </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard v1</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <!-- <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                @php
                    $totalOrders = \App\Models\FacebookAd::where('status', 'pending')->count();
                @endphp
                <h3>{{ $totalOrders }}</h3>

                <p>New FacebookAd Orders</p>

              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="{{ route('admin.service.purchases') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div> -->
                <!-- <div class="col-lg-3 col-6">

            <div class="small-box bg-danger">
              <div class="inner">
                @php
                    $totalRechargeOrders = \App\Models\WalletTransaction::where([
                        'status'=> 'pending',
                        'type' => 'recharge'
                    ])->count();
                @endphp
                <h3>{{ $totalRechargeOrders }}</h3>

                <p>Recharge Orders</p>

              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="{{ route('admin.wallet.transactions') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div> -->
                <!-- ./col -->
                <!-- <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                @php
                    $total = \App\Models\ServicePurchase::where([
                        'status'=> 'approved'
                    ])->count();
                @endphp
                <h3>{{ $total }}</h3>

                <p>Total Purchased Service</p>

              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="{{ route('admin.service.purchases') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div> -->
                <!-- ./col -->
                <!-- <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                @php
                    $totalEarnings = \App\Models\WalletTransaction::where([
                    'type' => 'payment',
                    'status' => 'approved'
                    ])->sum('amount');
                @endphp
                <h3>
                    @if($totalEarnings > 0)
                        {{ number_format($totalEarnings, 2) }}tk
                    @else
                        0tk
                    @endif
                </h3>
                <p>Total Earnings</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div> -->
                <!-- ./col -->
                <!-- <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                @php
                    $totalEarnings = \App\Models\WalletTransaction::where([
                    'type' => 'recharge',
                    'status' => 'approved'
                    ])->sum('amount');
                @endphp
                <h3>
                    @if($totalEarnings > 0)
                        {{ number_format($totalEarnings, 2) }}tk
                    @else
                        0tk
                    @endif
                </h3>
                <p>Total Recharge</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div> -->

                <!-- ./col -->
                <!-- <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                @php
                    $total = \App\Models\FacebookPage::where('status', 'active')->count();
                @endphp
                <h3>{{ $total }}</h3>


                <p>Active Facebook Pages</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="{{ route('admin.facebook-pages.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
              <div class="inner">
                @php
                    $total = \App\Models\FacebookAd::where('status', 'approved')->count();
                @endphp
                <h3>{{ $total }}</h3>


                <p>Active Facebook Ads Requests</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="{{ route('admin.facebook-ad-requests') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div> -->
                <!-- <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
              <div class="inner">
                @php
                    $total = \App\Models\WalletTransaction::where([
                        'status'=> 'pending',
                        'type' => 'payment'
                    ])->sum('amount');
                @endphp
                <h3>{{ $total }}</h3>
                <p>Pending Payment</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="{{ route('admin.wallet.transactions') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div> -->
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            @php
                            $total = \App\Models\ServiceAssign::where('employee_id', '!=', null)->count();
                            @endphp
                            <h3>{{ $total }}</h3>
                            <p>Assigned Service</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('admin.service_assigns.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            @php
                            $total = \App\Models\ServiceAssign::where('employee_id', '=', null)->count();
                            @endphp
                            <h3>{{ $total }}</h3>
                            <p>Not Assigned Service</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('admin.service_assigns.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            @php
                            $total = \App\Models\Invoice::sum('total_amount');
                            @endphp
                            <h3>{{ $total }}</h3>
                            <p>Total Sell Amount</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('admin.service_assigns.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            @php
                            $total = \App\Models\Invoice::sum('paid_amount');
                            @endphp
                            <h3>{{ $total }}</h3>
                            <p>Total Paid Amount</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('admin.service_assigns.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            @php
                            $totalAmount = \App\Models\Invoice::sum('total_amount');
                            $paidAmount = \App\Models\Invoice::sum('paid_amount');
                            $totalDue = number_format($totalAmount - $paidAmount, 2);
                            @endphp
                            <h3>{{ $totalDue }}</h3>
                            <p>Total Due Amount</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('admin.service_assigns.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            @php

                            $result = \App\Models\ServiceAssign::where('status', 'completed')->count();

                            @endphp
                            <h3>{{ $result }}</h3>
                            <p>Completed Tasks</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('admin.service_assigns.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            @php

                            $result = \App\Models\ServiceAssign::where('status', 'pending')->count();

                            @endphp
                            <h3>{{ $result }}</h3>
                            <p>Pending Tasks</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('admin.service_assigns.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            @php

                            $result = \App\Models\ServiceAssign::where('status', 'in_progress')->count();

                            @endphp
                            <h3>{{ $result }}</h3>
                            <p>In Progress Tasks</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('admin.service_assigns.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            @php
                            $userCount = \App\Models\User::count();
                            @endphp
                            <h3>{{ $userCount }}</h3>

                            <p>User Registrations</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{ route('admin_users.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <!-- unique Visitors -->
                            @php
                            $uniqueVisitors = \App\Models\Visitor::distinct('ip_address')->count('ip_address');
                            @endphp
                            <h3>{{ $uniqueVisitors }}</h3>
                            <!-- unique Visitors -->


                            <p>Unique Visitors</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <div class="row">


            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

@stop
