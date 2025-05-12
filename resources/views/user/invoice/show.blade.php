@extends('user.layouts.app')

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
            <div class="d-flex justify-content-end p-3">
                <a class="btn btn-danger me-2"  href="{{ route('user.service_assigns.invoiceGeneratePdf', $serviceAssign->id) }}">Generate Invoice as PDF</a>
                <a class="btn btn-success" target="_blank" href="{{ route('user.service_assigns.invoiceGenerate', $serviceAssign->id) }}">View Invoice</a>
            </div>
            {{-- Assigned Tasks --}}
            @if($serviceAssign->assignedTasks->count())
            <section class="invoice-box mt-4">
                <h4 class="fw-bold fs-2 mb-3">Service Tasks</h4>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Task Title</th>
                                <th>Status</th>
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


                                <td>{{ $task->completed_at?->format('d M Y, h:i A') ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
            @endif
            <section class="invoice-box mt-3">
                <div class="row">

                    <div class="col-md-12 mb-2 table-responsive">
                        <table class="table table-bordered table-striped">
                            <h4 class="fw-bold fs-2 mb-3">Invoice</h4>
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
                                    <td>{{ $serviceAssign->remarks ?? 'N/A' }}</td>
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



                </div>
            </section>



            {{-- Payment History --}}
            @if($payments->count())
            <section class="invoice-box mt-4">
                <h4 class="fw-bold fs-2 mb-3">Payment History</h4>
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
