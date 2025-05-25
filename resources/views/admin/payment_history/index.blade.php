@extends('admin.layouts.app')

@section('content')

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">All Payments</h1>

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
            <th>No.</th>
            <th>Invoice No</th>
            <th>Amount</th>
            <th>Payment Method</th>
            <th>Comment</th>
            <th>Paid at</th>
        </tr>
    </thead>
    <tbody>
        @foreach($payments as $payment)
        <tr>
            <td>{{ $payment->id }}</td>
            <td>{{ $payment->invoice->invoice_number }}</td>
            <td>৳{{ $payment->amount }}</td>
            <td>{{ ucfirst($payment->method) }}</td>
            <td>{{ $payment->comment }}</td>
            <td>{{ $payment->paid_at }}</td>

        </tr>
        @endforeach
    </tbody>
</table>

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

@stop
