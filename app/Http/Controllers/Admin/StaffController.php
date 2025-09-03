<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\StaffDataTable;
use App\Http\Controllers\Controller;
use App\Traits\FileUploadTrait;
use App\Http\Requests\SubmitStaffRequest;
use App\Http\Requests\UpdateStaffRequeat;
use App\Models\city;
use App\Models\Company;
use App\Models\country;
use App\Models\Department;
use App\Models\Designation;
use Spatie\Permission\Models\Role;
use App\Models\Staff;
use Illuminate\Support\Facades\DB;
use App\Models\StaffEducationDocument;
use App\Models\state;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;


class StaffController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view_users', only: ['index']),
            new Middleware('permission:add_users', only: ['create', 'store']),
            new Middleware('permission:edit_users', only: ['edit', 'update']),
            new Middleware('permission:delete_users', only: ['destroy']),
        ];
    }
    use FileUploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(StaffDataTable $datatable)
    {
        $data['title'] = 'LoanJar - Users';
        return $datatable->render('admin.staff.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $data['title'] = 'LoanJar - Create User';
        if (Auth::user()->role_id == 2) {
            $data['roles'] = Role::where('status', 1)
                                 ->whereNotIn('id', [1, 2])
                                 ->get();
        } else {
            $data['roles'] = Role::where('status', 1)->get();
        }
        return view('admin.staff.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */

public function store(SubmitStaffRequest $request)
{
    DB::beginTransaction();
    try {
        // Upload files
        $iconPath = $request->hasFile('icon') ? $this->uploadFile($request->file('icon'), 'admin') : null;
        $aadharPath = $request->hasFile('aadhar') ? $this->uploadFile($request->file('aadhar'), 'personaldocument') : null;
        $pancardPath = $request->hasFile('pancard') ? $this->uploadFile($request->file('pancard'), 'personaldocument') : null;
        $domicilePath = $request->hasFile('domicile') ? $this->uploadFile($request->file('domicile'), 'personaldocument') : null;
        $bankdoc1Path = $request->hasFile('bankdoc1') ? $this->uploadFile($request->file('bankdoc1'), 'bankdocument') : null;
        $bankdoc2Path = $request->hasFile('bankdoc2') ? $this->uploadFile($request->file('bankdoc2'), 'bankdocument') : null;
        $experienceletterPath = $request->hasFile('experience_letter') ? $this->uploadFile($request->file('experience_letter'), 'companydocument') : null;
        $offerletterPath = $request->hasFile('offer_letter') ? $this->uploadFile($request->file('offer_letter'), 'companydocument') : null;
        $revealingletterPath = $request->hasFile('revealing_letter') ? $this->uploadFile($request->file('revealing_letter'), 'companydocument') : null;
        $joiningletterPath = $request->hasFile('joining_letter') ? $this->uploadFile($request->file('joining_letter'), 'companydocument') : null;

        // Step 1: Create User
        $user = new User();
                if (User::where('email', $request->email)->exists()) {
                 return redirect()->back()->with('error', 'Email already exits.');
            }

        $user->name = $request->first_name . ' ' . ($request->last_name ?? '');
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->decrypt_password = encrypt($request->password); // Encrypted password that can be decrypted
        $user->mobile = $request->mobile;
        $user->address = $request->address;
        $user->role_id = $request->role_id;
        $user->save(); // ✅ Save before assigning role

        // Assign Role
        $role = Role::findOrFail($request->role_id);
        $user->syncRoles([$role->name]); // ✅ Prevent duplicate entries

        // Step 2: Create Staff Record
        $staff = new Staff();
        $staff->user_id = $user->id;
        $staff->created_by = Auth::user()->id;
        $staff->employee_id = 'emp_' . now()->format('Ymd') . $user->id;
        $staff->first_name = $request->first_name;
        $staff->last_name = $request->last_name ?? '';
        $staff->email = $request->email;
        $staff->password = bcrypt($request->password);
        $staff->mobile = $request->mobile;
        $staff->alternate_mobile = $request->alternate_mobile ?? '';
        $staff->gender = $request->gender;
        $staff->dob = $request->dob ?? '';
        $staff->marital_status = $request->marital_status;
        $staff->role_id = $request->role_id;
        $staff->aadhar_number = $request->aadharnumber ?? '';
        $staff->pan_number = $request->pannumber ?? '';
        $staff->employee_category = $request->employee_category;
        $staff->bankname = $request->bankname;
        $staff->accountnumber = $request->accountnumber;
        $staff->ifsccode = $request->ifsccode;
        $staff->bankaddress = $request->bankaddress;
        $staff->upi_id = $request->upi_id;
        $staff->save();

        // Step 3: Upload Certificates
        if ($request->hasFile('certificate')) {
            $documentTypes = $request->document_type;
            $documentNames = $request->document_name;

            foreach ($request->file('certificate') as $key => $file) {
                $path = $this->uploadFile($file, 'educationdocument');

                $doc = new StaffEducationDocument();
                $doc->user_id = $user->id;
                $doc->document_type = $documentTypes[$key];
                $doc->document_name = $documentNames[$key] ?? '';
                $doc->certificate = $path;
                $doc->save();
            }
        }

        DB::commit();
        Session::flash('success', 'Staff added successfully!');
        return redirect()->route('admin.users.index');
    } catch (\Exception $e) {
        DB::rollBack();

        // Delete uploaded files if any
        foreach ([
            $iconPath, $aadharPath, $pancardPath, $domicilePath,
            $bankdoc1Path, $bankdoc2Path,
            $experienceletterPath, $offerletterPath,
            $revealingletterPath, $joiningletterPath
        ] as $filePath) {
            if (!empty($filePath) && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        }

        Session::flash('error', 'Failed to add staff: ' . $e->getMessage());
        return redirect()->back()->withInput();
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
        $data['title'] = 'LoanJar - Edit User';
        $data['editstaff'] = Staff::findOrFail($id);
        $staff = Staff::findOrFail($id);
        $data['user'] = User::findOrFail($staff->user_id);

        if (Auth::user()->role_id == 2) {
            $data['roles'] = Role::where('status', 1)
                                 ->whereNotIn('id', [1, 2])
                                 ->get();
        } else {
            $data['roles'] = Role::where('status', 1)->get();
        }        $data['staffEducationDocuments'] = StaffEducationDocument::where('user_id', $data['editstaff']->user_id)->get();
        return view('admin.staff.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStaffRequeat $request, string $id)
    {
        DB::beginTransaction();

        try {
            // Fetch the existing staff and user
            // dd($id);
            // $user = User::findOrFail($id);
            $staff = Staff::where('id', $id)->first();
            $user = User::findOrFail($staff->user_id);
            // dd($id);
            if ($staff) {

                // Proceed with your file upload logic
                $aadharPath = $request->hasFile('aadhar')
                    ? $this->uploadOrReplaceFile($request->file('aadhar'), 'personaldocument', $staff->aadhar_certificate)
                    : $staff->aadhar_certificate;
            } else {
                $aadharPath = null;
            }
            $user->password = bcrypt($request->password); // Hashed password for authentication
            $user->decrypt_password = encrypt($request->password); // Encrypted password that can be decrypted
            // $user->mobile = $request->mobile;
            // $user->address = $request->address;
            // $user->icon = $iconPath ?? $user->icon;
            $user->role_id = $request->role_id;
            // $role = Role::findOrFail($request->role_id) ?? '';

            // $user->assignRole($role->name); 
            $user->save();

            // Update Staff Information
            $staff->first_name = $request->first_name ?? '';
            $staff->last_name = $request->last_name ?? '';
            $staff->email = $request->email;
            $staff->password = $request->has('password') ? bcrypt($request->password) : $staff->password;
            $staff->mobile = $request->mobile;
            $staff->alternate_mobile = $request->alternate_mobile ?? '';
            $staff->gender = $request->gender;
            $staff->dob = $request->dob ?? '';
            $staff->marital_status = $request->marital_status;
            $staff->role_id = $request->role_id;
            $staff->aadhar_number = $request->aadharnumber ?? '';
            // $staff->aadhar_certificate = $aadharPath ?? $staff->aadhar_certificate;
            $staff->pan_number = $request->pannumber ?? '';
            // $staff->pan_certificate = $pancardPath ?? $staff->pan_certificate;
            // $staff->domicile = $domicilePath ?? $staff->domicile;
            $staff->employee_category = $request->employee_category;
            $staff->bankname = $request->bankname;
            $staff->accountnumber = $request->accountnumber;
            $staff->ifsccode = $request->ifsccode;
            $staff->bankaddress = $request->bankaddress;
            $staff->ifsccode = $request->ifsccode;
            $staff->upi_id = $request->upi_id;
            // $staff->bankdoc1 = $bankdoc1Path ?? $staff->bankdoc1;
            // $staff->bankdoc2 = $bankdoc2Path ?? $staff->bankdoc2;
            $staff->save();

            // Step 3: Update StaffEducationDocuments if any
            if ($request->hasFile('certificate')) {
                $documentTypes = $request->document_type;
                $documentNames = $request->document_name;
                foreach ($request->file('certificate') as $key => $file) {
                    $path = $this->uploadOrReplaceFile($file, 'educationdocument');

                    $doc = new StaffEducationDocument();
                    $doc->user_id = $user->id;
                    $doc->document_type = $documentTypes[$key];
                    $doc->document_name = $documentNames[$key] ?? '';
                    $doc->certificate = $path;
                    $doc->save();
                }
            }

            DB::commit();
            Session::flash('success', 'User updated successfully!');
            return redirect()->route('admin.users.index');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);

            // Delete any files that were uploaded before rollback
            $this->deleteUploadedFiles([
                $iconPath,
                $aadharPath,
                $pancardPath,
                $domicilePath,
                $experienceletterPath,
                $offerletterPath,
                $revealingletterPath,
                $joiningletterPath
            ]);

            // Optionally log the error or flash a session message for failure
            // Session::flash('error', 'Failed to update staff: ' . $e->getMessage());
            // return redirect()->back()->withInput();
        }
    }

    public function deleteUploadedFiles($paths = [])
    {
        foreach ($paths as $path) {
            if (!empty($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the staff record
        $staff = Staff::findOrFail($id);
        
        // Find and delete the associated user
        if ($staff->user_id) {
            User::where('id', $staff->user_id)->delete();
        }
        
        // Delete the staff record
        $staff->delete(); 
        
        Session::flash('success', 'Deleted successfully!');
        return redirect()->route('admin.users.index');
    }
}
