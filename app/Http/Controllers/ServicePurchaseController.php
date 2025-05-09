<?php

namespace App\Http\Controllers;

use App\Mail\PurchaseApprovedMail;
use App\Models\FacebookAd;
use Illuminate\Http\Request;
use App\Models\ServicePurchase;
use Illuminate\Support\Facades\Mail;

class ServicePurchaseController extends Controller
{
    public function index()
    {
        // Fetch all service purchases for the authenticated user
        $purchases = ServicePurchase::with('service', 'walletTransaction')->latest()->paginate(10);

        return view('admin.service_purchases.index', compact('purchases'));
    }

    public function approve($id)
    {
        $service = ServicePurchase::with('user')->findOrFail($id);
        $service->status = 'approved';
        $service->approved_at = now();
        $service->walletTransaction()->update([
            'status' => 'approved',
        ]);
        $walletTransaction = $service->walletTransaction()->first(); // re-fetch it
        $walletTransactionId = $walletTransaction?->id;

        $facebookAd = FacebookAd::with('facebookPage')->where('wallet_transaction_id', $walletTransactionId)->first();

        if ($facebookAd) {
            $facebookAd->status = 'approved';
            $facebookAd->save();

            $facebookPage = $facebookAd->facebookPage;
            if ($facebookPage) {
                $facebookPage->status = 'active';
                $facebookPage->save();
            }
        }

        $service->user->role = 'customer';
        $service->user->save();
        $service->save();

        Mail::to($service->user->email)->send(new PurchaseApprovedMail($service->user, $service));

        return redirect()->route('admin.service.purchases')->with('success', 'Service purchase approved.');
    }

    public function reject($id)
    {
        $service = ServicePurchase::with('user')->findOrFail($id);
        $service->status = 'rejected';
        $service->user->wallet_balance += $service->price;
        $service->user->save();
        $service->walletTransaction()->update([
            'status' => 'rejected',
        ]);
        $walleteTransactionId = $service->walletTransaction->id;
        $facebookAd = FacebookAd::where('wallet_transaction_id', $walleteTransactionId)->first();
        if ($facebookAd) {
            $facebookAd->status = 'rejected';
            $facebookAd->save();
        }
        $service->save();

        return redirect()->route('admin.service.purchases')->with('error', 'Service purchase rejected.');
    }

    public function facebookAdRequests()
    {
        // Fetch all service purchases for the authenticated user
        $facebookAdRequests = FacebookAd::where('status', 'approved')->with('walletTransaction', 'user')->paginate(10);
        return view('admin.facebook_ad_requests.index', compact('facebookAdRequests'));
    }
    // destroy
    public function destroy($id)
    {
        $service = ServicePurchase::findOrFail($id);
        // dd($service);
        $service->delete();

        return redirect()->route('admin.service.purchases')->with('success', 'Service purchase deleted.');
    }



}
