<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Models\Loan;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\DataTables\LoanDataTable;
use Illuminate\Support\Facades\Auth;
use App\Models\LoanInstallment;
use DataTables;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;  // << add this line


class LoanController extends Controller 
{
    // public static function middleware(): array
    // {
        // return [
        // implements HasMiddleware
        //     new Middleware('permission:view_loans', only: ['index']),
        //     new Middleware('permission:add_loans', only: ['create', 'store', 'history']),
        //     new Middleware('permission:edit_loans', only: ['edit', 'update', 'history']),
        //     new Middleware('permission:delete_loans', only: ['destroy']), 
        // ];
    // }

    public function index(LoanDataTable $datatable)
    {
        $data['title'] = 'LoanJar - Loans';
        
        $userid = Auth::user()->id;
        if(Auth::user()->id == 1 || Auth::user()->role_id == 1){
            $data['users'] = Staff::get();
        }else{
            $data['users'] = Staff::where('created_by',$userid)->get();
        }
        return $datatable->render('admin.loan.index', $data);
    }

public function historyUser(Request $request)
{
    $loanId = $request->loan;
    $userId = $request->user;

    $data['title'] = 'LoanJar - Loan History';

    $installments = DB::table('loan_installments')
        ->where('user_id', $userId)
        ->where('loan_id', $loanId)
        ->orderBy('id', 'asc')
        ->get();

    $data['installments'] = $installments;

    return view('admin.loan.userhistory', $data);
}



    public function create()
    {
        $data['title'] = 'LoanJar - Apply for Loan';
        $managerId = Auth::user()->id;
        if($managerId == 1){
        $users = Staff::where('role_id', 3)->get();
          $data['users'] = $users;
        }else{
          $data['users'] = Staff::where('created_by', $managerId)->get();
        }
        $data['managers'] = User::where('role_id',2)->get();
        return view('admin.loan.create', $data);
    }
    public function view_user(LoanDataTable $datatable)
    {

        $userId = Auth::user()->id;
        $loan = Loan::where('user_id', $userId)->get();
        $data['title'] = 'LoanJar - Loan';
        $data['loan'] = $loan;
        return $datatable->render('admin.loan.userloan', $data);
    }

    public function history(string $id)
    {

        $loan = Loan::findOrFail($id);

        $data['title'] = 'LoanJar - Loan history';
        $data['managerId'] = Auth::user()->id;
        $data['editloan'] = $loan;

        // Get previous installments (if any)
        $installments = $loan->installments()->where('loan_id', $loan->id)->orderBy('id', 'asc')->get();

        // Calculate total sum of installments
        $totalAmount = $installments->sum('amount');

        $data['installments'] = $installments->keyBy(function ($item, $key) {
            return $key + 1; // match with loop index
        });

        // Pass the total amount to the view
        $data['totalAmount'] = $totalAmount;

        // Get previous installments (if any)
        $data['installments'] = $loan->installments()->orderBy('id', 'asc')->get()->keyBy(function ($item, $key) {
            return $key + 1; // match with loop index
        });

        $data['loan_bank'] = Staff::where('user_id',$loan->user_id)->first();
        $data['admin'] = User::where('id',$loan->created_by)->first();
        // dd($loan->user_id);
        return view('admin.loan.history', $data);
    }

protected function parseDateToYmd($dateString)
{
    $formats = ['d/m/Y', 'Y-m-d', 'm/d/Y', 'd-m-Y', 'Y/m/d'];

    foreach ($formats as $format) {
        try {
            return \Carbon\Carbon::createFromFormat($format, $dateString)->format('Y-m-d');
        } catch (\Exception $e) {
            continue; // Try next format
        }
    }

    // Final fallback
    try {
        return \Carbon\Carbon::parse($dateString)->format('Y-m-d');
    } catch (\Exception $e) {
        return null;
    }
}



