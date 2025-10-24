<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class KunjunganPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::firstOrCreate(
            ['name' => 'Kunjungan']
        );

        Permission::firstOrCreate(
            ['name' => 'kunjungan.index'],
            ['id_permission_group' => $permissiongroup->id]
        );

        Permission::firstOrCreate(
            ['name' => 'kunjungan.create'],
            ['id_permission_group' => $permissiongroup->id]
        );

        Permission::firstOrCreate(
            ['name' => 'kunjungan.edit'],
            ['id_permission_group' => $permissiongroup->id]
        );

        Permission::firstOrCreate(
            ['name' => 'kunjungan.delete'],
            ['id_permission_group' => $permissiongroup->id]
        );

        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);

        // Assign only index and create permissions to karyawan role
        $karyawanRole = Role::where('name', 'karyawan')->first();
        if ($karyawanRole) {
            $karyawanRole->givePermissionTo(['kunjungan.index', 'kunjungan.create']);
        }
    }
}
