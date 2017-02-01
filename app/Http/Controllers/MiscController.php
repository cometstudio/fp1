<?php

namespace App\Http\Controllers;

use App\Models\Misc;
use App\Models\Sitemap;
use Illuminate\Http\Request;

use App\Http\Requests;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MiscController extends Controller
{
    protected $css = 'misc';

    public function item($alias = 'directory', $subalias = '')
    {
        $misc = Misc::where('alias', '=', (empty($subalias) ? $alias : $subalias))->first();

        $parent = (!empty($misc->parent_id)) ? Misc::where('id', '=', $misc->parent_id)->firstOrFail() : null;

        if(empty($misc)
            || (!empty($misc->parent_id)
                    && (empty($subalias) || ($parent->alias != $alias))
                )
        ){
            return abort(404);
        }else{
            $template = !empty($misc->template) ? $misc->template : 'misc.item';

            $gallery = $misc->getGallery();

            return view(
                $template, [
                    'css'=>$this->css,
                    'parent'=>$parent,
                    'misc'=>$misc,
                    'gallery'=>$gallery,
                    'title'=>(!empty($misc->title) ? $misc->title : $misc->name),
                ]
            );
        }
    }
}
