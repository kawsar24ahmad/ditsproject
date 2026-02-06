@extends('admin.layouts.app')

@section('css')
<style>
.task-row{
    transition:all .2s ease;
    border:1px solid #eef2f7;
}

.task-row:hover{
    transform:translateY(-2px);
    box-shadow:0 12px 25px rgba(0,0,0,.05);
}

.badge-modern{
    padding:6px 14px;
    border-radius:999px;
    font-size:12px;
    font-weight:600;
}

.status-pending{
    background:#fff7ed;
    color:#c2410c;
}

.status-progress{
    background:#eff6ff;
    color:#1d4ed8;
}

.status-completed{
    background:#ecfdf5;
    color:#047857;
}

</style>
@endsection

@section('content')
<div class="app-content content">
<div class="content-wrapper">
<div class="container py-4">

<div class="card shadow border-0 rounded-4">
<div class="card-body">

<h3 class="text-center mb-4 fw-bold">
Service Calendar Template
</h3>
<p class="text-center mb-4 fw-bold">
<strong>Service :</strong>  {{$service->title  }}
</p>

<!-- GLOBAL BUTTONS -->
<div class="text-center mb-4">
<button class="btn btn-outline-secondary rounded-pill px-4" id="toggleAllGlobalBtn">
<i class="fas fa-check-double me-1"></i> Select All
</button>

<button class="btn btn-primary rounded-pill px-4" id="globalAssignBtn">
<i class="fas fa-users me-1"></i> Assign Tasks
</button>

<button class="btn btn-success rounded-pill px-4" id="globalUpdateStatusBtn">
<i class="fas fa-sync me-1"></i> Update Status
</button>
</div>


<div class="row row-cols-1 row-cols-md-2 g-4">

@foreach($calendars as $day)
<div class="col">

<div class="card border-0 rounded-4 shadow-sm">

<!-- Day Header -->
<div style="
background:linear-gradient(135deg,#6366f1,#3b82f6,#06b6d4);
padding:14px;
text-align:center;
border-radius:16px 16px 0 0;
">
<h5 class="text-white fw-semibold mb-0">
Day {{ $day->day_number }}
</h5>
</div>

<!-- Action Bar -->
<div class="p-3 border-bottom bg-light">

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

<div class="d-flex align-items-center gap-2">
<input type="checkbox"
class="day-toggle"
data-day="{{ $day->day_number }}">
<span class="text-muted small">Select All</span>
</div>

<div class="d-flex gap-2">

<button class="btn btn-sm btn-success addTaskBtn"
data-day="{{ $day->day_number }}" data-serviceid="{{ $day->service_id }}">
+ Task
</button>

<button class="btn btn-sm btn-primary assignBtn"
data-day="{{ $day->day_number }}">
Assign
</button>

<button class="btn btn-sm btn-warning updateStatusBtn"
data-day="{{ $day->day_number }}" >
Status
</button>

</div>
</div>
</div>

<!-- TASK LIST -->
<div class="card-body bg-light">

<div class="row">
@foreach($day->tasks as $task)

<div class="col-12 mb-3">

<div class="task-row bg-white rounded-4 p-3 shadow-sm">

<div class="d-flex justify-content-between align-items-start">

<!-- LEFT SIDE -->
<div class="d-flex gap-3 align-items-start flex-grow-1">

<input type="checkbox"
class="taskCheckbox mt-1"
value="{{ $task->id }}"
data-day="{{ $day->day_number }}"
style="width:18px;height:18px;cursor:pointer;">

<div class="flex-grow-1">

<div class="fw-semibold text-dark mb-1" style="font-size:15px;">
{{ $task->title }}
</div>

<span class="badge-modern
@if($task->status=='pending') status-pending
@elseif($task->status=='in_progress') status-progress
@else status-completed
@endif">
{{ ucfirst(str_replace('_',' ',$task->status)) }}
</span>

</div>
</div>

<!-- RIGHT SIDE -->
<div>
<button
type="button"
class="btn btn-sm btn-primary rounded-3 px-3 updateBtn"
data-task="{{ $task->id }}"
data-title="{{ $task->title }}"
data-status="{{ $task->status }}">
<i class="fas fa-pen me-1"></i> Update
</button>
</div>

</div>

<!-- EMPLOYEES -->
<div class="mt-3 pt-2 border-top">

<small class="text-muted d-block mb-2">Employees</small>

<div class="d-flex flex-wrap gap-2">

@if($task->employees->count())
@foreach($task->employees as $emp)

<span class="badge bg-light text-dark border px-3 py-2">
{{ $emp->name }}
</span>

@endforeach
@else

<span class="badge bg-secondary px-3 py-2">
No Employee
</span>

@endif

</div>

</div>

</div>
</div>

@endforeach
</div>


</div>

</div>
</div>
@endforeach

</div>

</div>
</div>

</div>
</div>
</div>

@include('serviceCalender.modal')

<!-- UPDATE TASK MODAL -->
<div class="modal fade" id="updateTaskModal">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('service.calendar.task.update') }}">
            @csrf
            @method('PUT')

            <input type="hidden" name="task_id" id="updateTaskId">

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Update Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <!-- TITLE -->
                    <div class="mb-3">
                        <label class="form-label">Task Title</label>
                        <input type="text" name="title" id="updateTaskTitle" class="form-control" required>
                    </div>

                    <!-- STATUS -->
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" id="updateTaskStatus" class="form-control">
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>




                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Update Task</button>
                </div>

            </div>
        </form>
    </div>
