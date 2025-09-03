<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'Super Admin',
            'Manager',
            'Users',

        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(['name' => $role]);
        }
    }
}
