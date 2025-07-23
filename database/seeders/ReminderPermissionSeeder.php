<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class ReminderPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create reminder permission if it doesn't exist
        $permission = Permission::firstOrCreate([
            'key' => 'dash_reminders'
        ]);

        if ($permission->wasRecentlyCreated) {
            $this->command->info('Created permission: dash_reminders');
        } else {
            $this->command->info('Permission already exists: dash_reminders');
        }
    }
}