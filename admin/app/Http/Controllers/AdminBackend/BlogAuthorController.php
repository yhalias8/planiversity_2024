<?php

namespace App\Http\Controllers\AdminBackend;

use App\Http\Controllers\Controller;
use App\Models\BlogAuthor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class BlogAuthorController extends Controller
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
            $data =  BlogAuthor::select('id', 'author_name', 'photo', 'slug', 'status', 'description', 'seo_title', 'seo_description')->orderBy('id', 'DESC')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
        return view('backend.pages.main.admin.blog_author');
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {

            $user = auth()->user();

            $request['slug'] = Str::slug($request->slug, '-');

            $validate = $request->validate([
                'author_name' => 'required|unique:blog_authors|max:255',
                'slug' => 'required|unique:blog_authors|max:255',
                'status' => 'required',
                'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $file_name = null;
            $cpath = "uploads/images/blog/author/";

            if ($request->hasfile('image')) {
                $image = $request->file('image');
                $key = Str::random(40);
                $file_name = $key . '-' . time() . '.' . $image->getClientOriginalExtension();
                $this->imageUpload($image, $cpath, $file_name);
            }

            $blog = new BlogAuthor();
            $blog->author_name = Str::of($request->author_name)->trim();
            $blog->slug = Str::slug($request->slug, '-');
            $blog->photo = $file_name;
            $blog->status = $request->status;
            $blog->description = $request->description;
            $blog->seo_title = $request->seo_title;
            $blog->seo_description = $request->seo_description;
            $saved = $blog->save();

            if ($saved) {
                return response()->json([
                    'success' => true,
                    'message' => "Successfully Author Added",
                ]);
            } else {
                return response()->json('Something went wrong', 422);
            }
        }
    }

    public function getAuthorData()
    {

        $data = BlogAuthor::select('id', 'author_name')
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
                'author_name' => 'required|max:255|unique:blog_authors,author_name,' . $request->id,
                'slug' => 'required|max:255|unique:blog_authors,slug,' . $request->id,
            ]);

            $file_name = null;
            $cpath = "uploads/images/blog/author/";
            $user = auth()->user();

            $blog = BlogAuthor::findorfail($request->id);

            if ($request->hasfile('image')) {

                $image = $request->file('image');
                $key = Str::random(40);
                $file_name = $key . '-' . time() . '.' . $image->getClientOriginalExtension();
                $this->imageUpload($image, $cpath, $file_name);

                if (File::exists(public_path('storage/uploads/images/blog/author/' . $blog->photo))) {
                    File::delete(public_path('storage/uploads/images/blog/author/' . $blog->photo));
                }
            } else {
                $file_name = $request->current_image;
            }

            $blog->author_name = Str::of($request->author_name)->trim();
            $blog->slug = Str::slug($request->slug, '-');
            $blog->photo = $file_name;
            $blog->status = $request->status;
            $blog->description = $request->description;
            $blog->seo_title = $request->seo_title;
            $blog->seo_description = $request->seo_description;
            $saved = $blog->save();

            if ($saved) {
                return response()->json([
                    'success' => true,
                    'message' => "Successfully Author Updated",
                ]);
            } else {
                return response()->json('Something went wrong', 422);
            }
        }
    }


    private function imageUpload($image, $path, $file_name)
    {
        $photo = Image::make($image)
            ->resize(350, 300)
            ->encode('jpg', 100);

        Storage::disk('public')->put($path . $file_name, $photo);
    }




    public function destroy(Request $request)
    {

        if ($request->ajax()) {

            $validate = $request->validate([
                'id' => 'required',
            ]);

            $blog = BlogAuthor::find($request->id);
            if (!is_null($blog)) {
                if (File::exists(public_path('storage/uploads/images/blog/author/' . $blog->photo))) {
                    File::delete(public_path('storage/uploads/images/blog/author/' . $blog->photo));
                }

                $saved = $blog->delete();
            }
            if ($saved) {
                return response()->json('Successfully deleted');
            } else {
                return response()->json('Something went wrong', 422);
            }
        }
    }
}
