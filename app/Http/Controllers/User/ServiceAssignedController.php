<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Message;
use App\Models\Service;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use App\Models\ServiceAssign;
use App\Models\PaymentHistory;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ServiceAssignedController extends Controller
{
    public function show($id)
    {
        $serviceAssign = ServiceAssign::with('invoice', 'customer')->where('customer_id', auth()->user()->id)->findOrFail($id);

        $payments = collect(); // default empty collection
        if ($serviceAssign->invoice) {
            $payments = PaymentHistory::where('invoice_id', $serviceAssign->invoice->id)->get();
        }

        // Paginate messages manually
        $messages = Message::where('service_assign_id', $serviceAssign->id)
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->paginate(10);



        return view('user.invoice.show', compact('serviceAssign', 'payments','messages'));
    }
    public function invoiceGenerate($id)
    {

        $service = ServiceAssign::with('invoice')->where('customer_id', auth()->user()->id)->findOrFail($id);
        $payments = PaymentHistory::where('invoice_id', $service->invoice->id)->get();
        return view('user.invoice.invoice-generate', compact('service', 'payments'));
    }
    public function invoiceGeneratePdf($id)
    {
        $service = ServiceAssign::with('invoice')->where('customer_id', Auth::id())->findOrFail($id);

        if (!$service->invoice) {
            abort(404, 'Invoice not found');
        }

        $payments = PaymentHistory::where('invoice_id', $service->invoice->id)->get();

        $pdf = Pdf::loadView('user.invoice.invoice-generate', compact('service', 'payments'));

        return $pdf->download('invoice_' . $service->invoice->invoice_number . '.pdf');
    }
}
