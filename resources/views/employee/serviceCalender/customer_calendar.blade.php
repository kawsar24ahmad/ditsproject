@extends('employee.layouts.app')

@section('content')
<div class="app-content content">
    <div class="content-wrapper">
        <div class="container py-4">

            <!-- Header Info -->
            <div class="bg-white shadow-sm rounded-3 p-4 mb-4 border">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                    <div>
                        <h4 class="fw-bold mb-1">Customer Service Calendar</h4>
                        <div class="text-muted small">
                            <span class="me-3">
                                <i class="fas fa-cogs me-1 text-primary"></i>
                                {{ $assignment->service->title }}
                            </span>

                            <span class="me-3">
                                <i class="fas fa-user me-1 text-success"></i>
                                {{ $assignment->customer->name }}
                            </span>

                            <span>
                                <i class="fas fa-file-invoice me-1 text-warning"></i>
                                {{ $assignment->invoice->invoice_number ?? 'N/A' }}
                            </span>
                        </div>
                    </div>

                    <a href="{{ route('admin.service_assigns.index') }}"
                    class="btn btn-outline-danger rounded-pill px-4">
                        ← Back
                    </a>

                </div>
            </div>



            <!-- Global Buttons -->


            <div class="bg-white border rounded-3 shadow-sm p-3 mb-4">
                <div class="d-flex flex-wrap gap-3 justify-content-center">

                    <button class="btn btn-outline-secondary rounded-pill px-4" id="toggleAllGlobalBtn">
                        <i class="fas fa-check-double me-1"></i>
                        Select All
                    </button>

                    <button class="btn btn-primary rounded-pill px-4 shadow-sm" id="globalAssignBtn">
                        <i class="fas fa-users me-1"></i>
                        Assign Tasks
                    </button>

                    <button class="btn btn-success rounded-pill px-4 shadow-sm" id="globalStatusBtn">
                        <i class="fas fa-sync-alt me-1"></i>
                        Update Status
                    </button>

                </div>
            </div>


            <!-- Calendar Days -->
           <div class="row row-cols-1 row-cols-md-2 g-4">

                @foreach($calendarDays as $day)
                <div class="col">

                <div class="card border-0 rounded-4 overflow-hidden"
                    style="
                        background: #ffffff;
                        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
                        transition: all .25s ease;
                    "
                    onmouseover="this.style.transform='translateY(-4px)'"
                    onmouseout="this.style.transform='translateY(0)'"
                >

                <!-- ===== DAY TITLE ===== -->
                <div style="
                    background: linear-gradient(135deg,#6366f1,#3b82f6,#06b6d4);
                    padding: 14px;
                    text-align:center;
                ">

                    <h5 style="
                        margin:0;
                        color:white;
                        font-weight:600;
                        letter-spacing:.5px;
                    ">
                        Day {{ $day->day_number }}
                    </h5>

                </div>


                <!-- ===== ACTION BAR ===== -->
                <div style="
                    background: linear-gradient(135deg,#f8fafc,#eef2ff);
                    padding:14px;
                    border-bottom:1px solid #eef2f7;
                ">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <div class="d-flex align-items-center gap-2">

                        <input type="checkbox"
                            class="day-toggle"
                            data-day-id="{{ $day->id }}"
                            style="width:18px;height:18px;cursor:pointer;">

                        <span style="font-weight:500;color:#64748b;">
                            Select All
                        </span>

                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-sm"
                            data-day-id="{{ $day->id }}"
                            onclick="openAddTaskModal(this)"
                            style="
                                background: linear-gradient(135deg,#10b981,#059669);
                                border:none;
                                color:white;
                                border-radius:8px;
                                padding:6px 14px;
                                font-weight:500;
                            ">
                        + Task
                    </button>


                        <button class="btn btn-sm assign-day-btn"
                                data-day-id="{{ $day->id }}"
                                style="
                                    background: linear-gradient(135deg,#6366f1,#4f46e5);
                                    border:none;
                                    color:white;
                                    border-radius:8px;
                                    padding:6px 14px;
                                    font-weight:500;
                                ">
                            Assign
                        </button>

                        <button class="btn btn-sm update-day-btn"
                                data-day-id="{{ $day->id }}"
                                style="
                                    background: linear-gradient(135deg,#f59e0b,#f97316);
                                    border:none;
                                    color:white;
                                    border-radius:8px;
                                    padding:6px 14px;
                                    font-weight:500;
                                ">
                            Status
                        </button>

                    </div>

                </div>
                </div>


                <!-- ===== TASK LIST ===== -->
                <div class="card-body" style="padding:18px; background:#f8fafc;">

                @forelse($day->tasks as $task)

                <div class="task-row mb-3"
                    data-task-id="{{ $task->id }}"
                    style="
                        background:white;
                        border-radius:14px;
                        padding:14px;
                        box-shadow:0 6px 18px rgba(0,0,0,0.05);
                        transition:.2s;
                    "
                    onmouseover="this.style.transform='translateY(-3px)'"
                    onmouseout="this.style.transform='translateY(0)'"
                >

                <!-- Top Row -->
                <div class="d-flex justify-content-between align-items-start gap-3">

                <div class="d-flex align-items-start gap-2" style="flex:1;min-width:0;">

                <input type="checkbox"
                    class="task-checkbox mt-1"
                    value="{{ $task->id }}"
                    style="width:18px;height:18px;cursor:pointer;">

                <!-- Title Safe for Long Text -->
                <div style="min-width:0;">

                <span style="
                    font-weight:600;
                    color:#1e293b;
                    display:block;



                    max-width:280px;
                ">
                    {{ $task->title }}
                </span>

                </div>
                </div>


                <!-- Right Side -->
                <div class="d-flex align-items-center gap-2">

                <!-- Status -->
                <span style="
                    padding:6px 14px;
                    border-radius:999px;
                    font-size:12px;
                    font-weight:600;

                    @if($task->status=='pending')
                        background:#fef3c7;color:#92400e;
                    @elseif($task->status=='in_progress')
                        background:#dbeafe;color:#1e40af;
                    @else
                        background:#dcfce7;color:#166534;
                    @endif
                ">
                {{ ucfirst(str_replace('_',' ',$task->status)) }}
                </span>


                <!-- Edit -->
                <button class="update-task-btn"
                        data-task-id="{{ $task->id }}"
                        data-task-title="{{ $task->title }}"
                        data-task-status="{{ $task->status }}"
                        style="
                            border:none;
                            background:#ef4444;
                            color:white;
                            border-radius:8px;
                            width:34px;
                            height:34px;
                        ">
                    <i class="fas fa-edit"></i>
                </button>

                </div>

                </div>


                <!-- Employees -->
                <div style="margin-top:10px;">

                <div style="font-size:13px;color:#64748b;margin-bottom:4px;">
                Employees
                </div>

                @if($task->employees->count())

                @foreach($task->employees as $emp)

                <span style="
                    background:#eef2ff;
                    color:#4f46e5;
                    padding:6px 12px;
                    border-radius:999px;
                    font-size:12px;
                    font-weight:500;
                    display:inline-block;
                    margin-right:6px;
                    margin-bottom:6px;
                ">
                {{ $emp->name }}
                </span>

                @endforeach

                @else

                <span style="
                    background:#f1f5f9;
                    padding:6px 12px;
                    border-radius:999px;
                    font-size:12px;
                ">
                No Employee
                </span>

                @endif

                </div>

                </div>

                @empty
                <div class="text-center text-muted py-4">
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

<!-- ================= ADD TASK MODAL ================= -->
<div class="modal fade" id="addTaskModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('customerServiceCalendars.storeTask') }}">
            @csrf

            <input type="hidden" name="day_id" id="addTaskDayId">

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Add Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Task Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" class="form-select" required>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Employees</label>

                        @foreach($employees as $employee)
                        <div class="form-check">
                            <input type="checkbox"
                                   name="employee_ids[]"
                                   value="{{ $employee->id }}"
                                   class="form-check-input">
                            <label class="form-check-label">
                                {{ $employee->name }}
                            </label>
                        </div>
                        @endforeach

                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">Save Task</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>

            </div>

        </form>
    </div>
</div>


<!-- ================= ASSIGN MODAL ================= -->
<div class="modal fade" id="assignModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="assignForm" method="POST" action="{{ route('tasks.assignMultiple') }}">
            @csrf
            <div id="modalTaskInputs"></div>
            <input type="hidden" name="day_id" id="modalDayId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign Employees</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label>Select Employees</label>
                    @foreach($employees as $employee)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="employee_ids[]" value="{{ $employee->id }}">
                        <label class="form-check-label">{{ $employee->name }}</label>
                    </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Assign</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ================= UPDATE TASK MODAL (Single Task) ================= -->
<div class="modal fade" id="updateTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="updateTaskForm" method="POST" action="{{ route('customerServiceCalendars.update') }}">
            @csrf

            <input type="hidden" name="task_id" id="updateTaskId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="title" id="updateTaskTitle" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" id="updateTaskStatus" class="form-select">
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ================= GLOBAL STATUS UPDATE MODAL ================= -->
<div class="modal fade" id="globalStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="globalStatusForm" method="POST" action="{{ route('tasks.updateMultipleStatus') }}">
            @csrf
            <input type="hidden" name="task_ids" id="globalStatusTaskIds">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Status for Selected Tasks</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label>Select Status</label>
                    <select name="status" class="form-select" required>
                        <option value="">-- Select Status --</option>
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update Status</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // --- GLOBAL SELECT ALL ---
    const toggleAllGlobalBtn = document.getElementById('toggleAllGlobalBtn');
    toggleAllGlobalBtn.addEventListener('click', () => {
        const checkboxes = document.querySelectorAll('.task-checkbox');
        const allSelected = Array.from(checkboxes).every(cb => cb.checked);
        checkboxes.forEach(cb => cb.checked = !allSelected);
        toggleAllGlobalBtn.textContent = allSelected ? 'Select All Tasks (All Days)' : 'Deselect All Tasks (All Days)';
    });

    // --- PER-DAY SELECT ALL ---
    document.querySelectorAll('.day-toggle').forEach(dayCheckbox => {
        dayCheckbox.addEventListener('change', function() {
            const card = this.closest('.card');
            if (!card) return;
            const tasks = card.querySelectorAll('.task-checkbox');
            tasks.forEach(cb => cb.checked = this.checked);
        });
    });

    // --- PER-DAY ASSIGN MODAL ---
    const assignModalEl = document.getElementById('assignModal');
    const modalTaskInputs = document.getElementById('modalTaskInputs');
    const modalDayId = document.getElementById('modalDayId');
    document.querySelectorAll('.assign-day-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const dayId = this.dataset.dayId;
            const taskIds = Array.from(this.closest('.card').querySelectorAll('.task-checkbox:checked')).map(cb => cb.value);
            if(taskIds.length === 0){
                alert('Select at least one task for this day to assign!');
                return;
            }
            modalTaskInputs.innerHTML = '';
            taskIds.forEach(id => modalTaskInputs.innerHTML += `<input type="hidden" name="task_ids[]" value="${id}">`);
            modalDayId.value = dayId;
            new bootstrap.Modal(assignModalEl).show();
        });
    });

    // --- SINGLE TASK UPDATE MODAL ---
    const updateTaskModalEl = document.getElementById('updateTaskModal');
    document.querySelectorAll('.update-task-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('updateTaskId').value = this.dataset.taskId;
            document.getElementById('updateTaskTitle').value = this.dataset.taskTitle;
            document.getElementById('updateTaskStatus').value = this.dataset.taskStatus;
            new bootstrap.Modal(updateTaskModalEl).show();
        });
    });

    // --- GLOBAL STATUS UPDATE MODAL ---
    const globalStatusModalEl = document.getElementById('globalStatusModal');
    const globalStatusModal = new bootstrap.Modal(globalStatusModalEl);
    const globalStatusTaskIdsInput = document.getElementById('globalStatusTaskIds');

    function openStatusModal(taskIds) {
        if(taskIds.length === 0){
            alert('Select at least one task!');
            return;
        }
        globalStatusTaskIdsInput.value = taskIds.join(',');
        globalStatusModal.show();
    }

    // Global status button (all days)
    document.getElementById('globalStatusBtn').addEventListener('click', function() {
        const selectedIds = Array.from(document.querySelectorAll('.task-checkbox:checked')).map(cb => cb.value);
        openStatusModal(selectedIds);
    });

    // Per-day status buttons
    document.querySelectorAll('.update-day-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const card = this.closest('.card');
            const selectedIds = Array.from(card.querySelectorAll('.task-checkbox:checked')).map(cb => cb.value);
            openStatusModal(selectedIds);
        });
    });


    const globalAssignBtn = document.getElementById('globalAssignBtn');
    globalAssignBtn.addEventListener('click', function() {
        const selectedIds = Array.from(document.querySelectorAll('.task-checkbox:checked')).map(cb => cb.value);
        if(selectedIds.length === 0){
            alert('Select at least one task to assign!');
            return;
        }

        // Clear previous inputs
        modalTaskInputs.innerHTML = '';
        selectedIds.forEach(id => modalTaskInputs.innerHTML += `<input type="hidden" name="task_ids[]" value="${id}">`);

        // No day_id needed for global assign
        modalDayId.value = '';

        new bootstrap.Modal(assignModalEl).show();
    });









});

 function openAddTaskModal(btn){
        console.log(btn);

        const dayId = btn.getAttribute('data-day-id');
        document.getElementById('addTaskDayId').value = dayId;

        new bootstrap.Modal(document.getElementById('addTaskModal')).show();
    }
</script>
@endpush
