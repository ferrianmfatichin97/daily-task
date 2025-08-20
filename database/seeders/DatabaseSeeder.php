<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1️⃣ Buat role
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $manager    = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $employee   = Role::firstOrCreate(['name' => 'employee', 'guard_name' => 'web']);

        // 2️⃣ Generate permission via Shield (kalau masih kosong)
        if (Permission::count() === 0) {
            $this->call(\BezhanSalleh\FilamentShield\FilamentShieldSeeder::class);
        }

        // 3️⃣ Assign permission
        $superAdmin->givePermissionTo(Permission::all());
        $employee->givePermissionTo([
            'view_task', 'view_any_task',
        ]);

        // 4️⃣ Super Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@bankdptaspen.co.id'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
            ]
        );
        $admin->assignRole($superAdmin);

        // 5️⃣ Manager
        $managerUser = User::firstOrCreate(
            ['email' => 'srihandayani@bankdptaspen.co.id'],
            [
                'name' => 'Sri Handayani',
                'password' => bcrypt('12345678'),
            ]
        );
        $managerUser->assignRole($manager);

        // 6️⃣ Employees
        $employees = [
            ['name' => 'Hendrik', 'email' => 'hendrik@bankdptaspen.co.id'],
            ['name' => 'Angga',   'email' => 'angga@bankdptaspen.co.id'],
            ['name' => 'Fani',    'email' => 'fani@bankdptaspen.co.id'],
            ['name' => 'Oliv',    'email' => 'oliv@bankdptaspen.co.id'],
        ];

        foreach ($employees as $emp) {
            $user = User::firstOrCreate(
                ['email' => $emp['email']],
                [
                    'name' => $emp['name'],
                    'password' => bcrypt('12345678'),
                ]
            );
            $user->assignRole($employee);
        }
    }
}
