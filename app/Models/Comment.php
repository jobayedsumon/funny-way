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

    public function replies()
    {
        return $this->children()->with(__FUNCTION__);
    }
}
