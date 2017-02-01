<?php

namespace App\Models;

class RecipeSupplement extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'recipe_id',
        'supplement_id',
        'weight'
    ];
}
