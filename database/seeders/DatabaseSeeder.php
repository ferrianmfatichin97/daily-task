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
        $gm         = Role::firstOrCreate(['name' => 'gm', 'guard_name' => 'web']);
        $manager    = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $staff      = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $hrd        = Role::firstOrCreate(['name' => 'hrd', 'guard_name' => 'web']);

        // 2️⃣ Generate permission via Shield (kalau masih kosong)
        if (Permission::count() === 0) {
            $this->call(\BezhanSalleh\FilamentShield\FilamentShieldSeeder::class);
        }

        // 3️⃣ Assign permission
        $superAdmin->givePermissionTo(Permission::all());
        $gm->givePermissionTo(Permission::all());
        $manager->givePermissionTo(Permission::all());
        $staff->givePermissionTo([
            'view_task', 'view_any_task', 'create_task', 'update_task',
        ]);

        // 4️⃣ Super Admin
        $admin = User::firstOrCreate(
            ['email' => 'adminsuper@bankdptaspen.co.id'],
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
            ['name' => 'Dicky Hendrik', 'email' => 'hendrik@bankdptaspen.co.id'],
            ['name' => 'Erlangga Ahmad',   'email' => 'erlangga@bankdptaspen.co.id'],
            ['name' => 'Jonathan Yasi',    'email' => 'jonathan@bankdptaspen.co.id'],
            ['name' => 'Anggi Anggreani',    'email' => 'anggi@bankdptaspen.co.id'],
            ['name' => 'Olivia Regina Sebayang',    'email' => 'olivia@bankdptaspen.co.id'],
        ];

        foreach ($employees as $emp) {
            $user = User::firstOrCreate(
                ['email' => $emp['email']],
                [
                    'name' => $emp['name'],
                    'password' => bcrypt('12345678'),
                ]
            );
            $user->assignRole($staff);
        }

        // 7️⃣ GM User (Contoh)
        $gmUser = User::firstOrCreate(
            ['email' => 'fauzihasan@bankdptaspen.co.id'],
            [
                'name' => 'Fauzi Hasan',
                'password' => bcrypt('12345678'),
            ]
        );
        $gmUser->assignRole($gm);
    }
}
