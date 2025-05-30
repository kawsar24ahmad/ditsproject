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
                        <div class="col-md-12 mb-2 table-responsive">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th style="width: 200px;">Invoice No</th>
                                        <td>{{ $serviceAssign->invoice->invoice_number }}</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 200px;">Customer Name</th>
                                        <td>{{ $serviceAssign->customer->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Customer Email</th>
                                        <td>{{ $serviceAssign->customer->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Customer Number</th>
                                        <td>{{ $serviceAssign->customer->phone ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Service Name</th>
                                        <td>{{ $serviceAssign->service->title ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Service Price</th>
                                        <td>{{ $serviceAssign->price ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Paid</th>
                                        <td>{{ $serviceAssign->paid_payment ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Due</th>
                                        <td>{{ $serviceAssign->price - $serviceAssign->paid_payment  }}</td>
                                    </tr>
                                    <tr>
                                        <th>Remarks</th>
                                        <td>{!! $serviceAssign->remarks ?? 'N/A' !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td><span class="badge badge-{{ $serviceAssign->status == 'completed' ? 'success' : ($serviceAssign->status == 'pending' ? 'warning' : 'secondary') }}">{{ ucfirst($serviceAssign->status) }}</span></td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td>{{ \Carbon\Carbon::parse($serviceAssign->created_at)->format('d M, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated at</th>
                                        <td>{{ \Carbon\Carbon::parse($serviceAssign->updated_at)->format('d M, Y') }}</td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>

                        <div class="my-4">
                            <p><span class="badge badge-{{ $serviceAssign->invoice->status == 'paid' ? 'success' : 'danger' }}" style="font-size: 40px;">{{ strtoupper($serviceAssign->invoice->status)}}</span></p>
                        </div>


                        <div class="col-md-4 mb-2">
                            <label>New Payment</label>
                            <input type="text" class="form-control" name="new_payment">
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
                            <label> Comment</label>
                            <input type="text" class="form-control" name="comment">
                        </div>



                        <div class="col-12 text-right">
                            <button type="submit" class="btn btn-primary">Update</button>
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


            <section class="mt-4 px-4 ">
                <div class="max-w-4xl mx-auto bg-white shadow-xl rounded-2xl p-6 border border-gray-200 ">
                    <div id="customerInfo" class="mt-3 w-full">
                        <div class="bg-white shadow rounded-lg p-4 max-w-3xl mx-auto">
                            <h2 class="text-xl font-semibold mb-4 border-b pb-2">Message Thread</h2>

                            <div class="space-y-3 max-h-96 overflow-y-auto mb-4 pr-2">
                                @forelse ($messages->reverse() as $msg)
                                @php
                                $isOwn = $msg->sender_id === auth()->id();
                                $alignClass = $isOwn ? 'justify-end ' : 'justify-start text-left';
                                $bgClass = $isOwn ? 'bg-blue-100' : 'bg-gray-100';
                                $roundedClass = $isOwn ? 'rounded-br-none' : 'rounded-bl-none';
                                @endphp

                                <div class="flex {{ $alignClass }}">
                                    @unless($isOwn)
                                    <img src="{{ $msg->sender->avatar ? asset($msg->sender->avatar) : asset('default.png') }}"
                                        class="w-8 h-8 rounded-full mr-2 mt-2" alt="avatar">
                                    @endunless

                                    <div class="max-w-[75%] {{ $bgClass }} border p-3 rounded-lg {{ $roundedClass }} my-2">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="font-semibold  text-gray-600">
                                                {{ ucfirst($msg->sender->name ?? 'User') }}
                                            </span>
                                            <span class=" ml-3 text-gray-500">
                                                {{ $msg->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        <div class=" text-gray-800">{{ $msg->message }}</div>
                                    </div>

                                    @if($isOwn)
                                    <img src="{{ auth()->user()->avatar? asset(auth()->user()->avatar) : asset('default.png') }}"
                                        class="w-8 h-8 rounded-full ml-2  mt-2" alt="avatar">
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
</script>
@endsection
