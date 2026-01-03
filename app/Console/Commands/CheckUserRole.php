<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckUserRole extends Command
{
    protected $signature = 'app:check-user {email}';
    protected $description = 'Check user roles and assign super_admin if needed';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found!");
            return Command::FAILURE;
        }

        $this->info("User: {$user->name}");
        $this->info("Email: {$user->email}");
        $this->info("Type: {$user->type}");

        $roles = $user->roles->pluck('name')->toArray();
        $this->info("Roles: " . implode(', ', $roles));

        if (!$user->hasRole('super_admin')) {
            $this->warn("User does NOT have super_admin role!");

            if ($this->confirm('Assign super_admin role?')) {
                $user->assignRole('super_admin');
                $this->info("✅ super_admin role assigned!");
            }
        } else {
            $this->info("✅ User has super_admin role");
        }

        return Command::SUCCESS;
    }
}
