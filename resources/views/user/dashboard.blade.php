@extends('user.layouts.app')

@push('css')
<style>
    /* Animated modern gradient for Calendar button */
    @keyframes modernGradient {
        0%{background-position:0% 50%;}
        50%{background-position:100% 50%;}
        100%{background-position:0% 50%;}
    }

    /* Calendar Button Style */
    .btn-calendar {
        padding: 10px 20px;
        border-radius: 12px;
        font-weight: 600;
        color: #fff;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(270deg,#06d6a0,#3b82f6,#a855f7,#f472b6,#06d6a0);
        background-size: 400% 400%;
        animation: modernGradient 6s ease infinite;
        box-shadow: 0 5px 15px rgba(0,0,0,0.25);
        transition: transform 0.3s, box-shadow 0.3s, filter 0.3s;
    }

    .btn-calendar:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.35);
        filter: brightness(1.1);
    }

    /* Other buttons */
    .btn-view {
        padding: 10px 18px;
        border-radius: 12px;
        border: 1px solid #3b82f6;
        color: #3b82f6;
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: 0.3s;
    }
    .btn-view:hover {
        background: #eff6ff;
    }

    .btn-invoice {
        padding: 10px 18px;
        border-radius: 12px;
        background: linear-gradient(135deg,#06b6d4,#22d3ee);
        color: white;
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: 0.3s;
    }
    .btn-invoice:hover {
        background: linear-gradient(135deg,#22d3ee,#06b6d4);
    }

    .btn-print {
        padding: 10px 18px;
        border-radius: 12px;
        border: 1px solid #d1d5db;
        color: #374151;
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: 0.3s;
    }
    .btn-print:hover {
        background: #f3f4f6;
    }
</style>
@endpush

@section('content')

@php
$services = App\Models\ServiceAssign::with([
    'invoice:id,invoice_number,service_assign_id',
    'service:id,title'
])->where('customer_id', auth()->user()->id)
  ->orderByDesc('id')
  ->paginate(2);
@endphp

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="container py-5">

            <!-- SUMMARY BOXES -->
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div style="padding:25px; border-radius:20px; color:white; background: linear-gradient(135deg,#6366f1,#3b82f6); box-shadow:0 10px 25px rgba(0,0,0,0.1);">
                        <h6 style="opacity:0.7; font-weight:600;">Total Services</h6>
                        <h2 style="margin-top:10px; font-weight:700;">{{ $services->count() }}</h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div style="padding:25px; border-radius:20px; color:white; background: linear-gradient(135deg,#10b981,#22c55e); box-shadow:0 10px 25px rgba(0,0,0,0.1);">
                        <h6 style="opacity:0.7; font-weight:600;">Total Paid</h6>
                        <h2 style="margin-top:10px; font-weight:700;">৳{{ number_format($services->sum('paid_payment'), 2) }}</h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div style="padding:25px; border-radius:20px; color:white; background: linear-gradient(135deg,#f59e0b,#fbbf24); box-shadow:0 10px 25px rgba(0,0,0,0.1);">
                        <h6 style="opacity:0.7; font-weight:600;">Total Due</h6>
                        <h2 style="margin-top:10px; font-weight:700;">৳{{ number_format($services->sum(fn($s) => $s->price - $s->paid_payment), 2) }}</h2>
                    </div>
                </div>
            </div>

            <!-- SERVICES CARDS -->
            <h4 style="font-weight:700; margin-bottom:20px; color:#1e40af;">
                <i class="fas fa-briefcase me-2"></i>Your Services
                <span style="background:#111827; color:white; padding:5px 15px; border-radius:50px; font-size:0.85rem;">{{ $services->count() }} total</span>
            </h4>

            <div class="row g-4">
                @foreach($services as $assignment)
                @php
                    $totalTasks = $assignment->assignedTasks->count();
                    $completedTasks = $assignment->assignedTasks->where('is_completed', 1)->count();
                    $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                @endphp

                <div class="col-md-6">
                    <div style="border-radius:20px; box-shadow:0 15px 30px rgba(0,0,0,0.08); overflow:hidden; transition: transform 0.3s; cursor:pointer;"
                        onmouseover="this.style.transform='translateY(-8px)';"
                        onmouseout="this.style.transform='translateY(0)';">

                        <!-- Header -->
                        <div style="display:flex; justify-content:space-between; align-items:center; padding:15px; background: linear-gradient(135deg,#4f46e5,#6366f1); color:white;">
                            <span style="font-weight:700;">Invoice: {{ $assignment->invoice->invoice_number }}</span>
                            <span style="background:white; color:#1f2937; padding:5px 15px; border-radius:50px; font-size:0.85rem; font-weight:600; text-transform:capitalize; box-shadow:0 2px 5px rgba(0,0,0,0.1);">
                                {{ $assignment->status }}
                            </span>
                        </div>

                        <!-- Progress -->
                        <div style="padding:15px;">
                            <div style="height:18px; background:#e5e7eb; border-radius:50px; overflow:hidden; margin-bottom:5px;">
                                <div style="width:{{ $percentage }}%; height:100%; border-radius:50px; background:
                                    @if($percentage == 100) #10b981
                                    @elseif($percentage >= 50) #3b82f6
                                    @elseif($percentage >= 20) #f59e0b
                                    @else #ef4444
                                    @endif;">
                                </div>
                            </div>
                            <small style="color:#6b7280;">{{ $completedTasks }} of {{ $totalTasks }} tasks completed</small>
                        </div>

                        <!-- Body -->
                        <div style="padding:15px; border-top:1px solid #e5e7eb;">
                            <h5 style="font-weight:600; margin-bottom:8px;">{{ $assignment->service->title }}
                                <span style="color:#6b7280; font-weight:400;">৳{{ number_format($assignment->price, 2) }}</span>
                            </h5>
                            <p style="color:#10b981; margin-bottom:5px;"><strong>Paid:</strong> ৳{{ number_format($assignment->paid_payment, 2) }}</p>
                            <p style="color:#ef4444; margin-bottom:5px;"><strong>Due:</strong> ৳{{ number_format($assignment->price - $assignment->paid_payment, 2) }}</p>
                            <p style="color:#374151; margin-bottom:0;"><strong>Delivery Date:</strong> {{ \Carbon\Carbon::parse($assignment->delivery_date)->format('d M, Y') }}</p>
                        </div>

                        <!-- Footer Buttons -->
                        <div style="padding:15px; display:flex; flex-wrap:wrap; gap:10px; border-top:1px solid #e5e7eb; justify-content:flex-start;">

                            @php $hasCalendar = $assignment->calendarDays->contains(fn($day) => $day->tasks->count() > 0); @endphp
                            @if($hasCalendar)
                                <a href="{{ route('userServiceCalendarIndex', $assignment->id) }}" class="btn-calendar">
                                    <i class="fas fa-calendar-alt"></i> Calendar
                                </a>
                            @endif

                            <a href="{{ route('user.service_assigns.show', $assignment->id) }}" class="btn-view">
                                <i class="fas fa-eye"></i>
                            </a>

                            @if($assignment->invoice)
                                <a href="{{ route('user.service_assigns.invoiceGenerate', $assignment->invoice->id) }}" class="btn-invoice">
                                    <i class="fas fa-file-invoice"></i> Invoice
                                </a>

                                <a href="{{ route('user.service_assigns.invoiceGeneratePdf', $assignment->invoice->id) }}" class="btn-print">
                                    <i class="fas fa-print"></i>
                                </a>
                            @endif

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
    </div>
</div>

@endsection
