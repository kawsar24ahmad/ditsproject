@extends('admin.layouts.app')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .form-field-row {
        border: 1px solid #ddd;
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 5px;
    }

    .form-field-row input,
    .form-field-row select {
        margin-bottom: 10px;
    }

    .remove-field {
        margin-top: 10px;
    }

    .add-field-btn-container {
        text-align: right;
        margin-top: 20px;
    }

    .form-group label {
        font-weight: 600;
    }

    .form-control,
    .btn {
        border-radius: 5px;
    }
</style>
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
                                <li class="breadcrumb-item"><a href="{{ route('admin.services.index') }}">Services</a></li>
                                <li class="breadcrumb-item active"> Service Tasks</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">

            {{-- Tasks Section --}}
            <div class="card mt-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Service Tasks for: <span class="text-danger fw-bold fs-3">{{ $service->title }}</span></h4>
                </div>
                <div class="card-body">
                    <form  action="{{ route('admin.tasks.store', $service->id) }}" method="POST" class="d-flex align-items-end mb-3 gap-2 flex-column ">
                        @csrf
                        <input  type="text" name="title" class="form-control" placeholder="Add new task..." required>
                        <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Add</button>
                    </form>

                    @if($service->tasks->count())
                    <ul class="list-group">
                        @foreach($service->tasks as $task)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $task->title }}</span>
                            <div class="d-flex gap-2">
                                <a href="javascript:void(0);" class="edit-task-btn"
                                   data-id="{{ $task->id }}"
                                   data-title="{{ $task->title }}"
                                   data-url="{{ route('admin.tasks.update', $task->id) }}">
                                    <i class="fas fa-edit text-warning"></i>
                                </a>
                                <form action="{{ route('admin.tasks.destroy', $task->id) }}" method="POST" class="mb-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this task?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p class="text-muted">No tasks found for this service.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editTaskForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit-task-title" class="form-label">Task Title</label>
                        <input type="text" class="form-control" id="edit-task-title" name="title" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2();
        $('.summernote').summernote({
            height: 200
        });

        $('.edit-task-btn').on('click', function () {
            const title = $(this).data('title');
            const url = $(this).data('url');

            $('#edit-task-title').val(title);
            $('#editTaskForm').attr('action', url);

            const modal = new bootstrap.Modal(document.getElementById('editTaskModal'));
            modal.show();
        });
    });
</script>
@endsection
