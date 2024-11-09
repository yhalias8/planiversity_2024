<?php

namespace App\Http\Controllers\AdminBackend;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

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
                'category_name' => 'required|unique:marketplace_category|max:255',
                'slug' => 'required|unique:marketplace_category|max:255',
                'status' => 'required',
                'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $file_name = null;
            $cpath = "uploads/images/category/";

            if ($request->hasfile('image')) {
                $file = $request->file('image');
                $key = Str::random(40);
                $file_name = $user->id . "-" . $key . "-" . time() . '.' . $file->extension();
                //$full_url = Storage::path($cpath . $file_name);
                //$full_url = asset('storage/' . $cpath . $file_name);
                //$file->move(public_path() . '/uploads/images', $name);
                $file->storeAs($cpath, $file_name, 'public');
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

    public function update(Request $request)
    {

        if ($request->ajax()) {

            $request->validate([
                'title' => 'required',
                'start_date' => 'required',
                //'coupon_code' => 'required',
                'end_date' => 'required',
                'percent' => 'required',
                'status' => 'required',
                'access' => 'required',
                'auth_level' => 'required',
            ]);

            $prefix = $request->prefix;
            $postfix = $request->postfix;

            if ($request->bulk == 0) {
                $prefix = null;
                $postfix = null;
            }

            $coupon = MarketplaceCategory::findorfail($request->id);
            $coupon->title = $request->title;
            $coupon->coupon_code = $request->coupon_code;
            $coupon->percent = $request->percent;
            $coupon->start_date = $request->start_date;
            $coupon->end_date = $request->end_date;
            $coupon->status = $request->status;
            $coupon->lifetime = $request->access;
            $coupon->target_auth_level = $request->auth_level;
            $coupon->bulk_coupon = $request->bulk;
            $coupon->coupon_prefix = $prefix;
            $coupon->coupon_postfix = $postfix;
            $coupon->target_plan_level = $request->plan_level;
            $saved = $coupon->save();

            if ($saved) {
                return response()->json([
                    'success' => true,
                    'message' => "Successfully Coupon Updated",
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
