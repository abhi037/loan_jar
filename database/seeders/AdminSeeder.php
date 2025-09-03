<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Call permission seeder
        $this->call(PermissionSeeder::class);

        // Create admin user
        $admin = User::updateOrCreate(['email' => 'superadmin@gmail.com'], [
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('12345678'),
            'address' => 'Lucknow',
            'status' => 1,
        ]);
        // Create staff record if not exists
        Staff::updateOrCreate(
            ['user_id' => $admin->id],
            [
                'employee_id' => 'emp_' . now()->format('Ymd') . $admin->id,
                'first_name' => 'Super Admin',
                'last_name' => 'Rai',
                'email' => 'superadmin@gmail.com',
                'password' => bcrypt('12345678'),
                'mobile' => '9999999999',
                'gender' => 'f',
                'marital_status' => 'unmarried',
                'employee_category' => 'experienced',
                'company_id' => 1,
                'department_id' => 1,
                'designation_id' => 1,
                'joining_date' => now()->format('Y-m-d'),
                // You can set remaining fields as '' or null if needed
                'dob' => null,
                'p_country' => null,
                'p_state' => null,
                'p_city' => null,
                'p_pincode' => null,
                'p_address' => '',
                'r_country' => null,
                'r_state' => null,
                'r_city' => null,
                'r_pincode' => null,
                'r_address' => '',
                'aadhar_number' => '',
                'pan_number' => '',
                'bankname' => '',
                'accountnumber' => '',
                'ifsccode' => '',
                'bankaddress' => '',
                'bankdoc1' => '',
                'bankdoc2' => '',
                'role_id' => 1, // or whatever role ID is for Super Admin
            ]
        );

        // Get all permissions
        $permissions = Permission::where('guard_name', 'admin')->get();

        // Create Admin role
        $adminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'admin']);

        // Assign all permissions to admin role
        $adminRole->syncPermissions($permissions);

        // Assign Admin role to the user
        if (!$admin->hasRole('Super Admin')) {
            $admin->assignRole('Super Admin');
        }
    }
}
