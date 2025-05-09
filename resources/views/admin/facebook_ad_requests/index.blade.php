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
                                <li class="breadcrumb-item active"><a href="{{ route('admin.service.purchases') }}">Facebook ads</a></li>
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
                                        <h4 class="card-title text-bold text-lg mb-0">Facebook Ads Order List</h4>
                                    </div>
                                </div>
                            </div>

                            <div class="card-content">
                                <div class="card-body card-dashboard">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>User</th>
                                                    <th>Facebook Access Token</th>
                                                    <th>Page Link</th>
                                                    <th>Budget</th>
                                                    <th>Duration</th>
                                                    <th>Location</th>
                                                    <th>Age Range</th>
                                                    <th>Button</th>
                                                    <th>URL</th>
                                                    <th>Phone Number</th>
                                                    <th>Greeting Message</th>
                                                    <th>Status</th>
                                                    <th>Payment Amount</th>
                                                    <th>Purchase Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($facebookAdRequests as $key => $facebookAdRequest)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $facebookAdRequest->user->name ?? 'N/A' }}<br><small>{{ $facebookAdRequest->user->email ?? '' }}</small></td>
                                                    <td>{{ $facebookAdRequest->user->fb_access_token }}</td>
                                                    <td><a href="{{ $facebookAdRequest->page_link }}" target="_blank">{{ Str::limit($facebookAdRequest->page_link, 30) }}</a></td>
                                                    <td>${{ number_format($facebookAdRequest->budget, 2) }}</td>
                                                    <td>{{ $facebookAdRequest->duration }} days</td>
                                                    <td>{{ $facebookAdRequest->location }}</td>
                                                    <td>{{ $facebookAdRequest->min_age }} - {{ $facebookAdRequest->max_age }}</td>

                                                    <td>{{ ucfirst(str_replace('_', ' ', $facebookAdRequest->button)) }}</td>

                                                    <td>{{ $facebookAdRequest->url ?? "N/A" }}</td>
                                                    <td>{{ $facebookAdRequest->number ?? "N/A" }}</td>
                                                    <td>{{ $facebookAdRequest->greeting }}</td>
                                                    <td><span class="badge badge-{{ $facebookAdRequest->status == 'approved' ? 'success' : 'warning' }}">{{ ucfirst($facebookAdRequest->status) }}</span></td>
                                                    <td>{{ $facebookAdRequest->walletTransaction->amount ?? 'N/A' }}৳</td>
                                                    <td>{{ $facebookAdRequest->created_at->format('d M, Y') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="mt-2">
                                            {{ $facebookAdRequests->appends(request()->query())->links() }}
                                        </div>
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

@endsection
