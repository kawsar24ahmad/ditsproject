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
        <div class="content-header row">
            <div class="col-12">
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Sell Service</li>
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
                <h3 class="content-header-title fw-bold fs-2 mb-4">নতুন বিক্রয়</h3>
                <hr>

                <form action="{{ route('employee.service_assigns.store') }}" method="POST" class="mt-3">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label>Customer <span class="text-danger">*</span></label>
                            <div class="input-group gap-3 ">
                                <select name="customer_id" class="form-control select2 h-full" required>
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                                <a href="javascript:void(0);" class="btn btn-primary rounded-sm" data-bs-toggle="modal" data-bs-target="#addCustomerModal">Add New Customer</a>
                            </div>
                        </div>
                        <div id="customerInfo table-responsive" class="mt-3">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="30%">Name:</th>
                                        <td id="customer_name">-</td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td id="customer_email">-</td>
                                    </tr>
                                    <tr>
                                        <th>Phone:</th>
                                        <td id="customer_phone">-</td>
                                    </tr>
                                    <tr>
                                        <th>Facebook ID Link:</th>
                                        <td id="customer_fb_id">-</td>
                                    </tr>
                                    <tr>
                                        <th>Facebook Page Link:</th>
                                        <td id="customer_fb_page">-</td>
                                    </tr>
                                    <tr>
                                        <th>Starting Followers:</th>
                                        <td id="starting_followers">-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                        <div class="col-md-12 mb-2">
                            <label>Service <span class="text-danger">*</span></label>
                            <select name="service_id" class="form-control" id="service_id" required>
                                <option value="">-- Select Service --</option>
                                @foreach($services as $service)
                                <option value="{{ $service->id }}" data-price="{{ $service->offer_price ?? $service->price }}">
                                    {{ $service->title }}
                                    @if ($service->offer_price > 0)
                                    - ৳<span>{{ $service->offer_price }}</span>
                                    @else
                                    - ৳{{ $service->price }}
                                    @endif
                                </option>

                                @endforeach
                            </select>
                        </div>
<div class="col-md-12 mb-3">
                            <label for="employee_id" class="form-label">Employee</label>
                            <div class="row g-2 align-items-end">
                                <div class="col-md-9">
                                    <select name="employee_id" class="form-control select2" id="employee_id">
                                        <option value="">Not Assigned</option>
                                        @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" >
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
                        <!-- <div class="col-md-12 mb-2">
                            <label>Employee (optional)</label>
                            <select name="employee_id" class="form-control select2">
                                <option value="">Not Assigned</option>
                                @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div> -->

                        <div class="col-md-4 mb-2">
                            <label>Total Price</label>
                            <input type="text" id="price" class="form-control" readonly>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label>Paid Payment <span class="text-danger">*</span></label>
                            <input type="number" name="paid_payment" id="advance" class="form-control" min="0" value="0">
                        </div>

                        <div class="col-md-4 mb-2">
                            <label>Due Payment</label>
                            <input type="text" id="due" class="form-control" readonly>
                        </div>

                        <div class="col-12 mb-3">
                            <label>Remarks</label>
                            <textarea name="remarks" id="remarks" class="form-control summernote"></textarea>
                        </div>
                         <div class="col-12 mb-3">
                            <label>Delivery Date</label>
                            <input type="date" name="delivery_date" class="form-control" id="">
                        </div>

                        <div class="col-12 text-right">
                            <button type="submit" class="btn btn-primary">Assign & Create Invoice</button>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="addCustomerForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCustomerModalLabel">Add New Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Gmail <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>What's up Number <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Facebook ID Link <span class="text-danger"></span></label>
                        <input type="text" name="fb_id_link" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Facebook Page Link <span class="text-danger"></span></label>
                        <input type="text" name="fb_page_link" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Starting Followers <span class="text-danger">*</span></label>
                        <input type="text" name="starting_followers" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <input type="hidden" name="role" value="user">
                    <input type="hidden" name="status" value="active">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Customer</button>
                </div>
            </div>
        </form>
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
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
    $(document).ready(function() {
        $('#addEmployeeForm').on('submit', function(e) {
            e.preventDefault();
            // alert('Form submitted');

            $.ajax({
                url: "{{ route('employee_users.store') }}",
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
        $('#addCustomerForm').on('submit', function(e) {
            e.preventDefault();
            // alert('Form submitted');

            $.ajax({
                url: "{{ route('employee_users.store') }}",
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
                        $('select[name="customer_id"]').append(option).trigger('change');
                        // Update info box
                        updateCustomerInfo(response.user);
                        // Close modal and reset form
                        $('#addCustomerModal').modal('hide');
                        $('#addCustomerForm')[0].reset();
                    }
                },
                error: function(xhr) {
                    alert('Something went wrong. Please try again.');
                }
            });
        });
        $('.select2').select2();
        $('.summernote').summernote({
            placeholder: 'Write remarks here...',
            height: 100
        });

        $('#service_id').on('change', function() {
            const price = $(this).find(':selected').data('price') || 0;
            $('#price').val(price);
            const advance = parseFloat($('#advance').val()) || 0;
            $('#due').val(price - advance);
        });

        $('#advance').on('input', function() {
            const price = parseFloat($('#service_id').find(':selected').data('price')) || 0;
            const advance = parseFloat($(this).val()) || 0;
            $('#due').val(price - advance);
        });


        function updateCustomerInfo(user) {
            $('#customer_name').text(user.name || '-');
            $('#customer_email').text(user.email || '-');
            $('#customer_phone').text(user.phone || '-');
            $('#customer_fb_id').text(user.fb_id_link || '-');
            $('#customer_fb_page').text(user.fb_page_link || '-');
            $('#starting_followers').text(user.starting_followers || '-');
        }

        function removeCustomerInfo() {
            $('#customer_name').text('');
            $('#customer_email').text('');
            $('#customer_phone').text('');
            $('#customer_fb_id').text('');
            $('#customer_fb_page').text('');
            $('#starting_followers').text('');
        }
        $('select[name="customer_id"]').on('change', function() {
            const customer_id = $(this).val();
            if (!customer_id) {
                removeCustomerInfo();
                return;
            };
            // alert(customer_id);
            $.ajax({
                url: `{{ route('employee_users.show', ':id') }}`.replace(':id', customer_id),

                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        const user = response.user;
                        updateCustomerInfo(user);
                    } else {
                        alert('Customer not found.');
                    }
                    // updateCustomerInfo(user);
                },
                error: function() {
                    alert('Failed to fetch customer data.');
                }
            });
        });
    });
</script>
@endsection
