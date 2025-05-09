<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\WalletTransaction;
use App\Http\Controllers\Controller;

class WalletController extends Controller
{
    public function index()
    {
        $transactions = WalletTransaction::where('user_id', auth()->id())
                            ->latest()->take(10)->get();

        return view('user.wallet.index', [
            'transactions' => $transactions,
            'balance' => auth()->user()->wallet_balance
        ]);
    }

    public function recharge(Request $request)
    {
        // Temporary for manual recharge
        $request->validate([
            'amount' => 'required|numeric|min:10',
            'sender_number' => 'required|string|max:15',
            'transaction_id' => 'required|string|max:50',
            'payment_method' => 'required|string|max:50',
        ]);

        WalletTransaction::create([
            'user_id' => auth()->id(),
            'amount' => $request->amount,
            'type' => 'recharge',
            'method' => 'manual',
            'payment_method' => $request->payment_method,
            'sender_number' => $request->sender_number,
            'transaction_id' => $request->transaction_id,
            'description' => 'Manual recharge request with',
            'status' => 'pending'
        ]);

        return back()->with('success', 'Your recharge request has been submitted.');
    }
}
