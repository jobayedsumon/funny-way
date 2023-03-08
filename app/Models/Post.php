<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo( User::class );
    }

    public function image()
    {
        return $this->hasOne( Image::class );
    }

    public function comments()
    {
        return $this->hasMany( Comment::class )->with('user.user_meta');
    }

    public function reacts()
    {
        return $this->hasMany( React::class );
    }

    public function category()
    {
        return $this->belongsTo( Category::class );
    }
}
