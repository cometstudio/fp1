<?php

namespace App\Models;

class CalendarExercise extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'calendar_id',
        'exercise_id',
        'type'
    ];
}
