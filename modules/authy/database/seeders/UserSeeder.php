<?php

namespace Fpaipl\Authy\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Roles
        foreach (config('panel.roles') as $role) {
            Role::create([
                'name' => $role['id'],
                'guard_name' => 'web',
            ]);
        }

        // Create Admin User
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'pg.softcode@gmail.com',
            'mobile' => '9868252588',
            'utype' => 'mobile',
            'type' => 'admin',
            'password' => bcrypt('password1245'),
            'email_verified_at' => now(),
        ]);

        // Assign All Roles
        foreach (config('panel.roles') as $role) {
            $admin->assignRole($role['id']);
        }

        // If app is in development mode, create test user
        if (config('app.env') !== 'local') {
            return;
        }

        // Create Test User
        $apptest = User::create([
            'name' => 'Ayush Gupta',
            'email' => 'apptest@wsg.in',
            'mobile' => '8860012001',
            'utype' => 'mobile',
            'type' => 'admin',
            'password' => bcrypt('987654321'),
            'email_verified_at' => now(),
        ]);
        
        // Assign All Roles except admin
        foreach (config('panel.roles') as $role) {
            if ($role['id'] === 'admin') {
                continue;
            }
            $apptest->assignRole($role['id']);
        }
    }
}
