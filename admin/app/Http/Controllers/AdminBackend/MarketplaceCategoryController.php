<?php

namespace App\Http\Controllers\AdminBackend;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class MarketplaceCategoryController extends Controller
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
            $data =  MarketplaceCategory::select('id', 'category_name', 'category_image', 'slug', 'status')->orderBy('id', 'DESC')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
        return view('backend.pages.main.admin.marketplace_category');
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {

            $user = auth()->user();

            $validate = $request->validate([
                'category_name' => 'required|unique:marketplace_categories|max:255',
                'slug' => 'required|unique:marketplace_categories|max:255',
                'status' => 'required',
                'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $file_name = null;
            $cpath = "uploads/images/category/";

            if ($request->hasfile('image')) {
                $image = $request->file('image');
                $key = Str::random(40);
                $file_name = $key . '-' . time() . '.' . $image->getClientOriginalExtension();
                $this->imageUpload($image, $cpath, $file_name);
            }

            $category = new MarketplaceCategory();
            $category->category_name = Str::of($request->category_name)->trim();
            $category->slug = Str::slug($request->slug, '-');
            $category->category_image = $file_name;
            $category->status = $request->status;
            $saved = $category->save();

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

        $data = MarketplaceCategory::select('id', 'category_name')
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
                'category_name' => 'required|max:255|unique:marketplace_categories,category_name,' . $request->id,
                'slug' => 'required|max:255|unique:marketplace_categories,slug,' . $request->id,
            ]);

            $file_name = null;
            $cpath = "uploads/images/category/";
            $user = auth()->user();

            $category = MarketplaceCategory::findorfail($request->id);

            if ($request->hasfile('image')) {

                $image = $request->file('image');
                $key = Str::random(40);
                $file_name = $key . '-' . time() . '.' . $image->getClientOriginalExtension();
                $this->imageUpload($image, $cpath, $file_name);

                if (File::exists(public_path('storage/uploads/images/category/' . $category->category_image))) {
                    File::delete(public_path('storage/uploads/images/category/' . $category->category_image));
                }
            } else {
                $file_name = $request->current_image;
            }

            $category->category_name = Str::of($request->category_name)->trim();
            $category->slug = Str::slug($request->slug, '-');
            $category->category_image = $file_name;
            $category->status = $request->status;
            $saved = $category->save();

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


    private function imageUpload($image, $path, $file_name)
    {
        $photo = Image::make($image)
            ->resize(350, 370)
            ->encode('jpg', 100);

        Storage::disk('public')->put($path . $file_name, $photo);
    }


    public function destroy(Request $request)
    {

        if ($request->ajax()) {

            $validate = $request->validate([
                'id' => 'required',
            ]);

            $category = MarketplaceCategory::find($request->id);
            if (!is_null($category)) {
                //unlink($category->category_image);
                if (File::exists(public_path('storage/uploads/images/category/' . $category->category_image))) {
                    File::delete(public_path('storage/uploads/images/category/' . $category->category_image));
                }

                $saved = $category->delete();
            }
            if ($saved) {
                return response()->json('Successfully deleted');
            } else {
                return response()->json('Something went wrong', 422);
            }
        }
    }
}