    // public function saveHistory(Request $request, $loanId)
    // {
    //     $userId = $request->input('user_id');
    //     $admin_id = $request->input('admin_id');
    //     $loan_data = Loan::find($loanId);
    
    //     if ($loan_data->status == 'pending' || $loan_data->status == 'rejected') {
    //         return redirect()->back()->with('error', 'Loan is not approved.');
    //     }
    
    //     foreach ($request->installments as $i => $installment) {
    //         // ✅ Skip if all important fields are empty or zero
    //     if (
    //         empty($installment['date']) ||
    //         empty($installment['amount']) ||
    //         $installment['amount'] == 0 ||
    //         empty($installment['payment_method'])
    //     ) {
    //         continue;
    //     }
        
    
    //         $installmentId = $installment['id'] ?? null;
    //         $date = $this->parseDateToYmd($installment['date']);
    //         $amount = $installment['amount'];
    
    //         if ($installmentId) {
    //             // ✅ Update existing installment
    //             $loanInstallment = LoanInstallment::where('loan_id', $loanId)
    //                 ->where('id', $installmentId)
    //                 ->where('user_id', $userId)
    //                 ->first();
    
    //             if ($loanInstallment) {
    //                 // Screenshot upload if present
    //                 if ($request->hasFile("installments.{$i}.screenshot") && $request->file("installments.{$i}.screenshot")->isValid()) {
    //                     $screenshot = $request->file("installments.{$i}.screenshot");
    //                     $screenshotPath = $screenshot->store('screenshots', 'public');
    //                     $loanInstallment->screenshot = $screenshotPath;
    //                 }
    
    //               $loanInstallment->date =  $date;
    //                 $loanInstallment->title = $installment['title'] ?? 'Installment';
    //                 $loanInstallment->description = $installment['description'] ?? '';
    //                 $loanInstallment->amount = $amount;
    //                 $loanInstallment->payment_method = $installment['payment_method'] ?? '';
    //                 $loanInstallment->save();
    //             }
    //         } else {
    //             // ✅ Check if installment with same loan_id, user_id, date exists
    //             $exists = LoanInstallment::where('loan_id', $loanId)
    //                 ->where('user_id', $userId)
    //                 ->where('date', $date)
    //                 ->exists();
    
    //             if ($exists) {
    //                 continue;
    //             }
    
    //             // Screenshot upload if present
    //             $screenshotPath = null;
    //             if ($request->hasFile("installments.{$i}.screenshot") && $request->file("installments.{$i}.screenshot")->isValid()) {
    //                 $screenshot = $request->file("installments.{$i}.screenshot");
    //                 $screenshotPath = $screenshot->store('screenshots', 'public');
    //             }
    
    //             // ✅ Create new installment
    //             LoanInstallment::create([
    //                 'loan_id' => $loanId,
    //                 'user_id' => $userId,
    //                 'created_by' => $admin_id,
    //                 'date' => $date,
    //                 'title' => $installment['title'] ?? 'Installment',
    //                 'description' => $installment['description'] ?? '',
    //                 'amount' => $amount,
    //                 'payment_method' => $installment['payment_method'] ?? '',
    //                 'screenshot' => $screenshotPath,
    //             ]);
    //         }
    //     }
    
