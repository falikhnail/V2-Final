<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Jabatanpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat permission group jika belum ada
        $permissiongroup = Permission_group::firstOrCreate([
            'name' => 'Jabatan'
        ]);

        // Daftar permission
        $permissionNames = [
            'jabatan.index',
            'jabatan.create',
            'jabatan.edit',
            'jabatan.show',
            'jabatan.delete',
        ];

        foreach ($permissionNames as $permName) {
            // Cek apakah permission sudah ada
            Permission::firstOrCreate(
                ['name' => $permName, 'guard_name' => 'web'],
                ['id_permission_group' => $permissiongroup->id]
            );
        }

        // Ambil semua permission dalam group ini
        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();

        // Beri semua permission ini ke role ID 1
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}
