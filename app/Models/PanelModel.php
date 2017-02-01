<?php

namespace App\Models;

class PanelModel extends Model
{
    protected $fillable = [
        'name',
        'public_model_name',
        'grid_item_view',
        'sortable',
        'has_gallery',
        'custom_url',
    ];

    public function getValidationRules()
    {
        return [
            'name'=>'required',
            'public_model_name'=>'required_without_all:custom_url',
        ];
    }

    public function getValidationMessages()
    {
        return [
            'name.required'=>'Укажите название модели',
            'public_model_name.required_without_all'=>'Укажите alias модели',
        ];
    }
}
