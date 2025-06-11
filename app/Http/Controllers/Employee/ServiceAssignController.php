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
       $customers = User::whereIn('role', ['customer', 'user'])->orderByDesc('id')->get();
        $employees = User::where('role', 'employee')->get();
        $services = Service::all();
        // dd($customers);
        return view('employee.invoice.create', compact('customers', 'services', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
  public function store(Request $request)
    {
        // Validate request
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'employee_id' => 'nullable|exists:users,id',
            'paid_payment' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string|max:1000',
        ]);
        // dd($validatedData);

        // Get the service price from the DB (prevent tampering)
        $service = Service::findOrFail($request->service_id);
        $price = $service->offer_price > 0 ? $service->offer_price : $service->price;


        $serviceAssign = $service->serviceAssign()->create([
            'customer_id' => $request->customer_id,
            'employee_id' => $request->employee_id,
            'price' => $price,
            'paid_payment' => $request->paid_payment,
            'remarks' => $validatedData['remarks'],
        ]);
        // Create service assignment
        // $serviceAssign = ServiceAssign::create([
        //     'customer_id' => $request->customer_id,
        //     'employee_id' => $request->employee_id,
        //     'service_id' => $request->service_id,
        //     'price' => $price,
        //     'paid_payment' => $request->paid_payment,
        //     'remarks' => $validatedData['remarks'],
        // ]);

        // $tasks = ServiceTask::where('service_id', $request->service_id)->get();
        $tasks = $service->load('tasks')->tasks;

        foreach ($tasks as $task) {
            $serviceAssign->assignedTasks()->create([
                'service_task_id' => $task->id,
                'title' => $task->title, // Add title explicitly
                'is_completed' => false,
            ]);
        }


        // Create invoice
        // Determine invoice status based on payment
        $status = 'unpaid';
        if ($request->paid_payment >= $price) {
            $status = 'paid';
        } elseif ($request->paid_payment > 0) {
            $status = 'partial';
        }
        $basePhone = $serviceAssign->customer->phone;
        $baseInvoice = 'INV-';

        // Count existing invoices for this customer
        $count = \App\Models\Invoice::where('invoice_number', 'like', $baseInvoice . '%-' . $basePhone)->count();

        // Generate 2-digit suffix
        $suffix = str_pad($count + 1, 2, '0', STR_PAD_LEFT);

        // Final invoice number
        $invoiceNumber = $baseInvoice . $suffix . '-' . $basePhone;

        // Create invoice
        $invoice = $serviceAssign->invoice()->create([
            'invoice_number' => $invoiceNumber,
            'total_amount' => $price,
            'paid_amount' => $request->paid_payment,
            'status' => $status,
        ]);





        // Log payment history
        $invoice->paymentHistory()->create([
            'amount' => $request->paid_payment,
            'payment_method' => null,
            'comment' => null,
            'paid_at' => now(),
        ]);

        // if(auth()->user()->role == 'employee'){
        //      return redirect()->route('employee.dashboard')->with('success', 'Service assigned and invoice created successfully!');
        // }
        return redirect()->route('employee.dashboard')->with('success', 'Service assigned and invoice created successfully!');
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
