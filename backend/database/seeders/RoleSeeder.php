<?php

namespace Database\Seeders;

use App\RolesEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use function Illuminate\Support\enum_value;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminRole = Role::firstOrCreate(['name' => enum_value(RolesEnum::SUPER_ADMIN)]);
        $userRole = Role::firstOrCreate(['name' => enum_value(RolesEnum::USER)]);

        $permissions = [
            // IP address management
            'ip.view_any',
            'ip.create',
            'ip.update_own',
            'ip.update_any',
            'ip.delete_own',
            'ip.delete_any',

            // Audit logs
            'audit_logs.view',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(
                ['name' => $permissionName, 'guard_name' => 'api'],
                ['name' => $permissionName, 'guard_name' => 'api'],
            );
        }

        $superAdminRole->syncPermissions([
            'ip.view_any',
            'ip.create',
            'ip.update_own',
            'ip.update_any',
            'ip.delete_own',
            'ip.delete_any',
            'audit_logs.view',
        ]);

        $userRole->syncPermissions([
            'ip.view_any',
            'ip.create',
            'ip.update_own',
        ]);
    }
}
