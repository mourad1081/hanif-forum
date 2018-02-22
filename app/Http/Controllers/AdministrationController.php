<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App;

class AdministrationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $categories = App\Category::all();
        $users = App\User::all();
        $grades = App\Grade::all();

        return view('administration', [
            "categories" => $categories,
            "users"      => $users,
            "grades"     => $grades
        ]);
    }

    public function updateCategory(Request $request, Category $category)
    {
        $this->validate($request, [
            "title"       => "string",
            "description" => "string",
            "parent_id"   => "nullable|numeric",
            "min_access_level" => "numeric"
        ]);

        $category->title = $request->input('title');
        $category->description = $request->input('description');
        $category->parent_id = $request->input('parent_id');
        $category->min_access_level = $request->input('min_access_level');
        $category->slug = str_slug($category->id . '_' . $request->input('title'));

        return (string) $category->save();
    }


    public function createCategory(Request $request)
    {
        $this->validate($request, [
            "title"       => "string",
            "description" => "nullable|string",
            "parent_id"   => "nullable|numeric",
            "min_access_level" => "numeric",
        ]);

        $category = new Category([
            "title"            => $request->input('title'),
            "description"      => $request->input('description'),
            "parent_id"        => $request->input('parent_id'),
            "slug"             => uniqid(),
            "min_access_level" => $request->input("min_access_level"),
            "nb_messages"      => 0,
            "nb_discussions"   => 0,
            "visible"          => 1
        ]);

        $category->save();
        $category->slug = str_slug($category->id . '_' . $request->input('title'));
        $category->save();

        return (string) $category->save();
    }

    public function archiveCategory(Category $category)
    {
        $category->visible = 0;
        $subcat_res = Category::where('parent_id', $category->id)->update(["visible" => 0]);

        return (string) ($category->save() and $subcat_res);
    }

    public function unarchiveCategory(Category $category)
    {

        if ($category->parent_id == null or (Category::where('id', $category->parent_id)->first()->visible == 1))
        {
            $category->visible = 1;
            return (string) $category->save();
        }
        else
            // returns -1 when the father isn't visible itself.
            return "-1";
    }
}
