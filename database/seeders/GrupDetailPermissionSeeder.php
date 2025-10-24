<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class GrupDetailPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari permission group Grup
        $permissiongroup = Permission_group::where('name', 'Grup')->first();

        if ($permissiongroup) {
            // Cek apakah permission sudah ada
            $existingPermission = Permission::where('name', 'grup.detail')->first();

            if (!$existingPermission) {
                Permission::create([
                    'name' => 'grup.detail',
                    'id_permission_group' => $permissiongroup->id
                ]);

                // Berikan permission ke role admin (ID 1)
                $roleID = 1;
                $role = Role::findById($roleID);
                $permission = Permission::where('name', 'grup.detail')->first();
                $role->givePermissionTo($permission);
            }
        }
    }
}
