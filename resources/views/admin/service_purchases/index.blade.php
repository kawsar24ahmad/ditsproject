@extends('admin.layouts.app')

@section('content')

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active"><a href="{{ route('admin.service.purchases') }}">Service purchase</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <section id="horizontal-vertical">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row w-100 align-items-center">
                                    <div class="col">
                                        <h4 class="card-title text-bold text-lg mb-0">Service purchase List</h4>
                                    </div>
                                </div>
                            </div>

                            <div class="card-content">
                                <div class="card-body card-dashboard">
                                    <div class="table-responsive">
                                        <table class="table zero-configuration">
                                            <thead>
                                                <tr>
                                                    <th>SL</th>
                                                    <th>User id</th>
                                                    <th>Service</th>
                                                    <th>Facebook Page</th>
                                                    <th> Price</th>
                                                    <th>Wallet Transaction id</th>
                                                    <th>Transaction id</th>
                                                    <th>Wallet Transaction Status</th>
                                                    <th>Status</th>
                                                    <th>Approved at</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($purchases as $service)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $service->user_id }}</td>
                                                    <td>{{ $service->service->title }}</td>

                                                    <td>{{ $service->walletTransaction?->facebookAd?->facebookPage?->page_name }}</td>

                                                    <td>{{ $service->price }}</td>
                                                    <td>{{ $service->walletTransaction->id }}</td>
                                                    <td>{{ $service->walletTransaction->transaction_id }}</td>
                                                    <td>
                                                        @if($service->walletTransaction->status == 'pending')
                                                        <span class="badge badge-warning">Pending</span>
                                                        @elseif($service->walletTransaction->status == 'approved')
                                                        <span class="badge badge-success">Approved</span>
                                                        @elseif($service->walletTransaction->status == 'rejected')
                                                        <span class="badge badge-danger">Rejected</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($service->status == 'pending')
                                                        <span class="badge badge-warning">Pending</span>
                                                        @elseif($service->status == 'approved')
                                                        <span class="badge badge-success">Approved</span>
                                                        @elseif($service->status == 'rejected')
                                                        <span class="badge badge-danger">Rejected</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $service->approved_at }}</td>
                                                    <td>
                                                       @if ($service->status == 'pending' )
                                                       <form action="{{ route('admin.service.purchase.approve', $service->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to approve this purchase?');">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                                        </form>
                                                       @endif


                                                        @if ($service->status == 'pending')
                                                        <form action="{{ route('admin.service.purchase.reject', $service->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to reject this purchase?');">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                                        </form>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        {{ $purchases->appends(request()->query())->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

@stop
