@extends('user.layouts.app')

@section('content')

<div class="app-content content">
<div class="content-wrapper">
<div class="container py-4">

<!-- ================= HEADER ================= -->
<div style="
background: linear-gradient(135deg,#6366f1,#4f46e5,#0ea5e9);
border-radius:20px;
padding:28px;
color:white;
box-shadow:0 20px 50px rgba(79,70,229,.25);
margin-bottom:28px;
">

<div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

<div>

<h4 style="font-weight:700;margin-bottom:6px;">
Customer Service Calendar
</h4>

<div style="opacity:.9;font-size:14px;display:flex;flex-wrap:wrap;gap:18px;">

<span>
<i class="fas fa-cogs me-1"></i>
{{ $assignment->service->title }}
</span>

<span>
<i class="fas fa-user me-1"></i>
{{ $assignment->customer->name }}
</span>

<span>
<i class="fas fa-file-invoice me-1"></i>
{{ $assignment->invoice->invoice_number ?? 'N/A' }}
</span>

</div>

</div>

<a href="{{ route('user.dashboard') }}"
style="
background:white;
color:#4f46e5;
padding:10px 22px;
border-radius:999px;
font-weight:600;
text-decoration:none;
box-shadow:0 6px 16px rgba(0,0,0,.1);
">
← Back
</a>

</div>

</div>


<!-- ================= CALENDAR ================= -->
<div class="row row-cols-1 row-cols-md-2 g-4">

@foreach($calendarDays as $day)

<div class="col">

<div style="
background:white;
border-radius:20px;
overflow:hidden;
box-shadow:0 12px 35px rgba(0,0,0,.08);
transition:.35s;
"
onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 25px 60px rgba(0,0,0,.12)'"
onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 12px 35px rgba(0,0,0,.08)'"
>

<!-- ===== DAY HEADER ===== -->
<div style="
background: linear-gradient(135deg,#6366f1,#3b82f6,#06b6d4);
padding:16px;
text-align:center;
">

<h5 style="
margin:0;
color:white;
font-weight:700;
letter-spacing:.6px;
">
Day {{ $day->day_number }}
</h5>

</div>


<!-- ===== TASK LIST ===== -->
<div style="padding:20px;background:#f8fafc;">

@forelse($day->tasks as $task)

<div style="
background:white;
border-radius:16px;
padding:16px;
margin-bottom:14px;
box-shadow:0 8px 22px rgba(0,0,0,.05);
transition:.25s;
border-left:5px solid
@if($task->status=='pending') #f59e0b
@elseif($task->status=='in_progress') #3b82f6
@else #10b981
@endif;
"
onmouseover="this.style.transform='translateX(4px)'"
onmouseout="this.style.transform='translateX(0)'"
>

<div class="d-flex justify-content-between align-items-center">

<!-- TASK TITLE -->
<div style="flex:1;min-width:0;">

<div style="
font-weight:600;
color:#0f172a;
font-size:14.5px;
margin-bottom:6px;
">
{{ $task->title }}
</div>

</div>

<!-- STATUS -->
<span style="
padding:7px 16px;
border-radius:999px;
font-size:12px;
font-weight:700;
letter-spacing:.3px;

@if($task->status=='pending')
background:#fff7ed;color:#c2410c;
@elseif($task->status=='in_progress')
background:#eff6ff;color:#1d4ed8;
@else
background:#ecfdf5;color:#047857;
@endif
">
{{ ucfirst(str_replace('_',' ',$task->status)) }}
</span>

</div>

</div>

@empty

<div style="
text-align:center;
padding:40px 10px;
color:#94a3b8;
font-size:14px;
">
No tasks available
</div>

@endforelse

</div>

</div>
</div>

@endforeach

</div>

</div>
</div>
</div>

@endsection
