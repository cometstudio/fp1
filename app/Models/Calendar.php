<?php

namespace App\Models;

use Illuminate\Http\Request;

class Calendar extends Model
{
    protected $table = 'calendar';
    protected $resizerConfigSet = 'dirs.calendar';
    public $orderByDefault = ['start_at', 'DESC'];

    protected $fillable = [
        'title',
        'text',
        'collect_article',
        'gallery',
        'gallery_titles',
        'collect_gallery',
        'video',
        'collect_video',
        'start_at',

    ];

    public function day($startAt = 0)
    {
        if(empty($startAt)) $startAt = time();

        return $this
            ->where('start_at', '>=', mktime(0, 0, 0, date('m', $startAt), date('j', $startAt), date('Y', $startAt)))
            ->where('start_at', '<=', mktime(23, 59, 59, date('m', $startAt), date('j', $startAt), date('Y', $startAt)))
            ->first();
    }

    /**
     * @return $this
     */
    public function recipes()
    {
        return $this->belongsToMany('App\Models\Recipe', 'calendar_recipes', 'calendar_id', 'recipe_id')
            ->join('meals', 'meals.id', '=', 'calendar_recipes.meal_id')
            ->select([
                'recipes.*',
                \DB::raw('meals.name AS meal_name'),
                \DB::raw('meals.id AS meal_id'),
            ])
            ->orderBy('meals.ord', 'DESC')
            ->orderBy('recipes.ord', 'DESC')
            ->withPivot('id');
    }

    public function totalMacros(&$recipes = null)
    {
        $totalMacros = [
            'protein'=>0,
            'fat'=>0,
            'carbohydrates'=>0,
            'energy'=>0,
            'protein_f'=>0,
            'fat_f'=>0,
            'carbohydrates_f'=>0,
            'energy_f'=>0,
        ];

        if(empty($recipes)) $recipes = $this->recipes()->get();

        if(!empty($recipes) && $recipes->count()){
            foreach($recipes as $recipe){
                if($macros = $recipe->macros()){
                    $recipe->macros = $macros;
                    foreach($macros as $key=>$value){
                        $totalMacros[$key] += $value;
                    }
                }
            }
        }

        $dailyValues = config()->has('macros.months.'.date('n', $this->start_at)) ? config()->get('macros.months.'.date('n', $this->start_at)) : config()->get('macros.months.0');
        $dailyCorrections = config()->has('macros.weekdays.'.date('N', $this->start_at)) ? config()->get('macros.weekdays.'.date('N', $this->start_at)) : config()->get('macros.weekdays.0');

        foreach($dailyValues as $ingridient=>$value){
            $totalMacros[$ingridient.'_daily']['value'] = intval($value * (!empty($dailyCorrections[$ingridient]) ? $dailyCorrections[$ingridient] : 1));
            $totalMacros[$ingridient.'_daily']['total'] = intval($totalMacros[$ingridient] * 100 / $totalMacros[$ingridient.'_daily']['value']);
            $totalMacros[$ingridient.'_daily']['active'] = ($totalMacros[$ingridient.'_daily']['total'] < 85) || ($totalMacros[$ingridient.'_daily']['total'] > 115) ? true : false;
        }

        return $totalMacros;
    }

    /**
     * @return $this
     */
    public function exercises()
    {
        return $this->belongsToMany('App\Models\Exercise', 'calendar_exercises', 'calendar_id', 'exercise_id')
            ->orderBy('calendar_exercises.id', 'ASC')
            ->withPivot(['id', 'type']);
    }

    public function cloneWeeklyRecipesTemplate($timestamp = 0)
    {
        try{
            $config = config('macros');

            if(empty($timestamp)) $timestamp = time();

            $weekStartsAt = mktime(0, 0, 0, date('n', $timestamp), date('j', $timestamp), date('Y', $timestamp));

            $skip = (!empty($config['templateWeekNum']) ? $config['templateWeekNum'] : 0) * 7;

            $calendar = Calendar::orderBy('start_at', 'ASC')->skip($skip)->limit(7)->get();

            if(empty($calendar) || !$calendar->count()) throw new \Exception;

            $i = 0;
            foreach($calendar as $day)
            {
                $cloneStartsAt = $weekStartsAt + (86400 * $i);

                if(!$this->where('start_at', '=', $cloneStartsAt)->first()){
                    $clone = $this->create([
                        'start_at'=>$cloneStartsAt,
                    ]);

                    if(!empty($clone->id)){
                        $recipesToClone = $day->recipes()->get();

                        if(!empty($recipesToClone) && $recipesToClone->count()){
                            foreach($recipesToClone as $bind){
                                CalendarRecipe::create([
                                    'meal_id'=>$bind->meal_id,
                                    'calendar_id'=>$clone->id,
                                    'recipe_id'=>$bind->pivot->recipe_id,
                                ]);
                            }
                        }
                    }
                }

                $i++;
            }

            return true;
        }catch (\Exception $e){
            return false;
        }
    }

    public function getOptions()
    {
        $exercises = Exercise::orderBy('name', 'DESC')->get();

        $calendarExercises = $this->exercises()->get();

        $meals = Meal::orderBy('ord', 'DESC')->get();

        $recipes = Recipe::orderBy('name', 'ASC')->get();

        $calendarRecipes = $this->recipes()->get();

        return compact(
            'exercises',
            'meals',
            'recipes',
            'calendarExercises',
            'calendarRecipes'
        );
    }

    public function modifyQueryBuilder(Request $request, &$builder, array $selectColumns = ['*'])
    {
        if($query = $request->get('query')) $builder->where('name', 'LIKE', \DB::raw("'%{$query}%'"));
        if($date = $request->get('_start_from')) $builder->where('start_at', '>=', \Date::getTimeFromDate($date));
        if($date = $request->get('_start_to')) $builder->where('start_at', '<=', \Date::getTimeFromDate($date));

        $builder->select($selectColumns);
    }

    public function beforeSave($attrubutes = [])
    {
        $this->setStartTime($attrubutes);

        return $this;
    }
}
