<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::findByName('super_admin', 'api');
        // Create the super admin user
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@moh.com',
            'phone_number' => '0911223388', // Use a valid phone number format
            'password' => Hash::make('admin1234'), // Ensure to set a secure password
            'status' => USER::STATUS_ACTIVE,
        ]);

        // Assign the super admin role to the user
        $user->assignRole($role);
    }
}
