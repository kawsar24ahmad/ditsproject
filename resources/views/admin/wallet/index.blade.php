@extends('admin.layouts.app')

@section('content')

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">All Transactions</h1>

          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard v1</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
      <table class="table table-bordered">
    <thead>
        <tr>
            <th>User</th>
            <th>Amount</th>
            <th>Method</th>
            <th>Payment Method</th>
            <th>Sender</th>
            <th>TXN ID</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $txn)
        <tr>
            <td>{{ $txn->user->name }}</td>
            <td>৳{{ $txn->amount }}</td>
            <td>{{ ucfirst($txn->method) }}</td>
            <td>{{ ucfirst($txn->payment_method) }}</td>
            <td>{{ $txn->sender_number }}</td>
            <td>{{ $txn->transaction_id }}</td>
            <td>
                <span class="badge badge-{{ $txn->status == 'approved' ? 'success' : ($txn->status == 'pending' ? 'warning' : 'danger') }}">{{ ucfirst($txn->status) }}</span>
            </td>
            <td>
                @if($txn->status == 'pending')
                <form action="{{ route('admin.wallet.transactions.update', $txn->id) }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="status" value="approved">
                    <button class="btn btn-sm btn-success">Approve</button>
                </form>
                <form action="{{ route('admin.wallet.transactions.update', $txn->id) }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="status" value="rejected">
                    <button class="btn btn-sm btn-danger">Reject</button>
                </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

@stop
