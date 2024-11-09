<?php

namespace App\Http\Controllers\AdminBackend;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class BlogPostController extends Controller
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
            $data =  BlogPost::select('id', 'post_title', 'featured_image', 'post_slug', 'post_status', 'author_id', 'categories')->orderBy('id', 'DESC')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('author_name', function ($row) {
                    return $row->author->author_name;
                })
                ->editColumn('categoryList', function ($row) {
                    $output = null;
                    $cats = explode(",", $row->categories);
                    foreach ($cats as $perm) {
                        $singledata = BlogPost::categoryType($perm);
                        $output[] = $singledata->category_name;
                    }
                    return $output;
                })
                ->rawColumns(['author_name', 'categoryList'])
                ->make(true);
        }
        return view('backend.pages.main.admin.blog_post');
    }

    public function create()
    {
        return view('backend.pages.main.admin.create_blog_post_view');
    }
    public function edit($id)
    {
        $singleData = BlogPost::where('id', $id)->first();
        return view('backend.pages.main.admin.edit_blog_post_view', compact('singleData', 'id'));
    }


    public function store(Request $request)
    {
        if ($request->ajax()) {

            $user = auth()->user();
            $pin_post = 0;
            $file_name = null;
            $cpath = "uploads/images/blog/post/";

            $request['post_slug'] = Str::slug($request->post_slug, '-');

            $validate = $request->validate([
                'post_title' => 'required',
                'post_content' => 'required',
                'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
                'post_slug' => 'required|unique:blog_posts|max:255',
                'post_status' => 'required',
                'author' => 'required',
                'categories' => 'required|array',
                'categories.*' => 'exists:blog_categories,id',
            ]);

            if ($request->hasfile('image')) {
                $image = $request->file('image');
                $key = Str::random(40);
                $file_name = $key . '-' . time() . '.' . $image->getClientOriginalExtension();
                $width = "1280";
                $height = "720";
                $this->imageUpload($image, $cpath, $file_name, $width, $height);
            }

            if (isset($request->pin_post)) {
                $pin_post = $request->pin_post;
            }

            $blog = new BlogPost();
            $blog->post_title = Str::of($request->post_title)->trim();
            $blog->post_content = $request->post_content;
            $blog->post_slug = Str::slug($request->post_slug, '-');
            $blog->seo_keyword = $request->seo_keyword;
            $blog->seo_title = $request->seo_title;
            $blog->seo_description = $request->seo_description;
            $blog->post_status = $request->post_status;
            $blog->post_type = $request->post_type;
            //$blog->published_at = Carbon::parse($request->published_at);
            $blog->published_at = Carbon::createFromFormat('Y-m-d h:i A', $request->published_at);
            $blog->pin_post = $pin_post;
            $blog->featured_image = $file_name;
            $blog->author_id = $request->author;
            $blog->categories = implode(",", $request->categories);
            $blog->user_id = $user->id;
            $saved = $blog->save();

            if ($saved) {
                return response()->json([
                    'success' => true,
                    'message' => "Successfully Blog Post Created",
                ]);
            } else {
                return response()->json('Something went wrong', 422);
            }
        }
    }


    public function update(Request $request)
    {

        if ($request->ajax()) {

            $request['post_slug'] = Str::slug($request->post_slug, '-');

            $validate = $request->validate([
                'post_title' => 'required',
                'post_content' => 'required',
                'image' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
                'post_slug' => 'required|max:255|unique:blog_posts,post_slug,' . $request->id,
                'post_status' => 'required',
                'author' => 'required',
                'categories' => 'required|array',
                'categories.*' => 'exists:blog_categories,id',
            ]);

            $user = auth()->user();
            $pin_post = 0;
            $cpath = "uploads/images/blog/post/";
            $image_file = $request->current_featured_image;


            if ($request->hasfile('image')) {
                $image = $request->file('image');
                $key = Str::random(40);
                $image_file = $key . '-' . time() . '.' . $image->getClientOriginalExtension();
                $width = "1280";
                $height = "720";
                $this->imageUpload($image, $cpath, $image_file, $width, $height);

                if (File::exists(public_path('storage/uploads/images/blog/post/' . $request->current_featured_image))) {
                    File::delete(public_path('storage/uploads/images/blog/post/' . $request->current_featured_image));
                }
            }

            if (isset($request->pin_post)) {
                $pin_post = $request->pin_post;
            }

            $blog = BlogPost::findorfail($request->id);

            $blog->post_title = Str::of($request->post_title)->trim();
            $blog->post_content = $request->post_content;
            $blog->post_slug = Str::slug($request->post_slug, '-');
            $blog->seo_keyword = $request->seo_keyword;
            $blog->seo_title = $request->seo_title;
            $blog->seo_description = $request->seo_description;
            $blog->post_status = $request->post_status;
            $blog->post_type = $request->post_type;
            $blog->published_at = Carbon::parse($request->published_at);
            $blog->pin_post = $pin_post;
            $blog->featured_image = $image_file;
            $blog->author_id = $request->author;
            $blog->categories = implode(",", $request->categories);
            $blog->user_id = $user->id;
            $saved = $blog->save();

            $data = array(
                "image_file" => $image_file,
            );

            if ($saved) {
                return response()->json([
                    'success' => true,
                    'data' => $data,
                    'message' => "Successfully Blog Post Updated",
                ]);
            } else {
                return response()->json('Something went wrong', 422);
            }
        }
    }


    private function imageUpload($image, $path, $file_name, $width, $height)
    {

        $photo = Image::make($image)
            ->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->encode('jpg', 100);

        Storage::disk('public')->put($path . $file_name, $photo);
    }


    public function destroy(Request $request)
    {

        if ($request->ajax()) {

            $validate = $request->validate([
                'id' => 'required',
            ]);

            $blog = BlogPost::find($request->id);
            if (!is_null($blog)) {
                if (File::exists(public_path('storage/uploads/images/blog/post/' . $blog->featured_image))) {
                    File::delete(public_path('storage/uploads/images/blog/post/' . $blog->featured_image));
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
