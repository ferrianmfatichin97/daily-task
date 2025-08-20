<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Symfony\Component\Finder\Finder;

class SyncShieldPermissions extends Command
{
    protected $signature = 'shield:sync-resources';
    protected $description = 'Scan semua Filament Resource, buat permission yang belum ada, dan assign ke role';

    public function handle()
    {
        $this->info('🔍 Scanning Filament Resources...');

        $resourcePath = app_path('Filament/Resources');
        $finder = new Finder();
        $finder->files()->in($resourcePath)->name('*Resource.php');

        $permissionPrefixes = config('filament-shield.permission_prefixes.resource', [
            'view',
            'view_any',
            'create',
            'update',
            'restore',
            'restore_any',
            'replicate',
            'reorder',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
        ]);

        $createdPermissions = [];

        foreach ($finder as $file) {
            $className = $file->getBasename('.php');
            $resourceName = Str::replaceLast('Resource', '', $className);
            $permissionNameBase = Str::snake($resourceName);

            foreach ($permissionPrefixes as $prefix) {
                $perm = "{$prefix}_{$permissionNameBase}";
                if (!Permission::where('name', $perm)->exists()) {
                    Permission::create(['name' => $perm]);
                    $createdPermissions[] = $perm;
                    $this->line("✅ Created permission: {$perm}");
                }
            }
        }

        // Assign ke super_admin
        $superAdmin = Role::where('name', 'super_admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo(Permission::all());
            $this->info("🎯 All permissions assigned to super_admin");
        }

        // Assign minimal view ke employee
        $employee = Role::where('name', 'employee')->first();
        if ($employee) {
            $employee->givePermissionTo(
                Permission::where(function ($q) {
                    $q->where('name', 'like', 'view_%')
                      ->orWhere('name', 'like', 'view_any_%');
                })->pluck('name')->toArray()
            );
            $this->info("👷 Minimal view permissions assigned to employee");
        }

        $this->newLine();
        $this->info("🎉 Sync selesai! Total permission baru: " . count($createdPermissions));
    }
}
