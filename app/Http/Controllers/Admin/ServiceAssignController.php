<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\ServiceTask;
use App\Models\AssignedTask;
use Illuminate\Http\Request;
use App\Models\ServiceAssign;
use App\Models\PaymentHistory;
use App\Http\Controllers\Controller;

use function PHPUnit\Framework\isEmpty;
use League\OAuth1\Client\Server\Server;

class ServiceAssignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $serviceAssignments =  ServiceAssign::with(['customer:id,name', 'employee:id,name', 'assignedTasks.task', 'invoice:id,invoice_number,service_assign_id', 'service:id,title'])
        ->orderByDesc('id')->get();
        return view('admin.invoice.index', compact('serviceAssignments'));
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
        return view('admin.invoice.create', compact('customers', 'services', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'employee_id' => 'nullable|exists:users,id',
            'paid_payment' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string|max:1000',
        ]);

        // Get the service price from the DB (prevent tampering)
        $service = Service::findOrFail($request->service_id);
        $price = $service->offer_price > 0 ? $service->offer_price : $service->price;


        // Create service assignment
        $serviceAssign = ServiceAssign::create([
            'customer_id' => $request->customer_id,
            'employee_id' => $request->employee_id,
            'service_id' => $request->service_id,
            'price' => $price,
            'paid_payment' => $request->paid_payment,
            'remarks' => $request->remarks,
        ]);

        $tasks = ServiceTask::where('service_id', $request->service_id)->get();
        foreach ($tasks as $task) {
            AssignedTask::create([
                'service_assign_id' => $serviceAssign->id,
                'service_task_id' => $task->id,
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

        // Create invoice
        $invoice = Invoice::create([
            'service_assign_id' => $serviceAssign->id,
            'invoice_number' => 'INV-' . str_pad($serviceAssign->id, 6, '0', STR_PAD_LEFT),
            'total_amount' => $price,
            'paid_amount' => $request->paid_payment,
            'status' => $status,
        ]);

        // Log payment history
        PaymentHistory::create([
            'invoice_id' => $invoice->id,
            'amount' => $request->paid_payment,
            'payment_method' => null,
            'comment' => null,
            'paid_at' => now(),
        ]);


        return redirect()->route('admin.service_assigns.index')->with('success', 'Service assigned and invoice created successfully!');
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
        $serviceAssign = ServiceAssign::with('invoice')->findOrFail($id);



        $payments = collect(); // default empty collection
        if ($serviceAssign->invoice) {
            $payments = PaymentHistory::where('invoice_id', $serviceAssign->invoice->id)->get();
        }



        return view('admin.invoice.edit', compact('serviceAssign', 'customers', 'employees', 'services', 'payments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $serviceAssign = ServiceAssign::findOrFail($id);

        // Validate request
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'employee_id' => 'nullable|exists:users,id',
            'new_payment' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:100',
            'comment' => 'nullable|string|max:1000',
            'remarks' => 'nullable|string|max:1000',
        ]);

        // Fetch service price
        $service = Service::findOrFail($request->service_id);
        $price = $service->offer_price > 0 ? $service->offer_price : $service->price;


        // Update assignment details
        $serviceAssign->update([
            'customer_id' => $request->customer_id,
            'service_id' => $request->service_id,
            'employee_id' => $request->employee_id,
            'remarks' => $request->remarks,
        ]);

        // Handle new payment (if any)
        if ($request->filled('new_payment') && $request->new_payment > 0) {
            // Update total paid payment
            $serviceAssign->paid_payment += $request->new_payment;
            $serviceAssign->save();

            // Determine new status
            $status = $serviceAssign->invoice->status;
            if ($serviceAssign->paid_payment >= $price) {
                $status = 'paid';
            } elseif ($serviceAssign->paid_payment > 0) {
                $status = 'partial';
            }

            $invoice = $serviceAssign->invoice;
            $invoice->update([
                'service_assign_id' => $serviceAssign->id,
                'paid_amount' =>  $invoice->paid_amount += $request->new_payment,
                'status' => $status,
            ]);


            // Log payment history
            PaymentHistory::create([
                'invoice_id' => $invoice->id,
                'amount' => $request->new_payment,
                'payment_method' => $request->payment_method,
                'comment' => $request->comment,
                'paid_at' => now(),
            ]);
        }

        return redirect()->route('admin.service_assigns.index')
            ->with('success', 'Service assignment updated and payment (if any) recorded successfully!');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