    //     return redirect()->back()->with('success', 'Loan installments saved successfully.');
    // }

public function saveHistory(Request $request, $loanId)
{
    $userId = $request->input('user_id');
    $admin_id = $request->input('admin_id');
    $loan_data = Loan::find($loanId);

    if ($loan_data->status == 'pending' || $loan_data->status == 'rejected') {
        return redirect()->back()->with('error', 'Loan is not approved.');
    }

    foreach ($request->installments as $i => $installment) {
        if (
            empty($installment['date']) ||
            empty($installment['amount']) ||
            $installment['amount'] == 0 ||
            empty($installment['payment_method'])
        ) {
            continue;
        }

        $installmentId = $installment['id'] ?? null;
        $date = $this->parseDateToYmd($installment['date']);
        $amount = $installment['amount'];

        // ✅ Calculate total already paid (excluding current installment if updating)
        $totalPaid = LoanInstallment::where('loan_id', $loanId)
            ->where('user_id', $userId)
            ->when($installmentId, fn($q) => $q->where('id', '!=', $installmentId))
            ->sum('amount');

        $totalWithNew = $totalPaid + $amount;

        if ($totalWithNew > $loan_data->total_with_interest) {
            return redirect()->back()->with('error', 'Installment amount exceeds the loan total amount.');
        }

        if ($installmentId) {
            // ✅ Update existing installment
            $loanInstallment = LoanInstallment::where('loan_id', $loanId)
                ->where('id', $installmentId)
                ->where('user_id', $userId)
                ->first();

            if ($loanInstallment) {
                if ($request->hasFile("installments.{$i}.screenshot") && $request->file("installments.{$i}.screenshot")->isValid()) {
                    $screenshot = $request->file("installments.{$i}.screenshot");
                    $screenshotPath = $screenshot->store('screenshots', 'public');
                    $loanInstallment->screenshot = $screenshotPath;
                }

                $loanInstallment->date = $date;
                $loanInstallment->title = $installment['title'] ?? 'Installment';
                $loanInstallment->description = $installment['description'] ?? '';
                $loanInstallment->amount = $amount;
                $loanInstallment->payment_method = $installment['payment_method'] ?? '';
                $loanInstallment->save();
            }
        } else {
            $exists = LoanInstallment::where('loan_id', $loanId)
                ->where('user_id', $userId)
                ->where('date', $date)
                ->exists();

            if ($exists) {
                continue;
            }

            $screenshotPath = null;
            if ($request->hasFile("installments.{$i}.screenshot") && $request->file("installments.{$i}.screenshot")->isValid()) {
                $screenshot = $request->file("installments.{$i}.screenshot");
                $screenshotPath = $screenshot->store('screenshots', 'public');
            }

            LoanInstallment::create([
                'loan_id' => $loanId,
                'user_id' => $userId,
                'created_by' => $admin_id,
                'date' => $date,
                'title' => $installment['title'] ?? 'Installment',
                'description' => $installment['description'] ?? '',
                'amount' => $amount,
                'payment_method' => $installment['payment_method'] ?? '',
                'screenshot' => $screenshotPath,
            ]);
        }
    }

    return redirect()->back()->with('success', 'Loan installments saved successfully.');
}


