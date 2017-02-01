<?php

namespace App\Models;

use Illuminate\Http\Request;

class Article extends Model
{
    protected $resizerConfigSet = 'dirs.calendar';
    public $orderByDefault = ['start_at', 'DESC'];

    protected $fillable = [
        'name',
        'text',
        'collect_article',
        'gallery',
        'gallery_titles',
        'start_at',
    ];

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

    public function afterSave($attrubutes = [])
    {
        // Push this record in the calendar
        if(!empty($attrubutes['publish'])){

            if(!$calendar = (new Calendar)->day($this->start_at)){
                $calendar = Calendar::create([
                    'start_at'=>$this->start_at
                ]);
            }

            if($calendar){
                if(!empty($calendar->title)) throw new \Exception('День уже имеет заголовок');
                if(!empty($calendar->text)) throw new \Exception('День уже имеет текст');
                if(!empty($calendar->gallery)) throw new \Exception('День уже имеет фотогалерею');

                $calendar->title = $attrubutes['name'];
                $calendar->text = $attrubutes['text'];
                $calendar->gallery = $attrubutes['gallery'];
                $calendar->collect_article = 1;

                $calendar->save();

                $this->destroy($this->id);
            }else throw new \Exception('Не создан день календаря');
        }
    }
}
