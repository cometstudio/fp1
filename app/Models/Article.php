<?php

namespace App\Models;

use App\Providers\Resizer\Resizer;
use Illuminate\Http\Request;
use Auth;

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
        'collect_gallery',
        'video',
        'collect_video',
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
                if(!empty($calendar->video)) throw new \Exception('День уже имеет видео');

                $calendar->title = $attrubutes['name'];
                $calendar->text = $attrubutes['text'];
                $calendar->collect_article = $attrubutes['collect_article'];
                if(!empty($attrubutes['collect_article'])) $calendar->article_published_by = Auth::user()->id;
                $calendar->gallery = $attrubutes['gallery'];
                $calendar->gallery_titles = Resizer::galleryTitlesString($attrubutes['galleryTitles']);
                $calendar->collect_gallery = $attrubutes['collect_gallery'];
                $calendar->video = $attrubutes['video'];
                $calendar->collect_video = $attrubutes['collect_video'];

                $calendar->save();

                $this->destroy($this->id);
            }else throw new \Exception('Не создан день календаря');
        }
    }
}
