<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Auth;
use Intervention\Image\ImageManagerStatic as Image;
use Ramsey\Uuid\Uuid;

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
            "user" => Auth::user(),
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
        $percentage = ($user->total_answers / $nextGrade->min_posts) * 100.0;

        return view('profile', [
            "user" => $user,
            "posts" => $posts,
            "discussions" => $discussions,
            "percentage"  => $percentage,
            "nextGrade"   => $nextGrade
        ]);
    }

    /**
     *
     * @param Request $request
     * @return string
     */
    public function updateAbout(Request $request)
    {
        $this->validate($request, [
            "about" => "required|string"
        ]);

        Auth::user()->about = $request->input("about");
        return (string) Auth::user()->save();
    }

    /**
     * @param Request $request
     * @return array|string l'URI de l'image uploadée si la création a bien été effectuée
     */
    public function uploadImage(Request $request) {

        $file     = $request->file('file');
        $thumbnail = Image::make($file)->resize(null, 200, function ($constraint) { $constraint->aspectRatio(); });
        $newName   = Uuid::uuid4()->toString();
        $path      = 'profile-pictures/';
        $uri = $path . 'thumbs/' . $newName . '.jpg';

        // Create all dirs in path if not exists :
        self::makedirs($path . 'thumbs/');

        $thumbnail->encode('jpg', 70)->save($uri);

        Auth::user()->picture = $uri;
        return (string) Auth::user()->save();
    }

    private static function makedirs($dirpath, $mode = 0755) {
        return is_dir($dirpath) || mkdir($dirpath, $mode, true);
    }
}



