@extends('employee.layouts.app')

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
                                <li class="breadcrumb-item"><a href="{{ route('employee.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active">Service Assignments</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <section id="data-display">
                <div class="row">
                    <div class="col-12">

                    <div class="card mb-4">
                    <div class="card-body">
                        <form action="{{ route('employee.service_assigns.index') }}" method="GET" class="row g-3">
                            <div class="col-md-6">
                                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by invoice number">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary">Search</button>
                                @if(request('search'))
                                <a href="{{ route('admin.service_assigns.index') }}" class="btn btn-secondary">Clear</a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title text-bold text-lg mb-0">All Service Assignments</h4>
                            </div>

                            <div class="card-content">
                                <div class="card-body card-dashboard">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Invoice No</th>
                                                    <th>Service ID</th>
                                                    <th>Customer</th>
                                                    <th>Price</th>
                                                    <th>Paid</th>
                                                    <th>Due Payment</th>
                                                    <th>Status</th>
                                                    <th>Remarks</th>
                                                    <th>Created</th>
                                                    <th>Updated</th>
                                                    <th>Edit</th>
                                                    <th>Invoice</th>
                                                    <th>Print</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($serviceAssignments as $assignment)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $assignment->invoice->invoice_number }}</td>
                                                    <td>{{ $assignment->service_id }}</td>
                                                    <td>{{ $assignment->customer->name }}</td>
                                                    <td>{{ number_format($assignment->price, 2) }}</td>
                                                    <td>{{ number_format($assignment->paid_payment, 2) }}</td>
                                                    <td>{{ number_format( $assignment->price - $assignment->paid_payment , 2) }}</td>
                                                    <td><span class="badge badge-{{ $assignment->status == 'paid' ? 'success' : ($assignment->status == 'partial' ? 'warning' : 'secondary') }}">{{ ucfirst($assignment->status) }}</span></td>
                                                    <td>{{ $assignment->remarks ?? '—' }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($assignment->created_at)->format('d M, Y') }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($assignment->updated_at)->format('d M, Y') }}</td>
<td>
                                                        <a href="{{ route('employee.service_assigns.edit', $assignment->id) }}" class="btn btn-sm btn-info"><i class="fas fa-pen"></i></a>
                                                    </td>

                                                    <td>
                                                        <a href="{{ route('employee.service_assigns.invoiceGenerate', $assignment->id) }}" class="btn btn-sm btn-success"><i class="fas fa-eye"></i> </a>

                                                    </td>
                                                    <td>
                                                        <a href="{{ route('employee.service_assigns.invoiceGeneratePdf', $assignment->id) }}" class="btn btn-sm btn-danger"><i class="fas fa-print"></i></a>

                                                    </td>


                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                        <div class="mt-2">
                                            {{ $serviceAssignments->appends(request()->query())->links() }}
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
