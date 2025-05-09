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
                <form action="{{ route('admin.service_assigns.update', $serviceAssign->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label>Customer</label>
                            <select name="customer_id" class="form-control select2" required>
                                @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ $serviceAssign->customer_id == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label>Service</label>
                            <select name="service_id" class="form-control" id="service_id" required>
                                @foreach($services as $service)
                                <option value="{{ $service->id }}" data-price="{{ $service->price }}" {{ $serviceAssign->service_id == $service->id ? 'selected' : '' }}>
                                    {{ $service->title }} - ৳{{ $service->price }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label>Employee</label>
                            <select name="employee_id" class="form-control select2">
                                <option value="">Not Assigned</option>
                                @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ $serviceAssign->employee_id == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label>Total Price</label>
                            <input type="text" id="price" class="form-control" readonly value="{{ $serviceAssign->price }}">
                        </div>

                        <div class="col-md-4 mb-2">
                            <label>Total Paid</label>
                            <input type="text" class="form-control" readonly value="{{ $serviceAssign->paid_payment }}">
                        </div>

                        <div class="col-md-4 mb-2">
                            <label>Due</label>
                            <input type="text" id="due" class="form-control" readonly value="{{ $serviceAssign->price - $serviceAssign->paid_payment }}">
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

                        <div class="col-12 mb-3">
                            <label>Remarks</label>
                            <textarea name="remarks" class="form-control summernote">{!! $serviceAssign->remarks !!}</textarea>
                        </div>

                        <div class="col-12 text-right">
                            <button type="submit" class="btn btn-primary">Update Assignment</button>
                        </div>
                    </div>
                </form>
            </section>

            {{-- Assigned Tasks --}}
            @if($serviceAssign->assignedTasks->count())
            <section class="invoice-box mt-4">
                <h4>Assigned Tasks</h4>
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
                            <td>{{ $task->task?->title }}</td>
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
            </section>
            @endif



            {{-- Payment History --}}
            @if($payments->count())
            <section class="invoice-box mt-4">
                <h4>Payment History</h4>
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
