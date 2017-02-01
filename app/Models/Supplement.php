<?php

namespace App\Models;


class Supplement extends Model
{
    protected $fillable = [
        'name',
        'protein',
        'fat',
        'carbohydrates',
        'energy',
    ];
}
