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


                        <div class="col-md-12 mb-3">
                            <label for="employee_id" class="form-label">Employee</label>
                            <div class="row g-2 align-items-end">
                                <div class="col-md-9">
                                    <select name="employee_id" class="form-control select2" id="employee_id">
                                        <option value="">Not Assigned</option>
                                        @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ $serviceAssign->employee_id == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <a href="javascript:void(0);" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                                        + Add Employee
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <label>Remarks</label>
                            <textarea name="remarks" class="form-control summernote">{!! $serviceAssign->remarks !!}</textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label>Delivery Date</label>
                            <input type="date" name="delivery_date" class="form-control"
                                value="{{ old('delivery_date', $serviceAssign->delivery_date ? \Carbon\Carbon::parse($serviceAssign->delivery_date)->format('Y-m-d') : '') }}">
                        </div>
                         <div class="col-12 text-right">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
                </form>
            </section>


            <section class="invoice-box mt-4">
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
                                    <form action="{{ route('admin.service-assigns.updatePaidAmount', $serviceAssign->id) }}" method="POST">
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

            <section class="invoice-box mt-4">

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


            {{-- Payment History --}}
            @if($payments->count())
            <section class="invoice-box mt-4">
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

            @if($serviceAssign->assignedTasks->count())
            <section class="invoice-box mt-4">
                <h4>Assigned Tasks</h4>
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
            </section>
            @endif
        </div>
    </div>
</div>
<!-- Add Employee Modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModal" aria-hidden="true">
    <div class="modal-dialog">
        <form id="addEmployeeForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCustomerModalLabel">Add New Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Mobile <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <input type="hidden" name="role" value="employee">
                    <input type="hidden" name="status" value="active">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Employee</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
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
