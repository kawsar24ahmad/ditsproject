<?php

namespace App\Http\Controllers\Employee;

use App\Models\User;
use App\Models\Message;
use App\Models\Service;
use App\Models\AssignedTask;
use Illuminate\Http\Request;
use App\Models\ServiceAssign;
use App\Models\PaymentHistory;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;

class ServiceAssignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $serviceAssignments =  ServiceAssign::with(['customer:id,name', 'employee:id,name', 'assignedTasks.task', 'invoice:id,invoice_number,service_assign_id'])
        ->where('employee_id',auth()->user()->id)
        ->orderByDesc('id')
        ->paginate(10);

        $assignIds = $serviceAssignments->pluck('id');


        return view('employee.invoice.index', compact('serviceAssignments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customers = User::whereIn('role', ['customer', 'user'])->orderByDesc('id')->get();
        $employees = User::where('role', 'employee')->get();
        $services = Service::all();
        $serviceAssign = ServiceAssign::with('invoice', 'customer', 'taskReports')->findOrFail($id);



        $payments = collect(); // default empty collection
        if ($serviceAssign->invoice) {
            $payments = PaymentHistory::where('invoice_id', $serviceAssign->invoice->id)->get();
        }

        $messages = Message::where('service_assign_id', $serviceAssign->id)
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('employee.invoice.edit', compact('serviceAssign', 'customers', 'employees', 'services', 'payments', 'messages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $serviceAssign = ServiceAssign::findOrFail($id);

        // Validate request
        $request->validate([
            'new_payment' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:100',
            'comment' => 'nullable|string|max:1000',
            'remarks' => 'nullable|string|max:1000',
        ]);

        // Allow remarks update
        $serviceAssign->remarks = $request->remarks;
        $serviceAssign->save();

        // If new payment is added
        if ($request->filled('new_payment') && $request->new_payment > 0) {
            // Update paid payment
            $serviceAssign->paid_payment += $request->new_payment;
            $serviceAssign->save();

            // Determine new payment status based on service price
            $price = $serviceAssign->offer_price > 0 ? $serviceAssign->offer_price : $serviceAssign->price;
            $status = $serviceAssign->paid_payment >= $price ? 'paid' : 'partial';

            // Update invoice
            $invoice = $serviceAssign->invoice;
            $invoice->paid_amount += $request->new_payment;
            $invoice->status = $status;
            $invoice->save();

            // Log payment history
            PaymentHistory::create([
                'invoice_id' => $invoice->id,
                'amount' => $request->new_payment,
                'payment_method' => $request->payment_method,
                'comment' => $request->comment,
                'paid_at' => now(),
            ]);
        }

        return back()->with('success', 'Payment and remarks updated successfully!');
    }



    public function toggle(string $id)
    {
        $task = AssignedTask::findOrFail($id);
        if (!$task) {
            return back()->with('error', 'Task is not found');
        }
        $task->is_completed = !$task->is_completed;
        $task->completed_at =  $task->is_completed ? now() : null;
        $task->save();
        $serviceAssign = $task->serviceAssign;

         // Check if all assigned tasks under this serviceAssign are completed
        $allCompleted = $serviceAssign->assignedTasks()->where('is_completed', false)->doesntExist();

        $serviceAssign->status = $allCompleted ? 'completed' : 'in_progress';
        $serviceAssign->save();

        return back();
    }
    public function invoiceGenerate($id)
    {

        $service = ServiceAssign::with('invoice')->findOrFail($id);
        $payments = PaymentHistory::where('invoice_id', $service->invoice->id)->get();
        return view('user.invoice.invoice-generate', compact('service', 'payments'));
    }
    public function invoiceGeneratePdf($id)
    {
        $service = ServiceAssign::with('invoice')->findOrFail($id);

        if (!$service->invoice) {
            abort(404, 'Invoice not found');
        }

        $payments = PaymentHistory::where('invoice_id', $service->invoice->id)->get();

        $pdf = Pdf::loadView('user.invoice.invoice-generate', compact('service', 'payments'));

        return $pdf->download('invoice_' . $service->invoice->invoice_number . '.pdf');
    }

}
