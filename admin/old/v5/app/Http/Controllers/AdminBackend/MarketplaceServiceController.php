<?php

namespace App\Http\Controllers\AdminBackend;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\UserList;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class MarketplaceServiceController extends Controller
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
            $data =  MarketplaceService::select('id', 'service_title', 'category_id', 'service_image', 'author_name', 'regular_price', 'status')->orderBy('id', 'DESC')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('category_name', function ($row) {
                    return $row->category->category_name;
                })
                ->rawColumns(['category_name'])
                ->make(true);
        }
        return view('backend.pages.main.admin.marketplace_service');
    }


    public function create()
    {
        return view('backend.pages.main.admin.create_service_view');
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {

            $user = auth()->user();

            $request->validate([
                'service_title' => 'required',
                'service_description' => 'required',
                'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
                'regular_price' => 'required',
                'member_price' => 'required',
                'service_type' => 'required',
                'author_name' => 'required',
                'author_description' => 'required',
                'service_status' => 'required',
                'category_id' => 'required',
            ]);


            $image_file = null;
            $download_file = null;
            $author_file = null;
            $cpath = "uploads/images/service/";
            $apath = "uploads/images/author/";
            $dpath = "uploads/downloadable/";

            if ($request->hasfile('image')) {

                $image = $request->file('image');
                $key = Str::random(40);
                $image_file = $key . '-' . time() . '.' . $image->getClientOriginalExtension();
                $this->imageUpload($image, $cpath, $image_file);
            }

            if ($request->hasfile('downloadable_file')) {
                $file = $request->file('downloadable_file');
                $key = Str::random(40);
                $download_file = $user->id . "-" . $key . "-" . time() . '.' . $file->extension();
                $file->storeAs($dpath, $download_file, 'public');
            }

            if ($request->hasfile('inputfile')) {

                $input_file = $request->file('inputfile');
                $key = Str::random(40);
                $author_file = $user->id . "-" . $key . "-" . time() . '.' . $input_file->extension();
                $input_file->storeAs($apath, $author_file, 'public');
            }

            if (empty($request->sale_price)) {
                $request->sale_price = 0;
            }

            $service = new MarketplaceService();
            $service->service_uuid = (string) Str::uuid();
            $service->service_title = $request->service_title;
            $service->service_description = $request->service_description;
            $service->category_id = $request->category_id;
            $service->service_image = $image_file;
            $service->author_name = $request->author_name;
            $service->author_image = $author_file;
            $service->author_description = $request->author_description;
            $service->regular_price = $request->regular_price;
            $service->member_price_percentage = $request->member_price_percentage;
            $service->member_price = $request->member_price;
            $service->sale_price = $request->sale_price;
            $service->service_type = $request->service_type;
            $service->downloadable_file = $download_file;
            $service->status = $request->service_status;
            $saved = $service->save();

            if ($saved) {
                return response()->json([
                    'success' => true,
                    'message' => "Successfully Service Added",
                ]);
            } else {
                return response()->json('Something went wrong', 422);
            }
        }
    }


    public function edit($id)
    {
        $singleData = MarketplaceService::where('id', $id)
            ->withCount('reviews')->first();
        return view('backend.pages.main.admin.edit_service_view', compact('singleData', 'id'));
    }

    public function orders($id)
    {
        return view('backend.pages.main.admin.service_order_view', compact('id'));
    }

    public function reviews($id)
    {
        return view('backend.pages.main.admin.service_review_view', compact('id'));
    }

    public function update(Request $request, $id)
    {

        if ($request->ajax()) {

            $user = auth()->user();

            $request->validate([
                'service_title' => 'required',
                'service_description' => 'required',
                'image' => 'image|mimes:jpg,jpeg,png|max:2048',
                'regular_price' => 'required',
                'member_price' => 'required',
                'service_type' => 'required',
                'author_name' => 'required',
                'author_description' => 'required',
                'service_status' => 'required',
                'category_id' => 'required',
            ]);

            $image_file = $request->current_image;
            $author_file = $request->current_author_image;
            $download_file = $request->current_file;
            $cpath = "uploads/images/service/";
            $apath = "uploads/images/author/";
            $dpath = "uploads/downloadable/";

            if ($request->hasfile('image')) {

                $image = $request->file('image');
                $key = Str::random(40);
                $image_file = $key . '-' . time() . '.' . $image->getClientOriginalExtension();
                $this->imageUpload($image, $cpath, $image_file);

                if (File::exists(public_path('storage/uploads/images/service/' . $request->current_image))) {
                    File::delete(public_path('storage/uploads/images/service/' . $request->current_image));
                }
            }

            if ($request->hasfile('downloadable_file')) {

                $file = $request->file('downloadable_file');
                $key = Str::random(40);
                $download_file = $user->id . "-" . $key . "-" . time() . '.' . $file->extension();
                $file->storeAs($dpath, $download_file, 'public');

                if (File::exists(public_path('storage/uploads/downloadable/' . $request->current_file))) {
                    File::delete(public_path('storage/uploads/downloadable/' . $request->current_file));
                }
            }


            if ($request->hasfile('inputfile')) {

                $input_file = $request->file('inputfile');
                $key = Str::random(40);
                $author_file = $user->id . "-" . $key . "-" . time() . '.' . $input_file->extension();
                $input_file->storeAs($apath, $author_file, 'public');
            }

            if (empty($request->sale_price)) {
                $request->sale_price = 0;
            }

            $service = MarketplaceService::findorfail($id);
            $service->service_title = $request->service_title;
            $service->service_description = $request->service_description;
            $service->category_id = $request->category_id;
            $service->service_image = $image_file;
            $service->author_name = $request->author_name;
            $service->author_image = $author_file;
            $service->author_description = $request->author_description;
            $service->regular_price = $request->regular_price;
            $service->member_price_percentage = $request->member_price_percentage;
            $service->member_price = $request->member_price;
            $service->sale_price = $request->sale_price;
            $service->service_type = $request->service_type;
            $service->downloadable_file = $download_file;
            $service->status = $request->service_status;
            $saved = $service->save();

            $data = array(
                "image_file" => $image_file,
                "downloadable_file" => $download_file
            );

            if ($saved) {
                return response()->json([
                    'success' => true,
                    'data' => $data,
                    'message' => "Successfully Service Updated",
                ]);
            } else {
                return response()->json('Something went wrong', 422);
            }
        }
    }

    private function imageUpload($image, $path, $file_name)
    {
        //$photo = Image::make($image)->resize(330, 250);

        $photo = Image::make($image)
            ->resize(330, 250)
            ->encode('jpg', 100);

        Storage::disk('public')->put($path . $file_name, $photo);

        // , function ($const) {
        //     $const->aspectRatio();
        // }
    }

    public function destroy(Request $request)
    {

        if ($request->ajax()) {

            $validate = $request->validate([
                'id' => 'required',
            ]);

            $service = MarketplaceService::find($request->id);
            if (!is_null($service)) {
                $saved = $service->delete();

                if (File::exists(public_path('storage/uploads/images/service/' . $service->service_image))) {
                    File::delete(public_path('storage/uploads/images/service/' . $service->service_image));
                }

                if (!empty($service->downloadable_file)) {
                    File::delete(public_path('storage/uploads/downloadable/' . $service->downloadable_file));
                }
            }

            if ($saved) {
                return response()->json('Successfully deleted');
            } else {
                return response()->json('Something went wrong', 422);
            }
        }
    }
}
