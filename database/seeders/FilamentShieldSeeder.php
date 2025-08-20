<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Symfony\Component\Finder\Finder;

class FilamentShieldSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🔍 Generating permissions from Filament Resources...');

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

        foreach ($finder as $file) {
            $className = $file->getBasename('.php');
            $resourceName = Str::replaceLast('Resource', '', $className);
            $permissionBase = Str::snake($resourceName);

            foreach ($permissionPrefixes as $prefix) {
                Permission::firstOrCreate(['name' => "{$prefix}_{$permissionBase}"]);
            }
        }

        $this->command->info('✅ Permissions synced!');
    }
}
