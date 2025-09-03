<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\LoanInstallment;
use App\Models\Staff;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\DataTables\LoanDataTable;
use DB;

class AdminController extends Controller
{
    public function index()
    {
        $title = 'LoanJar - Dashboard';
    
        $userid = Auth::user()->id;
        $roleid = Auth::user()->role_id;
    
        if ($roleid == 1) {
            $loans = Loan::orderBy('id', 'desc')->get();
            $totalAmount = Loan::where('status','!=',3)->sum('amount');
            $totalCapitalAmount = Loan::where('status','!=',3)->sum('total_with_interest');
            $totalIntAmount = $totalCapitalAmount - $totalAmount;
        } elseif ($roleid == 2) {
            $loans = Loan::where('created_by', $userid)->orderBy('id', 'desc')->get();
            $totalAmount = Loan::where('created_by', $userid)->where('status','!=',3)->sum('amount');
            $totalCapitalAmount = Loan::where('created_by', $userid)->where('status','!=',3)->sum('total_with_interest');
            $totalIntAmount = $totalCapitalAmount - $totalAmount;
        } else {
            $loans = Loan::where('user_id', $userid)->orderBy('id', 'desc')->get();
            $totalAmount = Loan::where('user_id', $userid)->where('status','!=',3)->sum('amount');
            $totalCapitalAmount = Loan::where('status','!=',3)->where('user_id', $userid)->sum('total_with_interest');
            $totalIntAmount = $totalCapitalAmount - $totalAmount;
        }
        
        // Extract all loan IDs
        $loanIds = $loans->pluck('id');
        // Sum collected amount based on role
        $totalCollectedAmount = LoanInstallment::whereIn('loan_id', $loanIds)->sum('amount');
    
$installments = DB::table('loan_installments')
    ->join('loans', 'loan_installments.loan_id', '=', 'loans.id')
    ->leftJoin('staffdetails', 'loans.user_id', '=', 'staffdetails.user_id') // ✅ fixed join
    ->whereIn('loan_installments.loan_id', $loanIds)
    ->select(
        'loan_installments.*',
        'loans.id as loan_id',
        'loans.amount as loan_amount',
        'loans.loan_id as loan_unique_id',
        'loans.user_id',
        DB::raw("CONCAT(COALESCE(staffdetails.first_name, ''), ' ', COALESCE(staffdetails.last_name, '')) as user_name"), // ✅ avoids null concat
        'loans.created_by'
    )
    ->orderBy('loan_installments.id', 'desc')
    ->get();

    // dump($installments);
            $remainingAmount = $totalCapitalAmount - $totalCollectedAmount;

            // dd($installments);
        return view('admin.admindetails.dashboard', compact(
            'title',
            'installments',
            'totalAmount',
            'totalCapitalAmount',
            'totalIntAmount',
            'totalCollectedAmount',
            'remainingAmount'
        ));
    }
    public function getChartData(Request $request)
    {
        $monthName = $request->input('month'); // Example: "May"
        $monthNumber = date('n', strtotime($monthName)); // Converts "May" to 5
    
        $userid = Auth::user()->id;
        $roleid = Auth::user()->role_id;

        $loanQuery = Loan::query();
        if ($roleid == 2) {
            $loanQuery->where('created_by', $userid);
        } elseif ($roleid != 1) {
            $loanQuery->where('user_id', $userid);
        }
        
    
        // Now apply month filter for loans
        $loanQuery->whereMonth('created_at', $monthNumber);
        $loanIds = $loanQuery->pluck('id');
        // Filter installments by same month
        $installments = LoanInstallment::whereIn('loan_id', $loanIds)
            ->whereMonth('created_at', $monthNumber)
            ->get();
        $totalAmount = Loan::where('status','!=',3)->sum('amount');
        $totalCapitalAmount = $loanQuery->sum('total_with_interest');
        $intAmount = $totalCapitalAmount - $totalAmount;
        $totalCollectedAmount = LoanInstallment::whereIn('loan_id', $loanIds)->sum('amount');

        return response()->json([
            'loan' => $totalAmount,
            'collected' => $intAmount,
            'remaining' => $totalCollectedAmount
        ]);
    }
    
    

    
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('login');
    }
    
    public function adminprofile(Request $request)
    {
        $title = 'LoanJar - Profile';
        $output['res'] = 'error';
        if (!empty($request->id)) {
            $request->validate([
                'name' => 'required',
                'mobile' => 'required|numeric|digits:10',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users')->ignore($request->id),
                ],
                'address' => 'required',
            ]);
    
            $user = User::find($request->id);
    
            if (!$user) {
                $output['msg'] = 'User not found';
               
            } else {
                // Update user details
                $user->name = $request->input('name');
                $user->mobile = $request->input('mobile');
                $user->email = $request->input('email');
                $user->address = $request->input('address');
               
                if ($user->save()) {
                    $output['res'] = 'success';
                    $output['msg'] = 'Profile updated successfully';
                    $output['redirect'] = route('admin.adminprofile');
                    return json_encode([$output]);
                } else {
                  
                    $output['msg'] = 'Failed to update profile';
                    return json_encode([$output]);
                }
                
            }
           
        } elseif (!empty($request->icon)) {
            $request->validate([
                'icon' => 'image|mimes:jpeg,png,jpg,webp,gif|max:2048',
            ]);
    
            $user = auth('admin')->user();
    
            if ($request->hasFile('icon')) {
                // Delete the old image if it exists
                if ($user->icon) {
                    Storage::disk('public')->delete($user->icon);
                }
                $imagePath = $request->file('icon')->store('admin', 'public');
                $user->icon = $imagePath;

                if ($user->save()) {
                    Session::flash('success', 'Image updated successfully');
                } else {
                    Session::flash('error', 'Image not updated ');
                }
            } else {
                Session::flash('success', 'No file provided in the request.');
            }
        }
        
     
        return view('admin.admindetails.profile', compact('title'));
    }
    //changepassword
    public function changePasswordShow()
    {
        $title = 'LoanJar - Change Password';
        return view('admin.admindetails.change-password', compact('title'));
    }
    public function changePassword(Request $request)
    {
        // Validate the request data, including the 'confirmed' rule for 'new-password'
        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required',
            'password_confirmation' => 'required|same:new-password',
        ]);

        // Check if the current password matches the authenticated user's password
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The current password does not match
            return redirect()->back()->withInput()->with("error", "Your current password does not match with the password.");
        }

        // Check if the new password is the same as the current password
        if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
            // Current password and new password are the same
            return redirect()->back()->withInput()->with("error", "New Password cannot be the same as your current password.");
        }

        // Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));

        // Save the user and check if the save operation was successful
        if ($user->save()) {
            return redirect()->back()->with("success", "Password successfully changed!");
        } else {
            // Handle the case where the save operation fails
            return redirect()->back()->withInput()->with("error", "Failed to change the password. Please try again.");
        }
    }
}
