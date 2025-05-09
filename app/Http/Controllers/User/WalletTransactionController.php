<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\WalletTransaction;
use App\Http\Controllers\Controller;

class WalletTransactionController extends Controller
{
    public function index()
    {
        $transactions = WalletTransaction::where('user_id', auth()->id())
                            ->latest()->take(10)->get();

        return view('user.transaction.index', [
            'transactions' => $transactions,
            'balance' => auth()->user()->wallet_balance
        ]);
    }
}
