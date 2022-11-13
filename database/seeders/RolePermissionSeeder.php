<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $manager = Role::findByName('manager');
        // if ($manager) {
        //     $manager->delete();
        // }
        // $supervisor = Role::findByName('supervisor');
        // if ($supervisor) {
        //     $supervisor->delete();
        // }
        // $staff = Role::findByName('staff');
        // if ($staff) {
        //     $staff->delete();
        // }

        // $manage_setting = Permission::findByName('manage-setting');
        // if ($manage_setting) {
        //     $manage_setting->delete();
        // }
        // $create_material = Permission::findByName('create-material');
        // if ($create_material) {
        //     $$create_material->delete();
        // }
        // $upload_material = Permission::findByName('update-material');
        // if ($upload_material) {
        //     $upload_material->delete();
        // }
        // $show_material = Permission::findByName('show-material');
        // if ($show_material) {
        //     $show_material->delete();
        // }
        // $delete_material = Permission::findByName('delete-material');
        // if ($delete_material) {
        //     $delete_material->delete();
        // }
        // $update_price = Permission::findByName('update-price');
        // if ($update_price) {
        //     $update_price->delete();
        // }

        Role::create(['name' => 'manager']);
        Role::create(['name' => 'supervisor']);
        Role::create(['name' => 'staff']);

        Permission::create(['name' => 'manage-setting']);
        Permission::create(['name' => 'create-material']);
        Permission::create(['name' => 'update-material']);
        Permission::create(['name' => 'show-material']);
        Permission::create(['name' => 'delete-material']);
        Permission::create(['name' => 'update-price']);

        // assign permission to role
        $user = User::where('email', 'admin@gmail.com')->first();
        $allPermission = Permission::all();
        foreach ($allPermission as $permission) {
            $role = Role::findByName('manager');
            $role->givePermissionTo($permission);

        }
        //asign role to dummy user
        $user->assignRole('manager');
    }
}
