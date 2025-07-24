<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::create(['name' => 'admin', 'display_name' => 'Administrateur']);
        Role::create(['name' => 'user', 'display_name' => 'Utilisateur']);
    }
}
