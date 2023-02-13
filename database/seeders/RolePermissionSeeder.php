<?php

namespace Database\Seeders;

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
    public function run() {

        $permissions = [
            [ 'name' => 'view-users' ] ,
            [ 'name' => 'manage-users' ] ,
            [ 'name' => 'view-permissions' ] ,
            [ 'name' => 'manage-permissions' ] ,
            [ 'name' => 'view-roles' ] ,
            [ 'name' => 'manage-roles' ] ,

        ];
        foreach ( $permissions as $permission ) {
            Permission::updateOrCreate( $permission );

        }

        $role = Role::findByName('jewelcrafter');

        $role->syncPermissions($permissions);

        $role = Role::findByName('administrator');

        $role->syncPermissions($permissions);
    }
}
