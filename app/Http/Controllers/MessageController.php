<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'service_assign_id' => 'required|exists:service_assigns,id',
            'message' => 'required|string|max:2000',
        ]);

        Message::create([
            'service_assign_id' => $request->service_assign_id,
            'sender_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return back()->with('success', 'Message sent successfully.');
    }
}
