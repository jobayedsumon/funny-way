<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    public function children()
    {
        return $this->hasMany( Comment::class, 'parent_id' );
    }

    public function user()
    {
        return $this->belongsTo( User::class );
    }

    public function replies()
    {
        return $this->children()->with(__FUNCTION__, 'user.user_meta', 'likes.user');
    }

    public function likes()
    {
        return $this->hasMany( Like::class );
    }
}
