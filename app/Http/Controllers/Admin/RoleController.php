<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\RolesDataTable;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {

        return [
            new Middleware('permission:view_roles', only: ['index']),
            new Middleware('permission:add_roles', only: ['create', 'store']),
            new Middleware('permission:edit_roles', only: ['edit', 'update']),
            new Middleware('permission:delete_roles', only: ['destroy']),
            new Middleware('permission:view_role-permission', only: ['show']),
            new Middleware('permission:add_role-permission', only: ['rolePermissions']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(RolesDataTable $datatable)
    {
        $data['title'] = 'LoanJar - Roles';
        return $datatable->render('admin.roles.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['title'] = 'LoanJar - Create Role';
        return view('admin.roles.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',

        ]);
        // Create a new company instance
        $role = new Role();
        $role->name = $request->name;
        $role->guard_name = 'admin';
        $role->save();
        Session::flash('success', 'Data added successfully!');

        // Redirect back with success or error message
        return redirect()->route('admin.role.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::find($id);

        $data = [
            'title' => 'Role Permission',
            'roles' => $role,
            'rolePermissions' => $role->permissions->pluck('name')->toArray(), // send assigned permissions
        ];

        return view('admin.roles.role-permission', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['title'] = 'LoanJar - Edit Role';
        $data['editrole'] = Role::find($id);
        return view('admin.roles.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!empty($id)) {
            // Validate the incoming request data
            $request->validate([
                'name' => ['required', 'string',  'max:255', Rule::unique('roles')->ignore($id),],
            ]);
            $role = Role::find($id);
            if (!empty($role)) {
                $role->update([
                    'name' => $request->name,
                ]);
                Session::flash('success', 'Data updated successfully!');
            } else {
                Session::flash('error', 'Role with ID ' . $id . ' not found.');
            }
        } else {

            Session::flash('error', 'Data not found!');
        }
        // Redirect back with success or error message
        return redirect()->route('admin.role.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function rolePermissions(Request $request)
    {
        // Find the role by ID
        $role = Role::findOrFail($request->role_id);

        // Get the permissions array from the request, or initialize an empty array if none
        $permissions = $request->permissions ?? [];

        // Initialize an array to hold permission models
        $permissionModels = [];

        // Loop through permissions and either find or create them
        foreach ($permissions as $permission) {
            // Create or find the permission model by its name
            $permissionModel = Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'admin', // Ensure the guard name is specified
            ]);

            // Add the permission model to the array
            $permissionModels[] = $permissionModel;
        }

        // Sync the permissions with the role
        // Use $permissionModels (an array of permission models) instead of $permissions (which is an array of names)
        $role->syncPermissions($permissionModels);

        // Debugging: Dump the permissions associated with the role
        // dd($role->permissions); // Check if permissions are correctly synced

        return redirect()->route('admin.role.index')->with('success', 'Permissions updated successfully.');
    }
}
