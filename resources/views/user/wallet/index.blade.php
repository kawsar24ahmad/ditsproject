@extends('user.layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid py-4">

            <h2 class="mb-4"><i class="fas fa-wallet"></i> My Wallet</h2>

            <div class="row">

                <!-- Current Wallet Balance -->
                <div class="col-md-4 mb-4">
                    <div class="card text-center shadow-sm border-success">
                        <div class="card-body">
                            <h5 class="text-muted">Current Balance</h5>
                            <h2 class="text-success font-weight-bold mb-0">৳{{ $balance }}</h2>
                        </div>
                    </div>
                    <div class="card text-center shadow-sm border-success">
                    <div class="card-header bg-primary text-white">
                            <strong>💳 Online Recharge</strong>
                        </div>
                        <div class="card-body">
                            <form action="{{ url('/pay') }}" method="post">
                                @csrf
                                <input type="number" name="amount" class="form-control mt-2" placeholder="Enter Amount" required>
                                <button type="submit" class="btn btn-primary w-100 mt-2">Recharge Now</button>
                            </form>
                            <p class="mt-3 text-muted">Note: Minimum recharge amount is 10৳</p>
                        </div>
                    </div>
                </div>

                <!-- Recharge Form -->
                <div class="col-md-8 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <strong>💳 Manual Recharge</strong>
                        </div>
                        <div class="card-body">
                            @php
                            $settings = App\Models\SiteSetting::first();
                            @endphp

                            @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <p><strong>📢 Send money to this number:</strong></p>
                            <ul class="mb-3">
                                <li><strong>Bkash:</strong> {{ $settings->bkash_account_no ?? '01YYYYYYYYY' }} ({{ $settings->bkash_type ?? '' }})</li>
                                <li><strong>Nagad:</strong> {{ $settings->bkash_account_no ?? '01YYYYYYYYY' }} ({{ $settings->bkash_type ?? '' }})</li>
                                <li><strong>Bank:</strong> {{ $settings->bank_name ?? 'N/A' }}<br>
                                    Account Name: {{ $settings->account_name ?? 'N/A' }}<br>
                                    Account No:  {{ $settings->bank_account_no ?? 'N/A' }}<br>
                                    Branch:  {{ $settings->bank_branch ?? 'N/A' }}
                                </li>
                            </ul>
                            <p class="text-muted">✅ Send the amount first, then fill up this form for approval:</p>

                            <form action="{{ route('user.wallet.recharge') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="amount">Amount (Min 10৳)</label>
                                        <input type="number" name="amount" class="form-control" min="10" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="payment_method">Payment Method</label>
                                        <select name="payment_method" class="form-control" required>
                                            <option value="">Select One</option>
                                            <option value="bkash">Bkash</option>
                                            <option value="nagad">Nagad</option>
                                            <option value="bank">Bank</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="sender_number">Sender Number or Bank Account</label>
                                        <input type="text" name="sender_number" class="form-control" required placeholder="Ex: 01XXXXXXXXX or Your Bank A/C No">
                                    </div>

                                    <div class="form-group">
                                        <label for="transaction_id">Transaction/Reference ID</label>
                                        <input type="text" name="transaction_id" class="form-control" required>
                                    </div>



                                    <button type="submit" class="btn btn-success w-100">📩 Submit Recharge Request</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

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
