<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class AssignReminderPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get the reminder permission
        $reminderPermission = Permission::where('key', 'dash_reminders')->first();
        
        if (!$reminderPermission) {
            $this->command->error('Reminder permission not found. Please run ReminderPermissionSeeder first.');
            return;
        }

        // Get all roles and assign the reminder permission
        $roles = Role::all();
        
        foreach ($roles as $role) {
            // Check if role already has this permission
            if (!$role->permissions->contains('id', $reminderPermission->id)) {
                $role->permissions()->attach($reminderPermission->id);
                $this->command->info("Assigned dash_reminders permission to role: {$role->name}");
            } else {
                $this->command->info("Role {$role->name} already has dash_reminders permission");
            }
        }
        
        $this->command->info('Reminder permission assignment completed!');
    }
}