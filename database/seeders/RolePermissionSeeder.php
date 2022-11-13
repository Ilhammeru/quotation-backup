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
        Role::findByName('manager')->delete();
        Role::findByName('supervisor')->delete();
        Role::findByName('staff')->delete();

        Permission::findByName('manage-setting')->delete();
        Permission::findByName('create-material')->delete();
        Permission::findByName('update-material')->delete();
        Permission::findByName('show-material')->delete();
        Permission::findByName('delete-material')->delete();
        Permission::findByName('update-price')->delete();

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
