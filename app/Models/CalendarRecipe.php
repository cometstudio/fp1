<?php

namespace App\Models;

class CalendarRecipe extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'meal_id',
        'calendar_id',
        'recipe_id'
    ];
}
