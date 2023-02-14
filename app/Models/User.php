<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasRoles, HasFactory, Notifiable;

    public $translatable = [ 'name' ];

    protected $appends = [ 'initials', 'first_name' ];

    protected $table = 'users';

    protected $guard_name = 'web';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function user_meta()
    {
        return $this->hasOne( UserMeta::class );
    }


    public function getFirstNameAttribute() {
        $name       = $this->name;
        $name_array = explode( " ", $name );

        return $name_array[0];
    }

    public function getInitialsAttribute() {
        $name       = $this->name;
        $name_array = explode( " ", $name );
        $initials   = '';
        foreach ( $name_array as $name_word ) {
            $initials .= substr( $name_word, 0, 1 );
        }

        return $initials;
    }
}
