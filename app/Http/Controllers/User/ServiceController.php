<?php

namespace App\Http\Controllers\User;

use App\Models\Service;
use App\Models\FacebookAd;
use Illuminate\Http\Request;
use App\Models\ServicePurchase;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ServiceController extends Controller
{
    public function show(string $id)
    {
        $service = Service::findOrFail($id);

        switch ($service->type) {
            case 'form':
                return view( 'user.services.form', compact('service'));

            default:
                abort(404);
        }
    }

    public function buyFacebookAdService(Request $request)
    {

        $validated = $request->validate([
            'page_link' => 'required|url',
            'budget' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:1',
            'duration' => 'nullable|integer',
            'min_age' => 'nullable|integer',
            'max_age' => 'nullable|integer',
            'location' => 'nullable|string',
            'button' => 'nullable|string',
            'greeting' => 'nullable|string',
            'url' => 'required_if:button,book_now,learn_more,shop_now,sign_up|nullable|url',
            'number' => 'required_if:button,call_now|nullable|string',
            'facebook_page_id' => 'nullable',
        ]);

        $user = auth()->user();

        if ($user->wallet_balance < $request->price) {
            return back()->with('error', 'You do not have enough balance.');
        }

        DB::beginTransaction();

        try {
            // Update user
            $user->wallet_balance -= $request->price;
            $user->save();

            // Wallet transaction
            $transaction = WalletTransaction::create([
                'user_id' => $user->id,
                'amount' => $request->price,
                'type' => 'payment',
                'method' => 'wallet',
                'transaction_id' => uniqid(),
                'purpose' => 'Facebook Ad Service Purchase',
                'description' => 'Ad for page: ' . $request->page_link,
                'status' => 'pending',
            ]);

            // Service purchase record
            ServicePurchase::create([
                'user_id' => $user->id,
                'service_id' => $request->service_id,
                'price' => $request->price,
                'wallet_transaction_id' => $transaction->id,
                'status' => 'pending',
            ]);


            // Facebook ad
            FacebookAd::create([
                'user_id' => $user->id,
                'facebook_page_id' => $request->facebook_page_id,
                'wallet_transaction_id' => $transaction->id,
                'page_link' => $request->page_link,
                'budget' => $request->budget,
                'duration' => $request->duration,
                'min_age' => $request->min_age,
                'max_age' => $request->max_age,
                'location' => $request->location,
                'button' => $request->button,
                'greeting' => $request->greeting,
                'price' => $request->price,
                'status' => 'pending',
                'url' => $request->url,
                'number' => $request->number,
            ]);

            DB::commit();

            return redirect()->route('user.dashboard')->with('success', 'Your Facebook ad request has been submitted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }



}
