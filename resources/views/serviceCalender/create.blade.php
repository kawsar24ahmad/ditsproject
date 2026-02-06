@extends('admin.layouts.app')

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
                                <li class="breadcrumb-item active">Service Calendar</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <section class="container mt-3">
                <div class="max-w-3xl mx-auto p-6 bg-white shadow-lg rounded-lg">
                    <h2 class="text-3xl font-bold mb-6 text-center">Create Service Calendar Template (30 Days)</h2>

                    <!-- Error Messages -->
                    <div id="error-box" class="alert alert-danger d-none">
                        <ul id="error-list" class="mb-0"></ul>
                    </div>

                    <div id="success-box" class="alert alert-success d-none"></div>

                    <form method="post" action="{{ route('serviceCalendars.store') }}">
                        @csrf

                        <!-- Select Service -->
                        <div class="mb-6">
                            <label class="block font-semibold mb-2">Select Service</label>
                            <select name="service_id" class="w-full border border-gray-300 p-3 rounded shadow-sm focus:ring-2 focus:ring-indigo-500" required>
                                <option value="">-- Select Service --</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Days -->
                        <div id="days-container">
                            @for($day = 1; $day <= 30; $day++)
                                <div class="day mb-4 p-3 bg-gray-50 rounded-lg shadow-sm border" data-day="{{ $day }}">
                                    <h3 class="text-lg font-semibold mb-2">Day {{ $day }}</h3>
                                    <div class="tasks-container mb-2">
                                        <div class="task flex mb-2">
                                            <input type="text" name="days[{{ $day }}][tasks][0][title]" class="w-full border border-gray-300 p-2 rounded" placeholder="Task title" required>
                                            <button type="button" class="remove-task-btn ml-2 bg-red text-white p-2 rounded">X</button>
                                        </div>
                                    </div>
                                    <button type="button" class="add-task-btn bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition">+ Add Task</button>
                                </div>
                            @endfor
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="bg-warning text-white px-6 py-2 rounded shadow hover:bg-indigo-700 transition w-full">Save Calendar</button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Add task dynamically
    document.querySelectorAll('.add-task-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const dayDiv = btn.closest('.day');
            const tasksContainer = dayDiv.querySelector('.tasks-container');
            const dayIndex = dayDiv.getAttribute('data-day');

            const taskIndex = tasksContainer.querySelectorAll('.task').length;

            const taskDiv = document.createElement('div');
            taskDiv.classList.add('task', 'flex', 'mb-2');
            taskDiv.innerHTML = `
                <input type="text" name="days[${dayIndex}][tasks][${taskIndex}][title]" class="w-full border border-gray-300 p-2 rounded" placeholder="Task title" required>
                <button type="button" class="remove-task-btn ml-2 bg-red text-white p-2 rounded">X</button>
            `;
            tasksContainer.appendChild(taskDiv);

            // Remove task button
            taskDiv.querySelector('.remove-task-btn').addEventListener('click', function() {
                taskDiv.remove();
            });
        });
    });

    // Remove initial task buttons
    document.querySelectorAll('.remove-task-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const taskDiv = btn.closest('.task');
            const tasksContainer = taskDiv.parentNode;
            if(tasksContainer.querySelectorAll('.task').length > 1){
                taskDiv.remove();
            } else {
                alert("Each day must have at least 1 task!");
            }
        });
    });


});
</script>
@endpush