    public function store(Request $request)
    {
        $request->validate([
            'user_id'            => 'required',
            'date'               => 'required',
            'title'              => 'required|string|max:255',
            'notes'              => 'nullable|string',
            'amount'             => 'required|numeric|min:1000',
            'capital_interest'   => 'required|numeric|min:0',
            'loan_duration'      => 'required|string|max:10',
            'total_with_interest'      => 'required',
            'apply_interest_on'  => 'required|in:year,month,day,hour',
            'aadhar_card'        => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'pan_card'           => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
       // Conditionally require manager_id if present in request
        if ($request->has('manager_id')) {
            $rules['manager_id'] = 'required';
        }
        $loan = new Loan();
        $loan->user_id = $request->user_id;
        if (Auth::id() == 1 && $request->filled('manager_id')) {
            $loan->created_by = $request->manager_id;
        } else {
            $loan->created_by = Auth::id() ?? 0;
        }
        $loan->date              = $this->parseDateToYmd($request->date);
        $loan->title             = $request->title;
        $loan->notes             = $request->notes;
        $loan->amount            = $request->amount;
        $loan->capital_interest  = $request->capital_interest;
        $loan->loan_duration     = $request->loan_duration;
        $loan->apply_interest_on = $request->apply_interest_on;
        
        $loan->status = 'pending';

        // Optional: calculate total_with_interest and store
        $loan->total_with_interest = $request->total_with_interest;

        // Generate custom loan_id: first 2 chars of username + incrementing number
        $prefix = strtoupper(substr(Auth::user()->name, 0, 2));
        $latestId = Loan::max('id') + 1;
        $loan->loan_id = $prefix . str_pad($latestId, 4, '0', STR_PAD_LEFT);

        if ($request->hasFile('aadhar_card')) {
            $loan->aadhar_card = $request->file('aadhar_card')->store('documents', 'public');
        }
        if ($request->hasFile('pan_card')) {
            $loan->pan_card = $request->file('pan_card')->store('documents', 'public');
        }
        $loan->save();
        Session::flash('success', 'Loan application submitted successfully!');
        return redirect()->route('admin.loans.index');
    }

 public function update(Request $request, $id)
{
    $request->validate([
        'user_id'            => 'required',
        'date'               => 'required',
        'title'              => 'required|string|max:255',
        'notes'              => 'nullable|string',
        'amount'             => 'required',
        'capital_interest'   => 'required|numeric|min:0',
        'loan_duration'      => 'required',
        'total_with_interest'=> 'required',
        'apply_interest_on'  => 'required|in:year,month,day,hour',
        'aadhar_card'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'pan_card'           => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]);

   // Conditionally require manager_id if present in request
    if ($request->has('manager_id')) {
        $rules['manager_id'] = 'required';
    }
    
    // Find loan
    $loan = Loan::findOrFail($id);

    // Get user_id from Staff model and final user
    $user_id_data = Staff::find($request->user_id);
    $user_finalise_id = User::find($user_id_data->user_id);

    // Update loan fields
    $loan->user_id = $user_finalise_id->id;
    $loan->date = $this->parseDateToYmd($request->date);
    $loan->title = $request->title;
    $loan->notes = $request->notes;
    $loan->amount = $request->amount;
    $loan->capital_interest = $request->capital_interest;
    $loan->loan_duration = $request->loan_duration;
    $loan->apply_interest_on = $request->apply_interest_on;
    $loan->total_with_interest = $request->total_with_interest;
        if (Auth::id() == 1 && $request->filled('manager_id')) {
            $loan->created_by = $request->manager_id;
            Staff::where('id', $request->user_id)
        ->update(['created_by' => $request->manager_id]);
            LoanInstallment::where('loan_id', $loan->id)
        ->update(['created_by' => $request->manager_id]);
        } else {
            $loan->created_by = Auth::id() ?? 0;
        }
          
    // Generate loan_id if not present
    if (!$loan->loan_id) {
        $prefix = strtoupper(substr(Auth::user()->name, 0, 2));
        $latestId = Loan::max('id') + 1;
        $loan->loan_id = $prefix . str_pad($latestId, 4, '0', STR_PAD_LEFT);
    }

    // Handle aadhar upload
    if ($request->hasFile('aadhar_card')) {
        if ($loan->aadhar_card && \Storage::disk('public')->exists($loan->aadhar_card)) {
            \Storage::disk('public')->delete($loan->aadhar_card);
        }
        $loan->aadhar_card = $request->file('aadhar_card')->store('documents', 'public');
    }

    // Handle pan upload
    if ($request->hasFile('pan_card')) {
        if ($loan->pan_card && \Storage::disk('public')->exists($loan->pan_card)) {
            \Storage::disk('public')->delete($loan->pan_card);
        }
        $loan->pan_card = $request->file('pan_card')->store('documents', 'public');
    }

    $loan->save();

    // ✅ Update user_id in all loan_installments related to this loan
    LoanInstallment::where('loan_id', $loan->id)
        ->update(['user_id' => $user_finalise_id->id]);

    Session::flash('success', 'Loan application updated successfully!');
    return redirect()->route('admin.loans.index');
}



public function edit(string $id)
{
    $data['title'] = 'LoanJar - Edit Loan';
    $managerId = Auth::id(); // cleaner than Auth::user()->id

    $loan = Loan::findOrFail($id); // better to use findOrFail to handle invalid ID gracefully
    $user = User::find($loan->user_id); // optional: use findOrFail if user must exist
    // dd($user);
    $data['editrole'] = $loan;
    if(Auth::user()->id == 1 || Auth::user()->role_id == 1){
            $data['users'] = Staff::where('role_id', 3)->get();
        }else{
            $data['users'] = Staff::where('created_by',$managerId)->get();
        }
        
    $data['user_'] = Staff::where('user_id',$loan->user_id)->get();
      $data['managers'] = User::where('role_id',2)->get();

    return view('admin.loan.edit', $data);
}

    public function destroy(string $id)
    {
        $loan = Loan::findOrFail($id);
        if ($loan->document) {
            Storage::disk('public')->delete($loan->document);
        }
        $loan->delete();
        Session::flash('success', 'Loan deleted successfully!');
        return redirect()->route('admin.loans.index');
    }

    public function report()
    {
        $data['title'] = 'LoanJar - Loan Report';
        $userid = Auth::user()->id;
        if(Auth::user()->id == 1 || Auth::user()->role_id == 1){
            $data['users'] = Staff::get();
        }else{
            $data['users'] = Staff::where('created_by',$userid)->get();
        }
        return view('admin.loan_report.index', $data);
    }

   public function getData(Request $request)
{
    $user = Auth::user();
    $roleId = $user->role_id;

    $query = Loan::select('loans.*', 'staffdetails.first_name as user_name', 'staffdetails.last_name as last_name')
      ->leftJoin('staffdetails', 'loans.user_id', '=', 'staffdetails.user_id');

    if ($user->role_id == 1) {
        // Admin, no filter
    } elseif ($user->role_id == 2) {
        $query->where('loans.created_by', $user->id);
    } else {
        $query->where('loans.user_id', $user->id);
    }

    // Apply date filtering if any 
    if ($request->filled('from') && $request->filled('to')) {
        try {
    $from = \Carbon\Carbon::createFromFormat('d/m/Y', request('from'))->format('Y-m-d');
    $to = \Carbon\Carbon::createFromFormat('d/m/Y', request('to'))->format('Y-m-d');
    $query->whereBetween('date', [$from, $to]);
        } catch (\Exception $e) {
            // handle error
        }
    }

    if ($request->filled('user_id')) {
        $query->where('loans.user_id', $request->input('user_id'));
    }

    return DataTables::of($query)
        ->addColumn('user_name', function($row) {
            $first = $row->user_name ?? '';
            $last = $row->last_name ?? '';
            return trim($first . ' ' . $last) ?: '-';
        })
        ->editColumn('created_at', function($row) {
            return \Carbon\Carbon::parse($row->created_at)
                ->timezone('Asia/Kolkata')
                ->format('d M Y');
        })
        ->editColumn('date', function($row) {
            try {
                return \Carbon\Carbon::parse($row->date)
                    ->timezone('Asia/Kolkata')
                    ->format('d M Y');
            } catch (\Exception $e) {
                return $row->date;
            }
        })
        ->editColumn('status', function ($row) use ($roleId) {
            $statusMap = [
                0 => ['label' => 'Pending', 'class' => 'bg-primary-subtle text-primary', 'next' => 1],
                1 => ['label' => 'Approved', 'class' => 'bg-success-subtle text-success', 'next' => 2],
                2 => ['label' => 'Rejected', 'class' => 'bg-danger-subtle text-danger', 'next' => 0],
            ];

            $currentStatus = $statusMap[$row->status] ?? $statusMap[0];

            if (in_array($roleId, [2, 3])) {
                return "<div class='badge {$currentStatus['class']} font-size-12'>{$currentStatus['label']}</div>";
            }

            return "<div class='badge {$currentStatus['class']} font-size-12'>{$currentStatus['label']}</div>";
        })
        ->addIndexColumn()
        ->addColumn('loan_period', fn($row) => $row->loan_duration . '-' . strtoupper(substr($row->apply_interest_on, 0, 1)))
        ->addColumn('capital_interest', fn($row) => $row->capital_interest . '%')
        ->addColumn('document', function($row) {
            return $row->document
                ? '<a href="' . asset('storage/' . $row->document) . '" target="_blank">View</a>'
                : 'No Doc';
        })
        ->rawColumns(['document', 'user_name', 'status'])
        ->make(true);
}


    public function reportInstall()
    {
        $data['title'] = 'LoanJar - Loan Installment Report';
        $userid = Auth::user()->id;
        if(Auth::user()->id == 1 || Auth::user()->role_id == 1){
            $data['users'] = Staff::get();
        }else{
            $data['users'] = Staff::where('created_by',$userid)->get();
        }
        return view('admin.loan_report.loan_report_install', $data);
    }

    public function getDataInstall(Request $request)
    {
        $user = Auth::user();
    
        $query = DB::table('loan_installments')
            ->leftJoin('users', 'loan_installments.user_id', '=', 'users.id')
            ->leftJoin('staffdetails', 'loan_installments.user_id', '=', 'staffdetails.user_id') // ✅ correct
            ->leftJoin('loans', 'loan_installments.loan_id', '=', 'loans.id')
            ->select(
                'loan_installments.*',
                'loans.title as loan_title',
                'staffdetails.first_name',
                'staffdetails.last_name'
            );
    
        if ($user->role_id == 3) {
            $query->where('loan_installments.user_id', $user->id);
        } else if ($user->role_id == 1) {
       
        }  elseif ($user->role_id == 2) {
            $query->where('loan_installments.created_by', $user->id);
        }

    if ($request->filled('from') && $request->filled('to')) {
    try {
        $from = Carbon::createFromFormat('d/m/Y', $request->input('from'))->format('Y-m-d');
        $to = Carbon::createFromFormat('d/m/Y', $request->input('to'))->format('Y-m-d');
        // Convert `loan_installments.date` from d-m-Y to real date using STR_TO_DATE for filtering
        // $query->whereRaw("STR_TO_DATE(loan_installments.date, '%d-%m-%Y') BETWEEN ? AND ?", [$from, $to]);
         $query->whereBetween('loan_installments.date', [$from, $to]);
    } catch (\Exception $e) {
        // Optional: log error or return message
    }
}

    
    
        if ($request->filled('user_id')) {
        $query->where('loans.user_id',$request->input('user_id'));
        }
        
        // dd($query->toSql());
        return DataTables::of($query)
            ->addColumn('user_name', function ($row) {
                return trim(($row->first_name ?? '') . ' ' . ($row->last_name ?? '')) ?: '-';
            })
            ->addColumn('loan_title', function ($row) {
                return $row->loan_title ?? '-';
            })
            ->editColumn('created_at', function ($row) {
                return $row->date ? Carbon::parse($row->date)->format('d M Y') : '-';
            })
            ->addColumn('screenshot', function ($row) {
                if (!$row->screenshot) return 'No Image';
    
                $imageUrl = asset('storage/' . $row->screenshot);
    
                return '
                    <img 
                        src="' . $imageUrl . '" 
                        height="40" 
                        width="40" 
                        class="img-thumbnail open-image-modal" 
                        data-bs-toggle="modal" 
                        data-bs-target="#imageModal" 
                        data-image="' . $imageUrl . '" 
                        style="cursor: pointer;"
                    >';
            })
            ->rawColumns(['screenshot'])
            ->make(true);
    }
        public function closeLoan($id)
    {
        
        $loan = Loan::findOrFail($id);
        $loan->status = 3; // Assuming 2 means 'Closed'
        $loan->save();
    
        return redirect()->back()->with('success', 'Loan has been closed successfully.');
    }

}
