@extends('user.layouts.app')

@section("css")
<style>
        body {
            font-family: "Helvetica Neue", sans-serif;
            background-color: #f9f9f9;
        }
        .service-card {
            transition: all 0.3s ease;
        }
        .service-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .rounded-icon {
            font-size: 32px;
        }
    </style>

@endsection

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <div class="d-flex justify-content-between">
                        <h1 class="m-0 text-dark">User Dashboard</h1>

                    </div>


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
            <!-- Welcome -->
            <div class="text-center mb-4">
                <h4>স্বাগতম, {{ auth()->user()->name }}</h4>
                <!-- <button class="btn btn-primary mt-2">একটি সার্ভিস বুকিং করুন</button> -->
            </div>

            @php
            $services = App\Models\ServiceAssign::with(['invoice:id,invoice_number,service_assign_id', 'service:id,title'])->where('customer_id', auth()->user()->id)->orderByDesc('id')->paginate(2);
            @endphp

            <!-- Services -->
            <!-- <h5 class="mb-3">আমাদের সার্ভিসসমূহ</h5>
            <div class="row row-cols-2 row-cols-md-4 g-3 mb-4">
                <div class="col">
                    <a href="https://digitalwaveit.com/e-commerce/">
                        <div class="card text-center service-card p-3">
                            <div class="rounded-icon text-primary mb-2">🖥️</div>
                            <div>ওয়েব ডিজাইন</div>
                        </div>
                    </a>
                </div>
                @if (!empty($services))
                    @foreach ($services as $service)
                        <div class="col">
                            <a href="{{ route('user.services.show', $service->id) }}">
                            <div class="card text-center service-card p-3">
                                <div class="rounded-icon text-primary mb-2">{{ $service->icon }}</div>
                                <div>{{ $service->title }}</div>
                            </div>
                            </a>
                        </div>
                    @endforeach
                @endif -->
                <!-- <div class="col">
                    <div class="card text-center service-card p-3">
                        <div class="rounded-icon text-primary mb-2">🎬</div>
                        <div>ভিডিও মার্কেটিং</div>
                    </div>
                </div> -->
                <!-- <div class="col">
                    <div class="card text-center service-card p-3">
                        <div class="rounded-icon text-primary mb-2">📢</div>
                        <div>ফেসবুক অ্যাডস</div>
                    </div>
                </div>
                <div class="col">
                    <div class="card text-center service-card p-3">
                        <div class="rounded-icon text-primary mb-2">🖥️</div>
                        <div>ওয়েব ডিজাইন</div>
                    </div>
                </div>
                <div class="col">
                    <div class="card text-center service-card p-3">
                        <div class="rounded-icon text-primary mb-2">⚙️</div>
                        <div>অন্যান্য</div>
                    </div>
                </div> -->
            </div>
            <!-- Main row -->
            <div class="content-body">
            <section id="data-display">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title text-bold text-lg mb-0">Your Services</h4>
                            </div>

                            <div class="card-content">
                                <div class="card-body card-dashboard">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Invoice No</th>
                                                    <th>Service </th>
                                                    <th>Customer </th>
                                                    <th>Price</th>
                                                    <th>Paid</th>
                                                    <th>Due Payment</th>
                                                    <th>Status</th>
                                                    <th>Remarks</th>
                                                    <th>Created</th>
                                                    <th>Updated</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($services as $assignment)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $assignment->invoice->invoice_number }}</td>
                                                    <td>{{ $assignment->service->title }}</td>
                                                    <td>{{ $assignment->employee?->name }}</td>
                                                    <td>{{ number_format($assignment->price, 2) }}</td>
                                                    <td>{{ number_format($assignment->paid_payment, 2) }}</td>
                                                    <td>{{ number_format( $assignment->price - $assignment->paid_payment , 2) }}</td>
                                                    <td><span class="badge badge-{{ $assignment->status == 'paid' ? 'success' : ($assignment->status == 'partial' ? 'warning' : 'secondary') }}">{{ ucfirst($assignment->status) }}</span></td>
                                                    <td>{{ $assignment->remarks ?? '—' }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($assignment->created_at)->format('d M, Y') }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($assignment->updated_at)->format('d M, Y') }}</td>
                                                    <td>
                                                        <a  href="{{ route('user.service_assigns.show', $assignment->id) }}" class="badge badge-success" style="font-size: 15px;"><i class="fas fa-eye"></i> View</a>

                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                        <div class="mt-2">
                                            {{ $services->appends(request()->query())->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

@stop
