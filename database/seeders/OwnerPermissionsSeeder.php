<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class OwnerPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // مسح الكاش لضمان اعتماد التحديثات فوراً
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $guardName = 'api';

        // 1. قائمة صلاحيات الملاك الجديدة
        $permissions = [
            'owner.view',
            'owner.create',
            'owner.update',
            'owner.delete',
        ];

        // 2. إنشاء الصلاحيات (فقط إن لم تكن موجودة)
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => $guardName,
            ]);
        }

        // 3. توزيع الصلاحيات على الأدوار الموجودة مسبقاً

        // أ) تحديث الآدمن (Admin)
        $adminRole = Role::where('name', 'Admin')->where('guard_name', $guardName)->first();
        if ($adminRole) {
            $adminRole->givePermissionTo($permissions);
        }

        // ب) تحديث مدخل البيانات (Data Entry)
        $dataEntryRole = Role::where('name', 'Data Entry')->where('guard_name', $guardName)->first();
        if ($dataEntryRole) {
            $dataEntryRole->givePermissionTo([
                'owner.view',
                'owner.create',
                'owner.update',
            ]);
        }

        // ج) تحديث المراجع (Auditor)
        $auditorRole = Role::where('name', 'Auditor')->where('guard_name', $guardName)->first();
        if ($auditorRole) {
            $auditorRole->givePermissionTo([
                'owner.view',
            ]);
        }
    }
}
