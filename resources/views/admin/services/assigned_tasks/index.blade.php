@extends('admin.layouts.app')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .invoice-box {
        padding: 30px;
        border: 1px solid #eee;
        background: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        font-size: 16px;
    }
</style>
@endsection

@section('content')
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="col-12">
                <h3 class="content-header-title">Edit Assigned Service & Payment</h3>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Edit Service Assignment</li>
                    </ol>
                </div>
            </div>
        </div>

        @if ($errors->any())
        <div class="alert alert-danger mt-2">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="content-body">
            <section class="invoice-box mt-3">

                <div class="row">
                    <div id="customerInfo table-responsive" class="mt-3">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="2">Customer Information</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th width="30%">Name:</th>
                                    <td>{{ $serviceAssign->customer->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $serviceAssign->customer->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $serviceAssign->customer->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Facebook ID Link:</th>
                                    <td>{{ $serviceAssign->customer_fb_id_link ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Facebook Page Link:</th>
                                    <td>{{ $serviceAssign->customer->fb_page_link ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Remarks</th>
                                    <td>{{ $serviceAssign->remarks ?? 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
            </section>
            {{-- Assigned Tasks --}}
            @if($serviceAssign->assignedTasks->count())
            <section class="invoice-box mt-4">
                <h4 class="fw-bold fs-2 mb-4">Assigned Tasks</h4>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Task Title</th>
                                <th>Notes</th>
                                <th>Status</th>
                                <th>Action</th>
                                <th>Completed At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($serviceAssign->assignedTasks as $index => $task)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $task->title }}</td>
                                <td>{{ $task->notes ?? 'N/A' }}</td>
                                <td>
                                    @if ($task->is_completed)
                                    <span class="badge bg-success">Completed</span>
                                    @else
                                    <span class="badge bg-warning">Incomplete</span>
                                    @endif
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('admin.tasks.toggle', $task->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        @if (!$task->is_completed)
                                        <button type="submit" class="btn btn-sm btn-primary">Mark as Complete</button>
                                        @else
                                        <button type="submit" class="btn btn-sm btn-warning">Mark as Incomplete</button>
                                        @endif
                                    </form>
                                </td>

                                <td>{{ $task->completed_at?->format('d M Y, h:i A') ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="max-w-xll mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg"
                    x-data="taskForm({{ $serviceAssign->id }})">
                    <h2 class="text-2xl font-bold mb-4">New Task</h2>
                    <form @submit.prevent="saveTasks">
                        <input type="hidden" name="">
                        <template x-for="(task, index) in tasks" :key="index">
                            <div class="mb-4 border p-3 rounded">
                                <div class="mb-2">
                                    <input type="text"
                                        class="w-full px-3 py-2 border rounded"
                                        placeholder="Task Title"
                                        x-model="task.title">
                                </div>
                                <div>
                                    <textarea class="w-full px-3 py-2 border rounded"
                                        placeholder="Notes (optional)"
                                        x-model="task.notes"></textarea>
                                </div>
                                <div class="text-right mt-2">
                                    <button type="button" @click="removeTask(index)"
                                        class="text-red-500 hover:text-red-700 font-bold text-xl">&times;</button>
                                </div>
                            </div>
                        </template>

                        <div class="flex justify-between">
                            <button type="button" @click="addTask"
                                class="px-4 py-2 bg-primary text-white rounded hover:bg-blue-600">Add More</button>
                            <button type="submit"
                                class="px-4 py-2 bg-success text-white rounded hover:bg-green-600">Save</button>
                        </div>
                    </form>
                </div>

            </section>
            @endif

<section class="mt-4">
    <div class="row">
        <div id="customerInfo" class="mt-3 w-full">
            <div class="bg-white shadow rounded-lg p-4 max-w-3xl mx-auto">
                <h2 class="text-xl font-semibold mb-4 border-b pb-2">Message Thread</h2>

                <div class="space-y-3 max-h-96 overflow-y-auto mb-4 pr-2">
                    @forelse ($messages->reverse() as $msg)
                        @php
                            $isOwn = $msg->sender_id === auth()->id();
                            $alignClass = $isOwn ? 'justify-end  ' : 'justify-start text-left';
                            $bgClass = $isOwn ? 'bg-blue-100' : 'bg-gray-100';
                            $roundedClass = $isOwn ? 'rounded-br-none' : 'rounded-bl-none';
                        @endphp

                        <div class="flex {{ $alignClass }}">
                            @unless($isOwn)
                                <img src="{{ $msg->sender->avatar ? asset($msg->sender->avatar ) : asset('default.png') }}"
                                     class="w-8 h-8 rounded-full mr-2 mt-2" alt="avatar">
                            @endunless

                            <div class="max-w-[75%] {{ $bgClass }} border p-3 rounded-lg {{ $roundedClass }}">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-semibold  text-gray-600">
                                        {{ ucfirst($msg->sender->role ?? 'User') }}
                                    </span>
                                    <span class=" ml-3 text-gray-500">
                                        {{ $msg->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <div class=" text-gray-800">{{ $msg->message }}</div>
                            </div>

                            @if($isOwn)
                                <img src="{{ auth()->user()->avatar ? asset(auth()->user()->avatar) : asset('default.png') }}"
                                     class="w-8 h-8 rounded-full ml-2 mt-2" alt="avatar">
                            @endif
                        </div>
                    @empty
                        <div class="text-gray-500 text-sm">No messages yet.</div>
                    @endforelse
                </div>

                <div class="mb-4">
                    {{ $messages->links() }} {{-- Laravel pagination links --}}
                </div>

                <form action="{{ route('messages.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="hidden" name="service_assign_id" value="{{ $serviceAssign->id }}">

                    <textarea name="message" rows="3" required
                              class="w-full p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 text-sm"
                              placeholder="Type your message..."></textarea>

                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        Send
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
    // alpine js
    function taskForm(serviceAssignId) {
        return {
            tasks: [{
                title: '',
                notes: ''
            }],
            addTask() {
                this.tasks.push({
                    title: '',
                    notes: ''
                });
            },
            removeTask(index) {
                this.tasks.splice(index, 1);
            },
            saveTasks() {
                const validTasks = this.tasks.filter(task => task.title.trim() !== '');
                console.log(validTasks);

                if (validTasks.length === 0) {
                    alert('Please enter at least one valid task title.');
                    return;
                }

                $.ajax({
                    url: `{{ route('admin.assign_task.store', ':id') }}`.replace(':id', serviceAssignId),
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        tasks: validTasks,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log(response);

                            location.reload();
                        } else {
                            alert('Failed to save tasks. Please try again.');
                        }
                    },
                    error: function(xhr) {
                        alert('Something went wrong. Please try again.');
                    }
                });
            }
        };
    }

    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2();

        // Initialize Summernote
        $('.summernote').summernote({
            height: 150,
        });

        $('#addEmployeeForm').on('submit', function(e) {
            e.preventDefault();
            // alert('Form submitted');

            $.ajax({
                url: "{{ route('admin_users.store') }}",
                // csrf
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        // Add new customer to dropdown
                        let option = new Option(response.user.name, response.user.id, true, true);
                        // $('select[name="customer_id"]').append(option).trigger('change');
                        $('select[name="employee_id"]').append(option).trigger('change');
                        // Update info box
                        // updateCustomerInfo(response.user);
                        // Close modal and reset form
                        $('#addEmployeeModal').modal('hide');
                        $('#addEmployeeForm')[0].reset();
                    }
                },
                error: function(xhr) {
                    alert('Something went wrong. Please try again.');
                }
            });
        });
    });
</script>
@endsection
