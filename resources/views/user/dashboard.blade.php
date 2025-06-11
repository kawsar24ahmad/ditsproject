  @php
            $services = App\Models\ServiceAssign::with(['invoice:id,invoice_number,service_assign_id', 'service:id,title'])->where('customer_id', auth()->user()->id)->orderByDesc('id')->paginate(2);
            @endphp
@extends('user.layouts.app')

@section('content')


<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">


        <div class="container py-2">
    <!-- Greeting -->
  <div class="text-center pt-4">
            <h4 class="fs-1 text-4xl ">Hello, {{ auth()->user()->name }}</h4>
            <p class="text-xl text-gray-600">Welcome to your dashboard.</p>
        </div>


    <!-- Summary Boxes -->
    <div class="row mb-5 g-3 mt-2">
        <div class="col-md-4">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Services</h5>
                    <h2 class="fw-bold">{{ $services->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Paid</h5>
                    <h2 class="fw-bold">৳{{ number_format($services->sum('paid_payment'), 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-danger shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Due</h5>
                    <h2 class="fw-bold">
                        ৳{{ number_format($services->sum(fn($s) => $s->price - $s->paid_payment), 2) }}
                    </h2>
                </div>
            </div>
        </div>
    </div>


 <!-- Services Table -->
  <!-- Main row -->
 <div class="content-body ">
    <section id="data-display">
        <div class="container">
            <h4 class="mb-4 fw-bold text-primary">
                <i class="fas fa-briefcase me-2"></i>Your Services
                <span class="badge bg-dark text-white ms-2 px-3 py-2 rounded-pill">{{ $services->count() }} total</span>
            </h4>

            <div class="row">
                @foreach ($services as $assignment)
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Invoice: {{ $assignment->invoice->invoice_number }}</span>
                            <span class="badge bg-light text-dark text-capitalize">
                                {{ $assignment->status }}
                            </span>
                        </div>

                       <div class="card-body">
    <h5 class=" fw-semibold mb-2">
        {{ $assignment->service->title }} — <span class="fw-normal">৳{{ number_format($assignment->price, 2) }}</span>
    </h5>

    <p class="mb-1 text-success"><strong>Paid:</strong> ৳{{ number_format($assignment->paid_payment, 2) }}</p>
    <p class="mb-1 text-danger"><strong>Due:</strong> ৳{{ number_format($assignment->price - $assignment->paid_payment, 2) }}</p>
    <p class="mb-1"><strong>Delivery Date:</strong> {{ \Carbon\Carbon::parse($assignment->delivery_date)->format('d M, Y') }}</p>
    <p class="mb-1"><strong>Created:</strong> {{ \Carbon\Carbon::parse($assignment->created_at)->format('d M, Y') }}</p>
    <p class="mb-1"><strong>Updated:</strong> {{ \Carbon\Carbon::parse($assignment->updated_at)->format('d M, Y') }}</p>
</div>


                       <div class="card-footer bg-light border-top">
    <div class="d-flex justify-content-between gap-2">
        <a href="{{ route('user.service_assigns.show', $assignment->id) }}" class="btn btn-outline-success btn-sm w-100">
            <i class="fas fa-eye me-1"></i> View
        </a>

        @if ($assignment->invoice)
            <a href="{{ route('user.service_assigns.invoiceGenerate', $assignment->invoice->id) }}" class="btn btn-info btn-sm text-white w-100">
                <i class="fas fa-file-invoice me-1"></i> Invoice
            </a>

            <a href="{{ route('user.service_assigns.invoiceGeneratePdf', $assignment->invoice->id) }}" class="btn btn-secondary btn-sm w-100">
                <i class="fas fa-print me-1"></i> Print
            </a>
        @endif
    </div>
</div>

                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-5 d-flex justify-content-center">
                {{ $services->appends(request()->query())->links() }}
            </div>
        </div>
    </section>
</div>



            <!-- /.row (main row) -->
</div>
    </div>
</div>

@endsection
