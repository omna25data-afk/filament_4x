<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'create:admin-user';
    
    protected $description = 'Create an admin user for Filament';

    public function handle()
    {
        $user = User::create([
            'username' => 'filament_admin',
            'email' => 'filament@example.com',
            'password' => Hash::make('password'),
            'full_name_ar' => 'Filament Admin',
            'role' => 'admin',
            'is_active' => 1,
        ]);

        $this->info('Admin user created successfully!');
        $this->info('Email: filament@example.com');
        $this->info('Password: password');
        
        return 0;
    }
}
