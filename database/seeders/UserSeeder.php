<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123'),
        ]);

        if (method_exists($admin, 'attachRole')) {
            $admin->attachRole('admin');
        } elseif (method_exists($admin, 'addRole')) {
            $admin->addRole('admin');
        } elseif (method_exists($admin, 'syncRoles')) {
            $admin->syncRoles(['admin']);
        }

        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => bcrypt('password123'),
        ]);
    }
}
