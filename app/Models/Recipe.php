<?php

namespace App\Models;

class Recipe extends Model
{
    protected $resizerConfigSet = 'dirs.recipe';

    protected $fillable = [
        'name',
        'notice',
        'text',
        'size',
        'gallery',
        'gallery_titles',
    ];

    public function meal()
    {
        return $this->hasOne('Models/Meal', 'id', 'meal_id');
    }

    /**
     * @return $this
     */
    public function supplements()
    {
        return $this->belongsToMany('App\Models\Supplement', 'recipe_supplements', 'recipe_id', 'supplement_id')
            ->select([
                'supplements.*',
                'recipe_supplements.weight',
            ])
            ->orderBy('supplements.name', 'ASC')
            ->withPivot('id');
    }

    public function macros()
    {
        $macros = [
            'protein'=>0,
            'fat'=>0,
            'carbohydrates'=>0,
            'energy'=>0,
            'protein_f'=>0,
            'fat_f'=>0,
            'carbohydrates_f'=>0,
            'energy_f'=>0,
        ];

        $supplements = $this->supplements()->get();

        if(!empty($supplements) && $supplements->count()){

            foreach($supplements as $supplement){
                foreach($macros as $key=>$value){
                    if(!empty($supplement->$key)){
                        $absMacros = intval($supplement->$key * $supplement->weight / 100);
                        $macros[$key] += $absMacros;
                        $macros[$key.'_f'] += intval($absMacros * 0.75);
                    }
                }
            }
        }

        return $macros;
    }

    public function getOptions()
    {
        $supplements = Supplement::orderBy('name', 'DESC')->get();

        $recipeSupplements = $this->supplements()->get();

        return compact(
            'supplements',
            'recipeSupplements'
        );
    }
}
