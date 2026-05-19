<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['admin', 'operator', 'satpam', 'supir'];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name'       => $role,
                'guard_name' => 'web',
            ]);
        }

        $this->command->info('Roles berhasil dibuat!');
    }
}