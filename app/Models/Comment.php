<?php

namespace App\Models;

class Comment extends Model
{
    protected $fillable = [
        'hash',
        'name',
        'user_id',
        'text',
    ];

    public function hash($identifier = '')
    {
        return md5($identifier);
    }
}
