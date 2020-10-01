<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'autor_id',
        'content',
        'tags'
    ];

    protected $with = ['author'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function author()
    {
        return $this->hasOne(User::class, 'id', 'autor_id');
    }
}
