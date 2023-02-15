<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserMeta;
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
        $user->name = 'Marwa Saad';
        $user->email = 'marwa.saad@thewickfirm.com';
        $user->password =  Hash::make('123');
        $user->save ();

        $user->assignRole ($role_jc);

        $user = new User();
        $user->name = 'Jobayed Sumon';
        $user->email = 'jobayed.sumon@thewickfirm.com';
        $user->password =  Hash::make('123');
        $user->save ();

        $user_mata = new UserMeta();
        $user_mata->user_id = $user->id;
        $user_mata->facebook = 'https://www.facebook.com';
        $user_mata->twitter = 'https://www.twitter.com';
        $user_mata->about = 'Software Engineer';

        $user->assignRole ($role_jc);

        $user = new User();
        $user->name = 'Admin';
        $user->email = 'admin@thewickfirm.com';
        $user->password =  Hash::make('123');
        $user->save ();

        $user->assignRole ($role_admin);

    }
}
