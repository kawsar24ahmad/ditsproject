
<!-- ================= ASSIGN MODAL ================= -->
<div class="modal fade" id="assignModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('serviceCalenderTaskAssign') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Assign Employees</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="day_number" id="assignDay">
                    <div id="assignTaskInputs"></div>

                    <label class="fw-bold">Select Employees:</label>
                    @foreach($employees as $emp)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="employee_ids[]" value="{{ $emp->id }}">
                        <label class="form-check-label">{{ $emp->name }}</label>
                    </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Assign</button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- ================= ADD TASK MODAL ================= -->
<div class="modal fade" id="addTaskModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('serviceCalenderTaskStore') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="day_number" id="addTaskDay">
                    <input type="hidden" name="service_id" id="serviceId">

                    <div class="mb-3">
                        <label class="form-label">Task Title</label>
                        <input type="text" name="title" required class="form-control">
                    </div>

                    <label class="fw-bold">Assign Employees:</label>
                    @foreach($employees as $emp)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="employee_ids[]" value="{{ $emp->id }}">
                        <label class="form-check-label">{{ $emp->name }}</label>
                    </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success">Save Task</button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- ================= UPDATE STATUS MODAL ================= -->
<div class="modal fade" id="updateStatusModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('serviceCalenderTaskUpdateStatus') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Update Task Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="day_number" id="statusDay">
                    <div id="statusTaskInputs"></div>

                    <label class="fw-bold">Select Status:</label>
                    <select name="status" class="form-select" required>
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-info">Update Status</button>
                </div>
            </form>

        </div>
    </div>
</div>
