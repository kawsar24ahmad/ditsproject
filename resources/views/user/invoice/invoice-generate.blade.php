<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Invoice #6</title>

    <style>
        html,
        body {
            margin: 10px;
            padding: 10px;
            font-family: sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        p,
        span,
        label {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0px !important;
        }

        table thead th {
            height: 28px;
            text-align: left;
            font-size: 16px;
            font-family: sans-serif;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 14px;
        }

        .heading {
            font-size: 24px;
            margin-top: 12px;
            margin-bottom: 12px;
            font-family: sans-serif;
        }

        .small-heading {
            font-size: 18px;
            font-family: sans-serif;
        }

        .total-heading {
            font-size: 18px;
            font-weight: 700;
            font-family: sans-serif;
        }

        .order-details tbody tr td:nth-child(1) {
            width: 20%;
        }

        .order-details tbody tr td:nth-child(3) {
            width: 20%;
        }

        .text-start {
            text-align: left;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .company-data span {
            margin-bottom: 4px;
            display: inline-block;
            font-family: sans-serif;
            font-size: 14px;
            font-weight: 400;
        }

        .no-border {
            border: 1px solid #fff !important;
        }

        .bg-blue {
            background-color: #414ab1;
            color: #fff;
        }

        th {
            text-align: left;
        }
        .invoice-status {
    padding: 10px 20px;
    font-size: 40px;
    font-weight: bold;
    border-radius: 8px;
    color: #fff;
    display: inline-block;
    text-transform: uppercase;
}

.invoice-status.paid {
    background-color: #28a745; /* Green */
    box-shadow: 0 0 10px rgba(40, 167, 69, 0.5);
}

.invoice-status.unpaid {
    background-color: #dc3545; /* Red */
}
.invoice-status.partial {
    background-color: #dc3545; /* Red */
}
.custom-badge {
    padding: 5px 12px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    color: #fff;
    display: inline-block;
    text-transform: capitalize;
}

.custom-badge.completed {
    background-color: #28a745; /* Green */
}

.custom-badge.pending {
    background-color: #ffc107; /* Yellow */
    color: #212529;
}

.custom-badge.in_progress
{
    background-color: #6c757d; /* Gray */
}

    </style>

</head>

<body>

    <table class="order-details">
        <thead>
            <tr>
                <th width="50%" colspan="2">
                    <h2 class="text-start">{{ strtoupper(env('APP_NAME')) }}</h2>
                </th>
                <th width="50%" colspan="2" class="text-end company-data">
                    <span>Invoice Id: {{ $service->invoice->invoice_number }}</span> <br>
                    <span>Customer Email: {{ $service->customer->email }}</span> <br>

                <span>Customer Number:
                {{ $service->customer->phone ?? 'N/A' }}</span> <br>
                    <span>Date: {{ date('d M Y') }}</span> <br>
                </th>
            </tr>
            <tr class="bg-blue">
                <th  colspan="4">Order Details</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>Invoice No</th>
                <td>{{ $service->invoice->invoice_number }}</td>
            </tr>

            <tr>
                <th>Service Name</th>
                <td>{{ $service->service->title ?? 'N/A' }}</td>

                <th>Service Price</th>
                <td>{{ $service->price ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Total Paid</th>
                <td>{{ $service->paid_payment ?? 'N/A' }}</td>

                <th>Total Due</th>
                <td>{{ $service->price - $service->paid_payment  }}</td>
            </tr>
            <tr>
                <th>Remarks</th>
                <td>{{ $service->remarks ?? 'N/A' }}</td>

                <th>Status</th>
                <td>
                    <span class="custom-badge {{ $service->status }}">
                        {{ ucfirst($service->status) }}
                    </span>
                </td>

            </tr>
            <tr>
                <th>Created At</th>
                <td>{{ \Carbon\Carbon::parse($service->created_at)->format('d M, Y') }}</td>

                <th>Updated at</th>
                <td>{{ \Carbon\Carbon::parse($service->updated_at)->format('d M, Y') }}</td>
            </tr>
        </tbody>
    </table>



  {{-- Payment History --}}
  @if($payments->count())
                <table class="table table-bordered" style="margin-top: 20px;">
                    <thead>
                        <tr>
                            <th class="no-border text-start heading" colspan="5">Payment History</th>
                        </tr>
                        <tr class="bg-blue">
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
                            <td>{{ $payment->amount }}tk</td>
                            <td>{{ $payment->payment_method  ?? "---"  }}</td>
                            <td>{{ $payment->comment ?? "---" }}</td>
                            <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('d M, Y h:i A') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            @endif
            <div class="my-4">
    <p style="text-align:center">
        <span class="invoice-status {{ $service->invoice->status }}">
            {{ strtoupper($service->invoice->status) }}
        </span>
    </p>
</div>


    <br>
    <p class="text-center">
        Thank your for shopping with {{ strtoupper(env('APP_NAME')) }}
    </p>

</body>

</html>
