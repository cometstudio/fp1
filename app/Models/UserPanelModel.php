<?php

namespace App\Models;

class UserPanelModel extends Model
{
    protected $fillable = [
        'user_id',
        'panel_model_id',
        'c',
        'r',
        'u',
        'd',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function panelModel()
    {
        return $this->belongsTo('App\Models\PanelModel');
    }
}
