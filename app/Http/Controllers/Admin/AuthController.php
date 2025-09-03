<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function index()
    {
        return view('admin.layout.adminlogin');
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return redirect()->route('admin.login')->with('error', 'Please enter correct email.');
        }
    
        // Check if User is active
        if ($user->status != 1) {
            return redirect()->route('admin.login')->with('error', 'Your account is inactive.');
        }
    
        // Load related staff (assuming one-to-one or belongsTo relationship)
        $staff = $user->staff; // You must define this relation in the User model
    
        // Check if Staff exists and is active
        if ((!$staff || $staff->status != 1) && $user->role_id != 1) {
            return redirect()->route('admin.login')->with('error', 'Your profile is inactive.');
        }
    
        // Attempt login
        if (Auth::guard('admin')->attempt($request->only('email', 'password'), $request->has('remember'))) {
            session()->flash('success', 'Login successful.');
            return redirect()->route('admin.dashboard');
        }
    
        return redirect()->route('admin.login')->with('error', 'Invalid email or password.');
    }

}
