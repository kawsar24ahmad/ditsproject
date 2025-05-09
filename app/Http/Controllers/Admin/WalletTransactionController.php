<?php

namespace App\Http\Controllers\Admin;

use App\Mail\WalletRechargeRejectMail;
use Illuminate\Http\Request;
use App\Models\ServicePurchase;
use App\Models\WalletTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\WalletRechargeSuccessMail;

class WalletTransactionController extends Controller
{
    public function index()
    {
        $transactions = WalletTransaction::latest()->get();
        return view('admin.wallet.index', compact('transactions'));
    }

    public function update(Request $request, WalletTransaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        // আগে থেকেই অ্যাপ্রুভ হয়ে থাকলে
        if ($transaction->status == 'approved') {
            return back()->with('info', 'This transaction is already approved.');
        }

        // APPROVE হ্যান্ডলিং
        if ($request->status === 'approved') {
            if (in_array($transaction->type, ['recharge', 'bonus', 'refund'])) {
                // ইউজারের ওয়ালেটে টাকা যোগ করো
                $transaction->user->increment('wallet_balance', $transaction->amount);
                // ✅ Send recharge success email
                if ($transaction->type === 'recharge') {
                    Mail::to($transaction->user->email)
                        ->send(new WalletRechargeSuccessMail($transaction->user, $transaction));
                }
            }

            // যদি সার্ভিস পেমেন্ট হয়
            if ($transaction->type === 'payment') {
                $purchase = ServicePurchase::with('user')->where('wallet_transaction_id', $transaction->id)->first();
                if ($purchase) {
                    $purchase->user->role = 'customer';
                    $purchase->user->save();
                    $purchase->update([
                        'status' => 'approved',
                        'approved_at' => now(),
                    ]);
                }
            }
        }

        // REJECT হ্যান্ডলিং
        if ($request->status === 'rejected') {
            if ($transaction->type === 'payment') {
                // টাকা রিফান্ড
                $transaction->user->increment('wallet_balance', $transaction->amount);

                $purchase = ServicePurchase::where('wallet_transaction_id', $transaction->id)->first();
                if ($purchase) {
                    $purchase->update([
                        'status' => 'rejected',
                    ]);
                }
            }
            if ($transaction->type == "recharge") {
                try {
                    Mail::to($transaction->user->email)->send(
                        new WalletRechargeRejectMail($transaction->user, $transaction)
                    );
                } catch (\Exception $e) {
                    return back()->with('error', 'Recharge rejection email failed: ' . $e->getMessage());
                }

            }
        }

        // ফাইনালি ট্রানজেকশন আপডেট
        $transaction->update(['status' => $request->status]);

        return back()->with('success', 'Transaction updated successfully.');
    }


}
