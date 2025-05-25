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
                <a class="btn btn-danger me-2" href="{{ route('user.service_assigns.invoiceGeneratePdf', $serviceAssign->id) }}"><i class="fas fa-print"></i></a>
                <a class="btn btn-success" target="_blank" href="{{ route('user.service_assigns.invoiceGenerate', $serviceAssign->id) }}"><i class="fas fa-eye"></i> View Invoice</a>
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
                                <th>Notes</th>
                                <th>Status</th>
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

            <section class="mt-4">
                <div class="row">
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
                                        class="w-8 h-8 rounded-full mr-2" alt="avatar">
                                    @endunless

                                    <div class="max-w-[75%] {{ $bgClass }} border p-3 rounded-lg {{ $roundedClass }}">
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
                                    <img src="{{ auth()->user()->avatar ? asset(auth()->user()->avatar) : asset('default.png') }}"
                                        class="w-8 h-8 rounded-full ml-2" alt="avatar">
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
