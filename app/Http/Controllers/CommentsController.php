<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Auth;

class CommentsController extends Controller
{
    public function thread($hash = '')
    {
        $comments = Comment::where('hash', '=', $hash)
            ->join('users', 'users.id', '=', 'comments.user_id')
            ->select([
                'comments.*',
                \DB::raw('users.name AS user_name'),
                \DB::raw('users.gallery'),
            ])
            ->orderBy('created_at', 'DESC')
            ->get();

        $view = view('comments.thread', [
            'comments'=>$comments
        ])->render();

        return response()->json([
            'view'=>$view,
        ]);
    }

    public function submit(Request $request, $hash = '')
    {
        $text = $request->input('text');

        if(!empty($hash) && !empty($text)){
            $data = [
                'hash'=>$hash,
                'text'=>$request->input('text')
            ];

            if(Auth::check()) $data['user_id'] = Auth::user()->id;

            Comment::create($data);
        }

        $comments = Comment::where('hash', '=', $hash)
            ->join('users', 'users.id', '=', 'comments.user_id')
            ->select([
                'comments.*',
                \DB::raw('users.name AS user_name'),
                \DB::raw('users.gallery'),
            ])
            ->orderBy('created_at', 'DESC')
            ->get();

        $view = view('comments.thread', [
            'comments'=>$comments
        ])->render();

        return response()->json([
            'view'=>$view,
        ]);
    }
}
