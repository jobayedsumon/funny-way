<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeUsersAndUsersMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable()->change();
        });

        Schema::rename('user_metas', 'users_meta');

        Schema::table('users_meta', function (Blueprint $table) {
            $table->string('image')->nullable()->change();
            $table->string('facebook')->nullable()->change();
            $table->string('twitter')->nullable()->change();
            $table->text('about')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable(false)->change();
        });

        Schema::rename('users_meta', 'user_metas');

        Schema::table('user_metas', function (Blueprint $table) {
            $table->string('image')->nullable(false)->change();
            $table->string('facebook')->nullable(false)->change();
            $table->string('twitter')->nullable(false)->change();
            $table->text('about')->nullable(false)->change();
        });
    }
}
