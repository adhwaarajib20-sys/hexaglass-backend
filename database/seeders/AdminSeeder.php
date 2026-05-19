<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@hexaglass.com'],
            [
                'name'     => 'Super Admin',
                'password' => bcrypt('Admin@123'),
                'no_hp'    => '08123456789',
                'status'   => 'aktif',
            ]
        );
        $adminRole = Role::findByName('admin', 'web');
        $admin->assignRole($adminRole);

        $operator = User::firstOrCreate(
            ['email' => 'operator@hexaglass.com'],
            [
                'name'     => 'Operator 1',
                'password' => bcrypt('Operator@123'),
                'no_hp'    => '08234567890',
                'status'   => 'aktif',
            ]
        );
        $operatorRole = Role::findByName('operator', 'web');
        $operator->assignRole($operatorRole);

        $satpam = User::firstOrCreate(
            ['email' => 'satpam@hexaglass.com'],
            [
                'name'     => 'Satpam 1',
                'password' => bcrypt('Satpam@123'),
                'no_hp'    => '08345678901',
                'status'   => 'aktif',
            ]
        );
        $satpamRole = Role::findByName('satpam', 'web');
        $satpam->assignRole($satpamRole);

        $this->command->info('Akun default berhasil dibuat!');
    }
}