</div>

@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function(){

// GLOBAL SELECT
let globalToggleBtn = document.getElementById('toggleAllGlobalBtn');
globalToggleBtn.addEventListener('click', () => {
    let checkboxes = document.querySelectorAll('.taskCheckbox');
    let allSelected = Array.from(checkboxes).every(cb => cb.checked);
    checkboxes.forEach(cb => cb.checked = !allSelected);
});

// PER DAY SELECT
document.querySelectorAll('.day-toggle').forEach(toggle => {
    toggle.addEventListener('change', function(){
        let day = this.dataset.day;
        document.querySelectorAll(`.taskCheckbox[data-day="${day}"]`)
        .forEach(cb => cb.checked = this.checked);
    });
});

// ASSIGN PER DAY
document.querySelectorAll('.assignBtn').forEach(btn => {
    btn.addEventListener('click', () => {

        let day = btn.dataset.day;
        let selected = [];

        document.querySelectorAll(`.taskCheckbox[data-day="${day}"]:checked`)
        .forEach(cb => selected.push(cb.value));

        if(!selected.length){
            alert('Select task first');
            return;
        }

        let div = document.getElementById('assignTaskInputs');
        div.innerHTML = '';
        selected.forEach(id => div.innerHTML += `<input type="hidden" name="task_ids[]" value="${id}">`);

        document.getElementById('assignDay').value = day;

        new bootstrap.Modal(document.getElementById('assignModal')).show();
    });
});

// GLOBAL ASSIGN
document.getElementById('globalAssignBtn').addEventListener('click', () => {

    let selected = [];
    document.querySelectorAll('.taskCheckbox:checked')
    .forEach(cb => selected.push(cb.value));

    if(!selected.length){
        alert('Select task first');
        return;
    }

    let div = document.getElementById('assignTaskInputs');
    div.innerHTML = '';
    selected.forEach(id => div.innerHTML += `<input type="hidden" name="task_ids[]" value="${id}">`);

    document.getElementById('assignDay').value = '';

    new bootstrap.Modal(document.getElementById('assignModal')).show();
});

// ADD TASK
document.querySelectorAll('.addTaskBtn').forEach(btn=>{
    btn.addEventListener('click', ()=>{
        document.getElementById('addTaskDay').value = btn.dataset.day;
        document.getElementById('serviceId').value = btn.dataset.serviceid;
        new bootstrap.Modal(document.getElementById('addTaskModal')).show();
    });
});
document.querySelectorAll('.updateBtn').forEach(btn => {

    btn.addEventListener('click', function(){

        let taskId = this.dataset.task;
        let title = this.dataset.title;
        let status = this.dataset.status;

        // Fill modal fields
        document.getElementById('updateTaskId').value = taskId;
        document.getElementById('updateTaskTitle').value = title;
        document.getElementById('updateTaskStatus').value = status;

        // Load employees via AJAX (optional advanced)
        fetch(`/admin/service-calendar/task/${taskId}/employees`)
        .then(res => res.json())
        .then(data => {

            let select = document.getElementById('updateTaskEmployees');

            Array.from(select.options).forEach(opt => {
                opt.selected = data.includes(parseInt(opt.value));
            });

        });

        new bootstrap.Modal(document.getElementById('updateTaskModal')).show();

    });

});



// UPDATE STATUS PER DAY
document.querySelectorAll('.updateStatusBtn').forEach(btn => {
    btn.addEventListener('click', () => {

        let day = btn.dataset.day;
        let selected = [];

        document.querySelectorAll(`.taskCheckbox[data-day="${day}"]:checked`)
        .forEach(cb => selected.push(cb.value));

        if(!selected.length){
            alert('Select task first');
            return;
        }

        let div = document.getElementById('statusTaskInputs');
        div.innerHTML = '';
        selected.forEach(id => div.innerHTML += `<input type="hidden" name="task_ids[]" value="${id}">`);

        document.getElementById('statusDay').value = day;

        new bootstrap.Modal(document.getElementById('updateStatusModal')).show();
    });
});

// GLOBAL STATUS
document.getElementById('globalUpdateStatusBtn').addEventListener('click', ()=>{

    let selected = [];
    document.querySelectorAll('.taskCheckbox:checked')
    .forEach(cb => selected.push(cb.value));

    if(!selected.length){
        alert('Select task first');
        return;
    }

    let div = document.getElementById('statusTaskInputs');
    div.innerHTML = '';
    selected.forEach(id => div.innerHTML += `<input type="hidden" name="task_ids[]" value="${id}">`);

    document.getElementById('statusDay').value = '';

    new bootstrap.Modal(document.getElementById('updateStatusModal')).show();
});

});

    </script>
@endpush
