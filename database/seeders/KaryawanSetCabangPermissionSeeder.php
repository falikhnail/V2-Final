<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class KaryawanSetCabangPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari permission group Karyawan
        $permissiongroup = Permission_group::where('name', 'Karyawan')->first();

        if (!$permissiongroup) {
            $this->command->error('Permission group "Karyawan" tidak ditemukan');
            return;
        }

        // Cek apakah permission sudah ada
        $existingPermission = Permission::where('name', 'karyawan.setcabang')->first();

        if (!$existingPermission) {
            Permission::create([
                'name' => 'karyawan.setcabang',
                'id_permission_group' => $permissiongroup->id
            ]);

            $this->command->info('Permission karyawan.setcabang berhasil ditambahkan');

            // Berikan permission ke role ID 1 (admin)
            $roleID = 1;
            $role = Role::findById($roleID);
            if ($role) {
                $role->givePermissionTo('karyawan.setcabang');
                $this->command->info('Permission karyawan.setcabang diberikan ke role admin');
            }
        } else {
            $this->command->info('Permission karyawan.setcabang sudah ada');
        }
    }
}
