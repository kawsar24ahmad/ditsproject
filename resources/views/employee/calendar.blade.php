@extends('employee.layouts.app')

@push('css')
<style>

/* CARD */
.day-card{
transition: all .3s ease;
background:#ffffff;
overflow:hidden;
}

.day-card:hover{
transform: translateY(-6px);
box-shadow:0 20px 40px rgba(0,0,0,.08);
}

/* HEADER */
.day-header{
background: linear-gradient(135deg,#6366f1,#4f46e5);
position:relative;
}

.day-header:after{
content:'';
position:absolute;
inset:0;
background: radial-gradient(circle at top right, rgba(255,255,255,.25), transparent 60%);
}

/* TASK COUNT */
.task-count-badge{
background: rgba(255,255,255,.18);
backdrop-filter: blur(8px);
padding:6px 14px;
border-radius:30px;
font-size:12px;
font-weight:600;
}

/* COMPACT INFO */
.compact-info{
display:flex;

background-color: #f8fafc;
}

.compact-info span{
background:rgba(255,255,255,.18);
padding:7px 12px;
border-radius:10px;
font-size:12px;
display:flex;
align-items:center;
gap:6px;
white-space:nowrap;
}

/* TASK CARD */
.task-card{
background:#f8fafc;
border-radius:12px;
padding:14px;
transition:.25s;
}

.task-card:hover{
background:#f1f5f9;
transform: translateX(4px);
}

/* STATUS BUTTON */
.status-btn{
width:36px;
height:36px;
border-radius:50%;
border:none;
background:#eef2ff;
color:#4f46e5;
display:flex;
align-items:center;
justify-content:center;
transition:.25s;
}

.status-btn:hover{
background:#4f46e5;
color:white;
transform: scale(1.05);
}

</style>
@endpush


@section('content')

<div class="app-content content">
<div class="content-wrapper">

<div class="content-body">
<section>
<div class="row">
<div class="col-12">

<div class="card shadow-lg border-0 rounded-4">
<div class="card-header bg-white border-bottom py-3">
<h4 class="fw-bold mb-0">
<i class="feather icon-calendar me-2"></i>
Work Calendar
</h4>
</div>

<div class="card-body p-4">

<div class="row g-4">

@forelse($days as $day)

<div class="col-lg-6 col-xl-4">

<div class="card border-0 shadow-sm rounded-4 h-100 day-card">

<!-- HEADER -->
<div class="p-4 text-white day-header">

<div class="d-flex justify-content-between align-items-start">

<div>
<h5 class="fw-bold mb-1">
Day {{ $day->day_number }}
</h5>
<small class="opacity-75">Work Overview</small>
</div>

<div class="task-count-badge">
{{ $day->tasks->count() }} Tasks
</div>

</div>


</div>


<!-- INFO -->
<div class="compact-info">

<span>
<i class="fas fa-user"></i>
{{ $day->serviceAssign->customer->name ?? 'N/A' }}
</span>

<span>
<i class="fas fa-briefcase"></i>
{{ Str::limit($day->serviceAssign->service->title ?? 'N/A',20) }}
</span>

<span>
<i class="fas fa-file-invoice"></i>
<a href="{{ route('employee.service_assigns.edit', $day->serviceAssign->id) }}">{{ $day->serviceAssign->invoice->invoice_number ?? 'N/A' }}</a>
</span>

</div>

<!-- TASK LIST -->
<div class="p-3">

@foreach($day->tasks as $task)

@php
$statusMap = [
'pending' => ['color'=>'secondary','bar'=>'#64748b'],
'in_progress' => ['color'=>'warning','bar'=>'#f59e0b'],
'completed' => ['color'=>'success','bar'=>'#10b981']
];

$st = $statusMap[$task->status] ?? $statusMap['pending'];
@endphp

<div class="task-card mb-3"
style="border-left:5px solid {{ $st['bar'] }}">

<div class="d-flex justify-content-between align-items-center">

<div>
<div class="fw-semibold small mb-1">
{{ $task->title }}
</div>

<span class="badge bg-{{ $st['color'] }} small px-3 py-2">
{{ ucfirst(str_replace('_',' ',$task->status)) }}
</span>
</div>

<button class="status-btn"
onclick="openStatusModal({{ $task->id }}, '{{ $task->status }}')">
<i class="fas fa-pen"></i>
</button>

</div>

</div>

@endforeach

</div>

</div>
</div>

@empty
<div class="col-12 text-center py-5 text-muted">
No Tasks Found
</div>
@endforelse

</div>

</div>
</div>

</div>
</div>
</section>
</div>

</div>
</div>


<!-- STATUS MODAL -->
<div class="modal fade" id="statusModal">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content rounded-4 border-0 shadow">

<form method="POST" action="{{ route('employee.task.status.update') }}">
@csrf

<div class="modal-header border-0">
<h5 class="fw-bold">Update Task Status</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<input type="hidden" name="task_id" id="modalTaskId">

<select name="status" id="modalStatus" class="form-select">
<option value="pending">Pending</option>
<option value="in_progress">In Progress</option>
<option value="completed">Completed</option>
</select>

</div>

<div class="modal-footer border-0">
<button type="submit" class="btn btn-primary px-4">
Update
</button>
</div>

</form>

</div>
</div>
</div>

@endsection


@push('scripts')
<script>
function openStatusModal(taskId, status){
document.getElementById('modalTaskId').value = taskId;
document.getElementById('modalStatus').value = status;
new bootstrap.Modal(document.getElementById('statusModal')).show();
}
</script>
@endpush
