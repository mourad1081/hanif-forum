<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Auth;

class ProfileController extends Controller
{
    //

    public function index()
    {
        $posts = App\ViewMessage::where('user_id', Auth::user()->id)->get();
        $discussions = App\Discussion::where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->get();
        $nextGrade = App\Grade::where('min_posts', '>' , Auth::user()->total_answers)->first();
        $percentage = (Auth::user()->total_answers / $nextGrade->min_posts) * 100.0;

        return view('profile', [
            "posts" => $posts,
            "discussions" => $discussions,
            "percentage"  => $percentage,
            "nextGrade"   => $nextGrade
        ]);
    }

    public function getProfile(App\User $user)
    {
        $posts = App\ViewMessage::where('user_id', $user->id)->get();
        $discussions = App\Discussion::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        $nextGrade = App\Grade::where('min_posts', '>' , $user->total_answers)->first();
        $percentage = (Auth::user()->total_answers / $nextGrade->min_posts) * 100.0;

        return view('profile', [
            "posts" => $posts,
            "discussions" => $discussions,
            "percentage"  => $percentage,
            "nextGrade"   => $nextGrade
        ]);
    }
}
