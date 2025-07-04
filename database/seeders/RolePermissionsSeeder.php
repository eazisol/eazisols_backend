<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create new permissions for roles and permissions management
        $permissions = [
            'dash_roles' => 'Access to manage roles',
            'dash_permissions' => 'Access to manage permissions'
        ];

        foreach ($permissions as $key => $description) {
            Permission::firstOrCreate(['key' => $key]);
            $this->command->info("Permission '{$key}' created or already exists.");
        }

        // Find admin role (assuming it exists)
        $adminRole = Role::where('name', 'Admin')->first();

        if ($adminRole) {
            // Get the permission IDs
            $permissionIds = Permission::whereIn('key', array_keys($permissions))->pluck('id')->toArray();
            
            // Attach the permissions to admin role (without duplicates)
            $adminRole->permissions()->syncWithoutDetaching($permissionIds);
            
            $this->command->info('Roles and permissions management permissions assigned to Admin role.');
        } else {
            $this->command->error('Admin role not found. Please create it first.');
        }
    }
} 