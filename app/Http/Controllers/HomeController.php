<?php

namespace App\Http\Controllers;

use App\Category;
use App\Discussion;
use App\Post;
use Illuminate\Http\Request;
use App;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     * a
     */
    public function index()
    {
        /** @var Category[] $cats */
        $cats = App\Category::where('visible', '1')
                            ->orderBy('min_access_level')
                            ->orderBy('priority')
                            ->get();

        return view('home')->with([
            "categories"       => self::generateHierarchyWithoutDepth($cats),
            "discussions"      => null,
            "current_category" => null
        ]);
    }

    public function createDiscussion(Request $request, Category $cat)
    {
        $this->validate($request, [
            "title" => "required|string",
            "content" => "required|string"
        ]);

        $discussion = new Discussion([
            "title" => $request->input('title'),
            "content" => $request->input('content'),
            "user_id" => Auth::user()->id,
            "category_id" => $cat->id
        ]);


        $ret = $discussion->save();

        if ($ret)
        {
            $cat->nb_discussions++;
            $cat->last_discussion_id = $discussion->id;
            $cat->last_discussion_title  = $discussion->title;
            $cat->last_discussion_author = Auth::user()->pseudo;
            $cat->last_discussion_created_at  = $discussion->created_at;
        }

        return ($ret and $cat->save()) ?
                redirect('/discussions/' . $discussion->id) :
                redirect()->back(503);
    }

    public function createDiscussionView(Category $cat)
    {
        return view('home', [
            "current_category" => $cat
        ]);
    }


    public function getSection(Category $cat)
    {
        // On ne peut pas accéder à une catégorie VIP si on n'a pas le droit et
        // On ne peut pas accéder à une catégorie Admin si on n'a pas le droit
        if (($cat->isVIPCategory() and !Auth::user()->isAtLeastVIP())
            or ($cat->isAdminCategory() and !Auth::user()->isAdmin()))
        {
            return abort(404);
        }

        $cats = App\Category::where('parent_id', $cat->id)->orWhere('id', $cat->id);
        $cats = $cats->where('visible', '1')->get();

        $discussions = App\ViewDiscussion::where('discussion_category_id', $cat->id)
                                         ->orderByDesc('discussion_sticked_at_top')
                                         ->orderByDesc('discussion_updated_at')
                                         ->get();
        $current_category = $cat;

        return view('home')->with([
            "categories"       => self::generateHierarchyWithoutDepth($cats, $cat->parent_id),
            "discussions"      => $discussions,
            "current_category" => $current_category
        ]);
    }

    public function getDiscussion(Discussion $disc)
    {
        $posts = App\ViewMessage::where('discussion_id', $disc->id)
                                    ->orderBy('created_at')
                                    ->get();

        $author = App\User::where('id', $disc->user_id)->first();
        $current_category = App\Category::whereId($disc->category_id)->first();
        $disc->views++;
        $disc->save();

        return view('home')->with([
            "current_discussion" => $disc,
            "current_category" => $current_category,
            "author" => $author,
            "posts" => $posts
        ]);
    }

    /**
     * @param Request $request
     * @param Discussion $discussion
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postMessage(Request $request, Discussion $discussion)
    {
        $this->validate($request, [
            "content" => "required|string"
        ]);
        $correspondingCategory = App\Category::where('id', $discussion->category_id)->first();

        $post = new App\Post([
            "discussion_id" => $discussion->id,
            "content"       => $request->input("content"),
            "likes"         => 0,
            "user_id"       => Auth::user()->id
        ]);

        Auth::user()->total_answers++;
        $discussion->answers++;
        $discussion->last_post_id = Auth::user()->id;
        $correspondingCategory->nb_messages++;

        // on met à jour le grade
        $grade = App\Grade::where('min_posts', '<=', Auth::user()->total_answers)
                            ->orderBy('min_posts', 'desc')
                            ->first();

        Auth::user()->grade = $grade->title;

        return ($post->save() and $discussion->save() and $correspondingCategory->save() and Auth::user()->save()) ?
            redirect()->back() :
            abort(503);
    }


    /**
     * @param Request $request
     * @param Post $post
     * @return string
     */
    public function updateMessage(Request $request, Post $post)
    {
        $this->validate($request, [
            "content" => "required|string"
        ]);
        $post->content = $request->input('content');

        return (string) $post->save();

    }


    /**
     *
     * @param array $categories
     * @param int $root_value
     * @return array
     */
    private static function generateHierarchy($categories, $root_value = 0) {
        $refs = array();
        $list = array();

        foreach ($categories as $category)
        {
            $ref = & $refs["" . $category->id];
            $ref['parent_id'] = $category->parent_id;
            $ref['value']     = $category;

            if ($category->parent_id == $root_value)
                $list["" . $category->id] = & $ref;
            else
                $refs[$category->parent_id]['children']["" . $category->id] = & $ref;
        }

        return $list;
    }


    /**
     * @param Category[] $categories
     * @param null|int $root_value
     * @return array
     */
    private static function generateHierarchyWithoutDepth($categories, $root_value = null) {
        $list = array();
        // Step 1 - on rajoute les root
        foreach ($categories as $category)
            if ($category->parent_id == $root_value)
                $list[$category->id] =  ["value" => $category, "children" => []];

        // Step 2 - on fill les root avec leur fils direct
        foreach ($categories as $category)
            if ($category->parent_id != $root_value and key_exists($category->parent_id, $list))
                $list[$category->parent_id]["children"][] = $category;

        return $list;
    }
}
