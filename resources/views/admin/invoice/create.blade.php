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
                <h3 class="content-header-title">Assign Service & Generate Invoice</h3>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Service Assignment</li>
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
                <form action="{{ route('admin.service_assigns.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label>Customer <span class="text-danger">*</span></label>
                            <select name="customer_id" class="form-control select2" required>
                                <option value="">-- Select Customer --</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-2">
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

                        <div class="col-md-4 mb-2">
                            <label>Employee (optional)</label>
                            <select name="employee_id" class="form-control select2">
                                <option value="">Not Assigned</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>

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

                        <div class="col-12 text-right">
                            <button type="submit" class="btn btn-primary">Assign & Create Invoice</button>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
    $(document).ready(function () {
        $('.select2').select2();
        $('.summernote').summernote({
            placeholder: 'Write remarks here...',
            height: 100
        });

        $('#service_id').on('change', function () {
            const price = $(this).find(':selected').data('price') || 0;
            $('#price').val(price);
            const advance = parseFloat($('#advance').val()) || 0;
            $('#due').val(price - advance);
        });

        $('#advance').on('input', function () {
            const price = parseFloat($('#service_id').find(':selected').data('price')) || 0;
            const advance = parseFloat($(this).val()) || 0;
            $('#due').val(price - advance);
        });
    });
</script>
@endsection
