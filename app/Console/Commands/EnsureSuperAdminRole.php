<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class EnsureSuperAdminRole extends Command
{
    protected $signature = 'app:ensure-super-admin';
    protected $description = 'Ensure super_admin role exists';

    public function handle()
    {
        $role = Role::firstOrCreate(['name' => 'super_admin']);
        $this->info("✅ super_admin role exists (ID: {$role->id})");

        return Command::SUCCESS;
    }
}
