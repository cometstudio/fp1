<?php

namespace App\Models;


class Exercise extends Model
{
    protected $fillable = [
        'name',
        'text',
        'notice',
        'notice1',
        'notice2',
        'gallery',
        'gallery_titles',
    ];
}
