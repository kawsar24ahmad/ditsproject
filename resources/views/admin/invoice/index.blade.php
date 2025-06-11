@extends('admin.layouts.app')
@section('css')


@endsection

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
                        <div class="px-6 py-3 border-b">
                            <h2 class="text-xl font-semibold text-secondary">Sold Services</h2>
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
                                        <p><strong>Service:</strong> {{ $assignment->service->title }}</p>
                                        <p><strong>Customer:</strong> {{ $assignment->customer->name }}</p>
                                        <p><strong>Starting Followers:</strong> {{ $assignment->customer->starting_followers ?? '-'}}</p>
                                        <p><strong>Employee:</strong> {{ $assignment->employee?->name ?? '—' }}</p>
                                        <p><strong>Price:</strong> <span class="text-dark">{{ number_format($assignment->price, 2) }}</span></p>
                                        <p><strong>Paid:</strong> <span class="text-success">{{ number_format($assignment->paid_payment, 2) }}</span></p>
                                        <p><strong>Due:</strong> <span class="text-danger">{{ number_format($assignment->price - $assignment->paid_payment, 2) }}</span></p>
<p>
    <strong>Delivery Date:</strong>
    {{ $assignment->delivery_date ? \Carbon\Carbon::parse($assignment->delivery_date)->format('F j, Y') : '-' }}
</p>
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
                                        <a href="{{ route('admin.assign_task.index', $assignment->id) }}"
                                            class="text-sm px-3 py-1 rounded bg-success text-white hover:opacity-90 transition">
                                            Assigned Tasks
                                        </a>

                                        <a href="{{ route('admin.service_assigns.edit', $assignment->id) }}"
                                            class="text-sm px-3 py-1 rounded bg-primary text-white hover:opacity-90 transition">
                                            <i class="fas fa-pen mr-1"></i> Edit
                                        </a>

                                        <a href="{{ route('admin.service_assigns.invoiceGenerate', $assignment->id) }}"
                                            class="text-sm px-3 py-1 rounded bg-info text-white hover:opacity-90 transition"
                                            title="See Invoice">
                                            <i class="fas fa-eye mr-1"></i>
                                        </a>

                                        <a href="{{ route('admin.service_assigns.invoiceGeneratePdf', $assignment->id) }}"
                                            class="text-sm px-3 py-1 rounded bg-dark text-white hover:opacity-90 transition"
                                            title="Print">
                                            <i class="fas fa-print"></i>
                                        </a>

                                        <form action="{{ route('admin.service_assigns.destroy', $assignment->id) }}"
                                            method="POST" onsubmit="return confirm('Are you sure you want to delete this assignment?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-sm px-3 py-1 rounded bg-danger text-white hover:opacity-90 transition">
                                                <i class="fas fa-trash mr-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

@endsection
@section('script')
<script>
    $(document).ready(function() {
        $('.table').DataTable();
    });
</script>

@endsection
