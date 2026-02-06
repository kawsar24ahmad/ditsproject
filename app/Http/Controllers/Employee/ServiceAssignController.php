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
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ServiceAssignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $serviceAssignments =  ServiceAssign::with(['customer:id,name', 'employee:id,name', 'assignedTasks.task', 'invoice:id,invoice_number,service_assign_id'])
    //     ->where('employee_id',auth()->user()->id)
    //     ->orderByDesc('id')
    //     ->paginate(10);

    //     $assignIds = $serviceAssignments->pluck('id');

    //     return view('employee.invoice.index', compact('serviceAssignments'));
    // }


    public function index(Request $request)
    {
        $search = $request->input('search');

        $serviceAssignments = ServiceAssign::with([
                'customer:id,name',
                'employee:id,name',
                'assignedTasks.task',
                'invoice:id,invoice_number,service_assign_id'
            ])
            ->where('employee_id', auth()->user()->id)
            ->when($search, function ($query) use ($search) {
                $query->whereHas('invoice', function ($q) use ($search) {
                    $q->where('invoice_number', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('id')
            ->paginate(10);

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

    public function pending()
    {
        $serviceAssignments =  ServiceAssign::with(['customer:id,name,starting_followers', 'employee:id,name', 'assignedTasks.task', 'invoice:id,invoice_number,service_assign_id', 'service:id,title'])
            ->where('status', 'pending')
            ->where('employee_id', auth()->user()->id)
            ->orderByDesc('id')->get();
        return view('employee.invoice.pending', compact('serviceAssignments'));
    }
    public function completed()
    {
        $serviceAssignments =  ServiceAssign::with(['customer:id,name,starting_followers', 'employee:id,name', 'assignedTasks.task', 'invoice:id,invoice_number,service_assign_id', 'service:id,title'])
            ->where('status', 'completed')
            ->where('employee_id', auth()->user()->id)
            ->orderByDesc('id')->get();
        return view('employee.invoice.completed', compact('serviceAssignments'));
    }
    public function progress()
    {
        $serviceAssignments =  ServiceAssign::with(['customer:id,name,starting_followers', 'employee:id,name', 'assignedTasks.task', 'invoice:id,invoice_number,service_assign_id', 'service:id,title'])
            ->where('status', 'in_progress')
            ->where('employee_id', auth()->user()->id)
            ->orderByDesc('id')->get();
        return view('employee.invoice.progress', compact('serviceAssignments'));
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
            'delivery_date' => 'nullable',
        ]);
        // dd($validatedData);

        try {
            DB::beginTransaction();
                // Get the service price from the DB (prevent tampering)
            $service = Service::findOrFail($request->service_id);
            $price = $service->offer_price > 0 ? $service->offer_price : $service->price;


            $serviceAssign = $service->serviceAssign()->create([
                'customer_id' => $request->customer_id,
                'employee_id' => $request->employee_id,
                'price' => $price,
                'paid_payment' => $request->paid_payment,
                'remarks' => $validatedData['remarks'],
                'delivery_date' => $validatedData['delivery_date'],
            ]);

            $tasks = $service->load('tasks')->tasks;

            foreach ($tasks as $task) {
                $serviceAssign->assignedTasks()->create([
                    'service_task_id' => $task->id,
                    'title' => $task->title, // Add title explicitly
                    'is_completed' => false,
                ]);
            }


            // $service->load('calendarDays.tasks');
            //     foreach ($service->calendarDays as $serviceDay) {

            //         $customerDay = $serviceAssign->calendarDays()->create([
            //             'day_number' => $serviceDay->day_number,
            //         ]);

            //         foreach ($serviceDay->tasks as $task) {

            //             $customerDay->tasks()->create([
            //                 'title' => $task->title,
            //                 'status' => 'pending'
            //             ]);

            //         }
            //     }

            $service->load('calendarDays.tasks.employees');

            foreach ($service->calendarDays as $serviceDay) {

                $customerDay = $serviceAssign->calendarDays()->create([
                    'day_number' => $serviceDay->day_number,
                ]);

                foreach ($serviceDay->tasks as $task) {

                    // Create customer task
                    $newTask = $customerDay->tasks()->create([
                        'service_task_id' => $task->id, // optional but good practice
                        'title' => $task->title,
                        'status' => 'pending'
                    ]);

                    // Copy Employees
                    if ($task->employees->isNotEmpty()) {

                        $newTask->employees()->sync(
                            $task->employees->pluck('id')
                        );

                    }

                }
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
            DB::commit();
            return redirect()->route('employee.dashboard')->with('success', 'Service assigned and invoice created successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();

            return back()->with('error', $th->getMessage());
        }
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

        // ✅ Validate input
        $validated = $request->validate([
            'customer.name'       => 'nullable|string|max:255',
            'customer.email'      => 'nullable|email|max:255',
            'customer.phone'      => 'nullable|string|max:20',
            'customer.fb_id_link' => 'nullable|url',
            'customer.fb_page_link' => 'nullable|url',
        ]);

         // ✅ Optional: Update customer info
        if ($request->filled('customer')) {
            $customer = $serviceAssign->customer;
            if ($customer) {
                $customer->update($request->customer);
            }
        }

        return back()->with('success', 'Service assignment updated successfully!');
    }


    public function updatePaidAmount(Request $request, $id)
    {
        $request->validate([
            'paid_amount' => 'required|numeric|min:0',
        ]);

        $serviceAssign = ServiceAssign::findOrFail($id);
        $invoice = $serviceAssign->invoice;

        $newPaidAmount = floatval($request->paid_amount);
        $price = $serviceAssign->service->offer_price > 0
            ? $serviceAssign->service->offer_price
            : $serviceAssign->service->price;

        $serviceAssign->paid_payment = $newPaidAmount;
        $serviceAssign->save();

        $invoice->paid_amount = $newPaidAmount;
        $invoice->status = $newPaidAmount >= $price ? 'paid' : ($newPaidAmount > 0 ? 'partial' : 'unpaid');
        $invoice->save();

        return back()->with('success', 'Paid amount updated successfully.');
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
