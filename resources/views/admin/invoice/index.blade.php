@extends('admin.layouts.app')

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="col-md-12 mb-3">
                <h2 class="fw-bold">Service Assignments</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Service Assignments</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="content-body m-4">
            <section>
                <div class="card mb-4">
                    <div class="card-body">
                        <form action="{{ route('admin.service_assigns.index') }}" method="GET" class="row g-3">
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

                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-2 g-4">
                    @forelse ($serviceAssignments as $assignment)
                    <div class="col">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                @php
                                $totalTasks = $assignment->assignedTasks->count();
                                $completedTasks = $assignment->assignedTasks->where('is_completed', 1)->count();
                                $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                                @endphp

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0">Invoice: {{ $assignment->invoice->invoice_number ?? "" }}</h5>
                                        <span class="badge
                                            {{ $assignment->status === 'paid' ? 'bg-success' :
                                            ($assignment->status === 'partial' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                            {{ ucfirst($assignment->status) }}
                                        </span>
                                    </div>
                                    <div class="progress mt-2" style="height: 18px;">
                                        <div class="progress-bar
                                            @if($percentage == 100)
                                                bg-success
                                            @elseif($percentage >= 50)
                                                bg-info
                                            @elseif($percentage >= 20)
                                                bg-warning
                                            @else
                                                bg-danger
                                            @endif"
                                            role="progressbar" style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                            {{ $percentage }}%
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $completedTasks }} of {{ $totalTasks }} tasks completed</small>
                                </div>

                                <ul class="list-unstyled mb-3">
                                    <li><strong>Service:</strong> {{ $assignment->service->title }}</li>
                                    <li><strong>Customer:</strong> {{ $assignment->customer->name }}</li>
                                    <li><strong>Starting Followers:</strong> {{ $assignment->customer->starting_followers ?? '-'}}</li>
                                    <li><strong>Employee:</strong>
                                        @if($assignment->employee)
                                            {{ $assignment->employee->name }}
                                            <span class="text-muted">({{ $assignment->employee->title ?? 'No title' }})</span>
                                        @else
                                            —
                                        @endif
                                    </li>
                                    <li><strong>Price:</strong> ৳{{ number_format($assignment->price, 2) }}</li>
                                    <li><strong>Paid:</strong> <span class="text-success">৳{{ number_format($assignment->paid_payment, 2) }}</span></li>
                                    <li><strong>Due:</strong> <span class="text-danger">৳{{ number_format($assignment->price - $assignment->paid_payment, 2) }}</span></li>
                                    <li><strong>Delivery Date:</strong> {{ $assignment->delivery_date ? \Carbon\Carbon::parse($assignment->delivery_date)->format('F j, Y') : '-' }}</li>
                                    <li><strong>Created:</strong> {{ \Carbon\Carbon::parse($assignment->created_at)->format('d M, Y') }}</li>
                                    <li><strong>Updated:</strong> {{ \Carbon\Carbon::parse($assignment->updated_at)->format('d M, Y') }}</li>
                                </ul>

                                <div class="mb-3">
                                    <strong>Remarks:</strong><br>
                                    @if (!empty(strip_tags($assignment->remarks)))
                                        <div class="text-muted small">{!! \Illuminate\Support\Str::words(strip_tags($assignment->remarks), 20, '...') !!}</div>
                                    @else
                                        <div class="text-muted">—</div>
                                    @endif
                                </div>

                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('customerServiceCalendarIndex', $assignment->id) }}" class="btn btn-outline-success btn-sm">Service Calender</a>
                                    <a href="{{ route('admin.assign_task.index', $assignment->id) }}" class="btn btn-outline-success btn-sm">Assigned Tasks</a>
                                    <a href="{{ route('admin.service_assigns.edit', $assignment->id) }}" class="btn btn-outline-primary btn-sm">Edit</a>
                                    <a href="{{ route('admin.service_assigns.invoiceGenerate', $assignment->id) }}" class="btn btn-outline-info btn-sm">See Invoice</a>
                                    <a href="{{ route('admin.service_assigns.invoiceGeneratePdf', $assignment->id) }}" class="btn btn-outline-dark btn-sm">Print</a>
                                    <form action="{{ route('admin.service_assigns.destroy', $assignment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this assignment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center text-muted fs-5 py-5">
                        No service assignments found for this invoice number.
                    </div>
                    @endforelse

                    <div class="mt-6">
                {{ $serviceAssignments->appends(request()->query())->links() }}
            </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection

@section('script')
@endsection
