@extends('employee.layouts.app')

@section('content')

@php
$serviceAssignments = App\Models\ServiceAssign::with(['customer:id,name', 'invoice:id,invoice_number,service_assign_id'])
->where('employee_id',auth()->user()->id)
->orderByDesc('id')
->paginate(10);
@endphp


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


        <!-- Summary Boxes -->
        <div class="row mb-5 g-3 m-2">
            <div class="col-md-4">
                <div class="card text-white bg-primary shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Services</h5>
                        <h2 class="fw-bold">{{ $serviceAssignments->count() }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Completed Services</h5>
                        <h2 class="fw-bold">{{ $serviceAssignments->where('status', 'completed')->count()}}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-danger shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">In Progress Services</h5>
                        <h2 class="fw-bold">{{ $serviceAssignments->where('status', 'in_progress')->count() }}</h2>
                    </div>
                </div>
            </div>


        </div>


        <div class="content-body">
            <section id="data-display">
                <div class="row">
                    <div class="col-12">
                        <div class="px-6 py-2 border-b">
                            <h2 class="text-xl font-semibold text-gray-800">Assigned Services</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 p-4">
                            @foreach ($serviceAssignments as $assignment)
                            <div class="bg-white shadow rounded-2xl overflow-hidden border border-gray-100">
                                <div class="p-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <h3 class="text-lg font-semibold text-dark">
                                            Invoice: {{ $assignment->invoice->invoice_number }}
                                        </h3>
                                        <span class="text-xs px-2 py-1 rounded-full text-white
                                            {{ $assignment->status === 'paid' ? 'bg-success' : ($assignment->status === 'partial' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                            {{ ucfirst($assignment->status) }}
                                        </span>
                                    </div>

                                    <div class="text-sm text-gray-700 space-y-1">
                                        <p><strong>Service ID:</strong> {{ $assignment->service_id }}</p>
                                        <p><strong>Customer:</strong> {{ $assignment->customer->name }}</p>
                                        <p><strong>Price:</strong> <span class="text-dark">{{ number_format($assignment->price, 2) }}</span></p>
                                        <p><strong>Paid:</strong> <span class="text-success">{{ number_format($assignment->paid_payment, 2) }}</span></p>
                                        <p><strong>Due:</strong> <span class="text-danger">{{ number_format($assignment->price - $assignment->paid_payment, 2) }}</span></p>
                                        <p><strong>Remarks:</strong>
                                            @if (!empty(strip_tags($assignment->remarks)))
                                            <span x-data="{ expanded: false }">
                                                <template x-if="!expanded">
                                                    <span>
                                                        {{ \Illuminate\Support\Str::words(strip_tags($assignment->remarks), 10, '...') }}
                                                        <button @click="expanded = true" class="text-primary text-xs ml-1">more</button>
                                                    </span>
                                                </template>
                                                <template x-if="expanded">
                                                    <span>
                                                        {!! $assignment->remarks !!}
                                                        <button @click="expanded = false" class="text-primary text-xs ml-1">less</button>
                                                    </span>
                                                </template>
                                            </span>
                                            @else
                                            —
                                            @endif
                                        </p>
                                        <p><strong>Created:</strong> {{ \Carbon\Carbon::parse($assignment->created_at)->format('d M, Y') }}</p>
                                        <p><strong>Updated:</strong> {{ \Carbon\Carbon::parse($assignment->updated_at)->format('d M, Y') }}</p>
                                    </div>

                                    <div class="mt-4 flex flex-wrap gap-2">
                                        <a href="{{ route('employee.service_assigns.edit', $assignment->id) }}"
                                            class="text-sm px-3 py-1 rounded bg-info text-white hover:opacity-90 transition">
                                            <i class="fas fa-pen mr-1"></i> Edit
                                        </a>

                                        <a href="{{ route('employee.service_assigns.invoiceGenerate', $assignment->id) }}"
                                            class="text-sm px-3 py-1 rounded bg-success text-white hover:opacity-90 transition"
                                            title="See Invoice">
                                            <i class="fas fa-eye mr-1"></i>
                                        </a>

                                        <a href="{{ route('employee.service_assigns.invoiceGeneratePdf', $assignment->id) }}"
                                            class="text-sm px-3 py-1 rounded bg-dark text-white hover:opacity-90 transition"
                                            title="Print">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-6 px-4">
                            {{ $serviceAssignments->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

@endsection
