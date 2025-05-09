@extends('user.layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid py-4">

            <h2 class="mb-4"><i class="fas fa-wallet"></i> My Transactions</h2>

            <div class="row">



                <!-- Transaction History -->
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-dark text-white">
                            <strong>📜 Transaction History</strong>
                        </div>
                        <div class="card-body p-0 table-responsive">
                            <table class="table table-bordered table-hover m-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Sender Info</th>
                                        <th>Method</th>
                                        <th>Payment Method</th>
                                        <th>Transaction ID</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $txn)
                                    <tr>
                                        <td>{{ $txn->created_at->format('d M, Y h:i A') }}</td>
                                        <td>{{ ucfirst($txn->type) }}</td>
                                        <td>{{ $txn->sender_number }}</td>
                                        <td>{{ ucfirst($txn->method) }}</td>
                                        <td>{{ ucfirst($txn->payment_method) }}</td>
                                        <td>{{ $txn->transaction_id }}</td>
                                        <td>৳{{ $txn->amount }}</td>
                                        <td>
                                            @if($txn->status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                            @elseif($txn->status == 'approved')
                                            <span class="badge badge-success">Approved</span>
                                            @else
                                            <span class="badge badge-danger">Rejected</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No transactions found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
</div>

@endsection
