<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run () {
        $role_jc    = Role::create( [ 'name' => 'jewelcrafter' ] );
        $role_admin = Role::create( [ 'name' => 'administrator' ] );

        $user = new User();
        $user->name = '{"en":"Marwa Saad"}';
        $user->email = 'marwa.saad@thewickfirm.com';
        $user->password =  Hash::make('123');
        $user->save ();

        $user->assignRole ($role_jc);

    }
}