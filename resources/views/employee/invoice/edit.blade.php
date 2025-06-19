@extends('employee.layouts.app')

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

            <section class="invoice-box mt-3 mx-4">
                <form action="{{ route('employee.service_assigns.update', $serviceAssign->id) }}" method="POST">
                    @csrf
                    @method('PUT')
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
                                        <td>
                                            <input type="text" name="customer[name]" class="form-control" value="{{ old('customer.name', $serviceAssign->customer->name ?? '') }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>
                                            <input type="email" name="customer[email]" class="form-control" value="{{ old('customer.email', $serviceAssign->customer->email ?? '') }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Phone:</th>
                                        <td>
                                            <input type="text" name="customer[phone]" class="form-control" value="{{ old('customer.phone', $serviceAssign->customer->phone ?? '') }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Facebook ID Link:</th>
                                        <td>
                                            <input type="text" name="customer[fb_id_link]" class="form-control" value="{{ old('customer.fb_id_link', $serviceAssign->customer->fb_id_link ?? '') }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Facebook Page Link:</th>
                                        <td>
                                            <input type="text" name="customer[fb_page_link]" class="form-control" value="{{ old('customer.fb_page_link', $serviceAssign->customer->fb_page_link ?? '') }}">
                                        </td>

                                    </tr>
                                    <tr>
                                        <th>Remarks</th>
                                        <td>{!! $serviceAssign->remarks ?? 'N/A' !!}</td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>

                        <div id="service-info" class="mt-3  table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th colspan="2">Service Info:</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th width="30%">Title:</th>
                                        <td>{{ $serviceAssign->service->title ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Price:</th>
                                        <td>{{ $serviceAssign->price ?? 'N/A' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Service Status:</th>
                                        <td><span class="badge badge-info">{{ strtoupper($serviceAssign->status)  }}</span></td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>






                        <div class="col-12 text-right">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                </form>
            </section>


            <section class="invoice-box mt-4 mx-4">
                <div id="service-info" class="mt-3  table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="2">Payment Information:</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th width="20%">Price:</th>
                                <td>{{ $serviceAssign->price ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Paid Amount:</th>
                                <td class="flex justify-between">
                                    <form action="{{ route('employee.service-assigns.updatePaidAmount', $serviceAssign->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <input type="text" name="paid_amount" class="form-control" value="{{ old('paid_amount', $serviceAssign->paid_payment ?? '0.00') }}">

                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </form>

                                </td>
                            </tr>
                            <tr>
                                <th>Due:</th>
                                <td>{{ $serviceAssign->price - $serviceAssign->paid_payment  }}</td>
                            </tr>
                            <tr>
                                <th>Payment Status:</th>
                                <td><span class="badge badge-{{ $serviceAssign->invoice->status == 'paid' ? 'success' : 'danger' }}" style="font-size: 40px;">{{ strtoupper($serviceAssign->invoice->status)}}</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>








            </section>
            {{-- Assigned Tasks --}}

            <section class="invoice-box mt-4 mx-4">

                <form action="{{ route('admin.service-assigns.addPayment', $serviceAssign->id) }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label>New Payment</label>
                            <input type="number" placeholder="Enter Amount" class="form-control" name="new_payment" required>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label for="payment_method">Payment Method</label>
                            <select name="payment_method" id="payment_method" class="form-control">
                                <option value="">-- Select Payment Method --</option>
                                <option value="bkash">Bkash</option>
                                <option value="nagad">Nagad</option>
                                <option value="manual">Manual</option>
                                <option value="bank">Bank</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label>Comment</label>
                            <input type="text" class="form-control" name="comment">
                        </div>

                        <div class="col-12 mt-2">
                            <button type="submit" class="btn btn-success">Add Payment</button>
                        </div>
                    </div>
                </form>

            </section>






            {{-- Assigned Tasks --}}
            @if($serviceAssign->assignedTasks->count())
            <section class="invoice-box mt-4 mx-4">
                <h4 class="text-2xl font-bold">Assigned Tasks</h4>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Task Title</th>
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
                                <td>
                                    @if ($task->is_completed)
                                    <span class="badge bg-success">Completed</span>
                                    @else
                                    <span class="badge bg-warning">Incomplete</span>
                                    @endif
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('employee.tasks.toggle', $task->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        @if (!$task->is_completed)
                                        <button type="submit" class="btn btn-sm btn-primary">Mark as Complete</button>
                                        @else
                                        <button type="submit" class="btn btn-sm btn-danger">Mark as Incomplete</button>
                                        @endif
                                    </form>
                                </td>

                                <td>{{ $task->completed_at?->format('d M Y, h:i A') ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="max-w-xll mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg"
                        x-data="taskForm({{ $serviceAssign->id }})">
                        <div class="text-end">
                            <button class="bg-blue text-white  py-2 px-4 rounded-lg" @click="open=!open" x-text="open ? 'Close':'Add New Task'"></button>
                        </div>
                        <div x-show="open">
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
                    </div>


                </div>
            </section>
            @endif

            <section class=" px-4 mt-5">
                <div class="max-w-4xl mx-auto bg-white shadow-xl rounded-2xl p-6 border border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-4">📝 Service Task Report</h2>

                    {{-- Existing Reports Table --}}
                    <div class="overflow-x-auto mb-8">
                        <table class="w-full table-fixed border border-gray-300 text-sm text-gray-800 rounded-md">
                            <thead class="bg-gray-100 text-2xl">
                                <tr>
                                    <th class="border border-gray-300 px-4 py-3 text-left font-semibold" style="width: 20%;">📅 Date</th>
                                    <th class="border border-gray-300 px-4 py-3 text-left font-semibold">🛠️ Work Details</th>
                                    <th class="border border-gray-300 px-4 py-3 text-left font-semibold">Worker</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white text-lg">
                                @forelse ($serviceAssign->taskReports as $report)
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-300 px-4 py-3 whitespace-nowrap w-32">
                                        {{ \Carbon\Carbon::parse($report->date)->format('d M, Y') }}
                                    </td>
                                    <td class="border border-gray-300 px-4 py-3">
                                        {!! $report->work_details !!}
                                    </td>
                                    <td class="border border-gray-300 px-4 py-3">
                                        {{$report->employee->name ?? 'N/A'}}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center text-gray-500 border px-4 py-4">No reports found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>


                    <div class="mt-5">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-4">📝 New Task Report</h2>
                        {{-- Submit New Report --}}
                        {{-- Global Error Summary --}}


                        <form action="{{ route('employee.service_tasks_reports.store') }}" method="POST" class="space-y-6" novalidate>
                            @csrf
                            <input type="hidden" name="service_assign_id" value="{{ $serviceAssign->id }}">
                            <input type="hidden" name="employee_id" value="{{ auth()->id() }}">

                            {{-- Date Input --}}
                            <div>
                                <label for="date" class="block text-lg font-medium text-gray-700 mb-1">📅 Report Date</label>
                                <input type="date" name="date" id="date"
                                    value="{{ old('date') }}"
                                    class="w-full rounded-md border px-4 py-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none @error('date') border-red-500 @else border-gray-300 @enderror"
                                    required>
                                @error('date')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Work Details --}}
                            <div>
                                <label for="work_details" class="block text-lg font-medium text-gray-700 mb-1">🛠️ Work Details</label>
                                <textarea name="work_details" id="work_details" rows="5"
                                    class="w-full rounded-md border px-4 py-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none summernote @error('work_details') border-red-500 @else border-gray-300 @enderror"
                                    placeholder="Describe the work done..." required>{{ old('work_details') }}</textarea>
                                @error('work_details')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Submit --}}
                            <div class="text-right">
                                <button type="submit"
                                    class="inline-flex items-center gap-2 px-6 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 shadow transition">
                                    🚀 Submit Report
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </section>


            <section class="mt-6 mx-4">
                <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-6">
                    <h2 class="text-2xl font-semibold mb-6 border-b pb-3">Message Thread</h2>

                    {{-- Message Container --}}
                    <div id="message-container" class="space-y-5 max-h-[400px] overflow-y-auto pr-4 pb-2" style="scroll-behavior: smooth;">
                        @forelse ($messages->reverse() as $msg)
                        @php
                        $isOwn = $msg->sender_id === auth()->id();
                        $alignClass = $isOwn ? 'justify-end' : 'justify-start';
                        $bgClass = $isOwn ? 'bg-blue-50 text-blue-900' : 'bg-gray-100 text-gray-900';
                        $roundedClass = $isOwn ? 'rounded-tr-none' : 'rounded-tl-none';
                        $fileExt = $msg->file ? pathinfo($msg->file, PATHINFO_EXTENSION) : '';
                        @endphp

                        <div class="flex {{ $alignClass }} items-start space-x-3">
                            {{-- Avatar --}}
                            @unless($isOwn)
                            <img width="100" src="{{ $msg->sender->avatar ? asset($msg->sender->avatar) : asset('default.png') }}"
                                alt="avatar" class="w-10 h-10 rounded-full flex-shrink-0" style="width: 48px; height: 48px; object-fit: cover; border-radius: 50%;">

                            @endunless

                            {{-- Message bubble --}}
                            <div class="max-w-[70%] {{ $bgClass }} p-4 rounded-lg {{ $roundedClass }} shadow-sm my-2">
                                <div class="flex items-center justify-between mb-1">
                                    <h3 class="font-semibold">{{ ucfirst($msg->sender->name ?? 'User') }}</h3>
                                    <time class="text-xs text-gray-500">{{ $msg->created_at->diffForHumans() }}</time>
                                </div>

                                <p class="mb-3 whitespace-pre-wrap break-words">{{ $msg->message }}</p>

                                @if ($msg->file)
                                @php
                                $fileUrl = asset($msg->file);
                                $fileName = basename($msg->file);
                                @endphp

                                @if(in_array($fileExt, ['jpg','jpeg','png','gif']))
                                <img width="200" height="300" src="{{ $fileUrl }}" alt="image" class="rounded-md shadow-md max-w-xs" />
                                @elseif(in_array($fileExt, ['mp4','webm','mov','avi']))
                                <video controls class="rounded-md shadow-md max-w-full h-auto">
                                    <source src="{{ $fileUrl }}" type="video/{{ $fileExt }}">
                                    Your browser does not support the video tag.
                                </video>
                                @endif
                                <a href="{{ $fileUrl }}" download="{{ $fileName }}"
                                    class="inline-block mt-2 px-4 py-2 text-blue-600 font-semibold rounded-md
                  hover:bg-blue-100 hover:text-blue-800 transition
                  focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50
                  "
                                    aria-label="Download file {{ $fileName }}">
                                    Download File ({{ strtoupper($fileExt) }})
                                </a>
                                @endif

                            </div>

                            @if($isOwn)
                            <img src="{{ auth()->user()->avatar ? asset(auth()->user()->avatar) : asset('default.png') }}"
                                alt="avatar"
                                style="width: 48px; height: 48px; object-fit: cover; border-radius: 50%;" />

                            @endif
                        </div>
                        @empty
                        <p class="text-center text-gray-500 italic">No messages yet.</p>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-6">
                        {{ $messages->links() }}
                    </div>

                    {{-- Message Form --}}
                    <div x-data="messageForm()" x-init="scrollToBottom()" class="mt-6 p-6 bg-gray-50 rounded-lg shadow-md">
                        <form action="{{ route('messages.store') }}" method="POST" enctype="multipart/form-data"
                            @submit="isSubmitting = true" x-ref="form" class="space-y-4">
                            @csrf
                            <input type="hidden" name="service_assign_id" value="{{ $serviceAssign->id }}">

                            <textarea name="message" rows="3" x-ref="messageInput"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 resize-none text-sm"
                                placeholder="Type your message..."></textarea>

                            <div class="flex items-center justify-between">
                                <label for="file-upload" class="flex items-center space-x-2 cursor-pointer text-gray-600 hover:text-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828L18 9.828a4 4 0 00-5.656-5.656L7.757 8.757a6 6 0 108.486 8.486" />
                                    </svg>
                                    <span class="text-sm">Attach file</span>
                                </label>
                                <input type="file" id="file-upload" name="file" class="hidden" @change="previewFile($event)">

                                <button type="submit" :disabled="isSubmitting"
                                    class="inline-flex items-center gap-2 px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold shadow hover:bg-blue-700 disabled:opacity-60 transition">
                                    <template x-if="isSubmitting">
                                        <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="white" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="white" d="M4 12a8 8 0 018-8v8H4z"></path>
                                        </svg>
                                    </template>
                                    <template x-if="!isSubmitting">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 10l9 9m0 0l9-9m-9 9V3" />
                                        </svg>
                                    </template>
                                    <span x-text="isSubmitting ? 'Sending...' : 'Send'"></span>
                                </button>
                            </div>

                            <div x-show="fileUrl" class="mt-3 space-y-2">
                                <template x-if="isImage(fileUrl)">
                                    <img :src="fileUrl" alt="Preview" class="max-w-xs rounded shadow-md" />
                                </template>
                                <template x-if="isVideo(fileUrl)">
                                    <video controls class="max-w-md rounded shadow-md">
                                        <source :src="fileUrl" :type="getMimeType(fileUrl)" />
                                    </video>
                                </template>
                                <template x-if="!isImage(fileUrl) && !isVideo(fileUrl)">
                                    <p class="text-gray-700">Selected file: <span x-text="getFileName(fileUrl)"></span></p>
                                </template>
                                <button type="button" @click="clearFile()" class="text-red-500 hover:underline text-sm">Clear File</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>

            {{-- Payment History --}}
            @if($payments->count())
            <section class="invoice-box my-4 mx-4 ">
                <h4>Payment History</h4>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Comment</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $index => $payment)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $payment->amount }}৳</td>
                                <td>{{ $payment->payment_method  ?? "---"  }}</td>
                                <td>{{ $payment->comment ?? "---" }}</td>
                                <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('d M, Y h:i A') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
            @endif
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
    // alpine js
    function taskForm(serviceAssignId) {
        return {
            open: false,
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
                    url: `{{ route('employee.assign_task.store', ':id') }}`.replace(':id', serviceAssignId),
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
        $('.select2').select2();
        $('.summernote').summernote({
            placeholder: 'Write remarks here...',
            height: 100
        });

        $('#service_id').on('change', function() {
            const price = $(this).find(':selected').data('price') || 0;
            $('#price').val(price);
            const paid = parseFloat("{{ $serviceAssign->paid_payment }}") || 0;
            $('#due').val(price - paid);
        });
    });


    function messageForm() {
        return {
            isSubmitting: false,
            fileUrl: null,
            fileInput: null, // To store the file input element

            init() {
                this.scrollToBottom();
                this.fileInput = this.$refs.form.querySelector('input[name="file"]');
            },

            previewFile(event) {
                const file = event.target.files[0];
                if (!file) {
                    this.fileUrl = null;
                    return;
                }

                if (this.fileUrl) {
                    URL.revokeObjectURL(this.fileUrl);
                }

                this.fileUrl = URL.createObjectURL(file);
            },

            clearFile() {
                if (this.fileUrl) {
                    URL.revokeObjectURL(this.fileUrl);
                }
                this.fileUrl = null;
                if (this.fileInput) {
                    this.fileInput.value = ''; // Clear the file input
                }
            },

            isImage(url) {
                return /\.(jpg|jpeg|png|gif)$/i.test(url);
            },

            isVideo(url) {
                return /\.(mp4|webm|mov|avi)$/i.test(url);
            },

            getMimeType(url) {
                const ext = url.split('.').pop().toLowerCase();
                switch (ext) {
                    case 'mp4':
                        return 'video/mp4';
                    case 'webm':
                        return 'video/webm';
                    case 'mov':
                        return 'video/quicktime';
                    case 'avi':
                        return 'video/x-msvideo';
                    default:
                        return '';
                }
            },

            getFileName(url) {
                if (!url) return '';
                const parts = url.split('/');
                return parts[parts.length - 1];
            },

            scrollToBottom() {
                const container = document.getElementById('message-container');
                if (container) {
                    // Use a slight delay to ensure content is rendered before scrolling
                    setTimeout(() => {
                        container.scrollTop = container.scrollHeight;
                    }, 100);
                }
            }
        }
    }
</script>
@endsection
