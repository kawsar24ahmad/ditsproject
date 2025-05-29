<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Models\ServiceAssign;
use App\Notifications\NewMessageNotification;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'service_assign_id' => 'required|exists:service_assigns,id',
            'message' => 'required|string|max:2000',
        ]);

        $message = Message::create([
            'service_assign_id' => $request->service_assign_id,
            'sender_id' => auth()->id(),
            'message' => $request->message,
        ]);


        $serviceAssign = ServiceAssign::with(['customer', 'employee'])->find($request->service_assign_id);

        // Step 1: সম্ভাব্য রিসিপিয়েন্ট
        $recipients = collect([
            $serviceAssign->customer,
            $serviceAssign->employee,
        ]);

        // Step 2: যদি কোনো admin দেখতে পারে, তাকেও যুক্ত করো
        $adminUsers = User::where('role', 'admin')->get();

        $recipients = $recipients->merge($adminUsers);

        // Step 3: ডুপ্লিকেট ও পাঠানো ব্যক্তি বাদ দাও
        $recipients = $recipients
            ->filter(function ($user) {
                return $user && $user->id !== auth()->id(); // sender বাদ
            })
            ->unique('id');

        // Step 4: notify everyone
        foreach ($recipients as $user) {
            $user->notify(new NewMessageNotification($message));
        }



        return back()->with('success', 'Message sent successfully.');
    }
}
