<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentHistory;
use Illuminate\Http\Request;

class PaymentHistoryController extends Controller
{
    public function index()
    {
        $payments = PaymentHistory::latest()->get();

        return view('admin.payment_history.index', compact('payments'));
    }
}
