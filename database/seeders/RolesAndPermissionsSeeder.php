<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Load permissions from config
        $permissionsConfig = config('roles_permissions.permissions');
        $rolesConfig = config('roles_permissions.roles');
        
        // Create permissions
        foreach ($permissionsConfig as $feature => $permissions) {
            foreach ($permissions as $permission) {
                Permission::create(['name' => "{$feature}.{$permission}", 'guard_name' => 'api']);
            }
        }

        // Create roles and assign permissions
        foreach ($rolesConfig as $role => $permissions) {
            // Create the role
            $roleModel = Role::create(['name' => $role, 'guard_name' => 'api']);

            // Assign permissions to the role
            foreach ($permissions as $feature => $perms) {
                foreach ($perms as $perm) {
                    $permission = Permission::where('name', "{$feature}.{$perm}")->first();
                    if ($permission) {
                        $roleModel->givePermissionTo($permission);
                    }
                }
            }
        }
    }
}
