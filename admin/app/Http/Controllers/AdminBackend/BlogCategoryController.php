<?php

namespace App\Http\Controllers\AdminBackend;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class BlogCategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data =  BlogCategory::select('id', 'category_name', 'slug', 'status', 'seo_title', 'seo_description')->orderBy('id', 'DESC')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
        return view('backend.pages.main.admin.blog_category');
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {

            $user = auth()->user();

            $request['slug'] = Str::slug($request->slug, '-');

            $validate = $request->validate([
                'category_name' => 'required|unique:blog_categories|max:255',
                'slug' => 'required|unique:blog_categories|max:255',
                'status' => 'required',
            ]);

            $blog_category = new BlogCategory();
            $blog_category->category_name = Str::of($request->category_name)->trim();
            $blog_category->slug = Str::slug($request->slug, '-');
            $blog_category->status = $request->status;
            $blog_category->seo_title = $request->seo_title;
            $blog_category->seo_description = $request->seo_description;
            $saved = $blog_category->save();

            if ($saved) {
                return response()->json([
                    'success' => true,
                    'message' => "Successfully Category Added",
                ]);
            } else {
                return response()->json('Something went wrong', 422);
            }
        }
    }

    public function getCategoryData()
    {

        $data = BlogCategory::select('id', 'category_name')
            ->where('status', 'active')
            ->get();

        return $data;
    }

    public function update(Request $request)
    {

        if ($request->ajax()) {

            $request['slug'] = Str::slug($request->slug, '-');

            $validated = $request->validate([
                'id' => 'required',
                'category_name' => 'required|max:255|unique:blog_categories,category_name,' . $request->id,
                'slug' => 'required|max:255|unique:blog_categories,slug,' . $request->id,
            ]);

            $user = auth()->user();

            $blog_category = BlogCategory::findorfail($request->id);

            $blog_category->category_name = Str::of($request->category_name)->trim();
            $blog_category->slug = Str::slug($request->slug, '-');
            $blog_category->status = $request->status;
            $blog_category->seo_title = $request->seo_title;
            $blog_category->seo_description = $request->seo_description;
            $saved = $blog_category->save();

            if ($saved) {
                return response()->json([
                    'success' => true,
                    'message' => "Successfully Category Updated",
                ]);
            } else {
                return response()->json('Something went wrong', 422);
            }
        }
    }


    public function destroy(Request $request)
    {

        if ($request->ajax()) {

            $validate = $request->validate([
                'id' => 'required',
            ]);

            $blog_category = BlogCategory::find($request->id);
            if (!is_null($blog_category)) {
                $saved = $blog_category->delete();
            }
            if ($saved) {
                return response()->json('Successfully deleted');
            } else {
                return response()->json('Something went wrong', 422);
            }
        }
    }
}
