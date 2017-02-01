<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Resizer;
use Illuminate\Support\Str;

class MyController extends Controller
{
    protected $css = 'my';

    public function index()
    {
        $title = 'Персональные данные';

        return view(
            'my.index', [
                'css'=>$this->css,
                'title'=>$title,
            ]
        );
    }

    public function save(Request $request)
    {
        $user = Auth::user();

        $resizerConfig = $user->getResizerConfigSet();

        $picture = $request->file('_picture');

        // If a profile picture has been sent
        if(!empty($picture)){

            if(!empty($user->gallery)) Resizer::deleteImages($user->gallery, false, $resizerConfig);


            $name = Str::random(24);

            Resizer::addImage($picture->getPathname(), $name, false, $resizerConfig);

            $user->gallery = Resizer::galleryString([$name]);

            $user->touch();

            $user->update();

        // Store other data
        }else{
            // Validate input
            $this->validate($request, $user->getValidationRules(), $user->getValidationMessages());

            $data = $request->all();

            // Password change
            if($request->has('_password')){
                $user->password = bcrypt($request->input('_password'));
            }

            $user->update($data);
        }

        return response()->json([
            'picture'=>'/images/thumbs/'.$user->getThumbnail().'.jpg'
        ]);
    }

    public function unlinkPicture()
    {
        $user = Auth::user();

        $resizerConfig = $user->getResizerConfigSet();

        if(!empty($user->gallery)){
            Resizer::deleteImages($user->gallery, false, $resizerConfig);
        }

        $user->gallery = '';

        $user->update();

        return response()->json([
            'picture'=>'/images/thumbs/'.$user->getThumbnail().'.jpg'
        ]);
    }

    public function delete()
    {
        $user = Auth::user();

        $redirectURL = $user->deleteProfile() ? '/' : '/my';

        return redirect($redirectURL);
    }
}
