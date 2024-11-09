<?php

namespace App\Http\Controllers\AdminBackend;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

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

    public function store(Request $request)
    {
        if ($request->ajax()) {

            $request->validate([
                'title' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'percent' => 'required',
                'status' => 'required',
                'access' => 'required',
                'auth_level' => 'required',
            ]);

            $coupon = new MarketplaceService();
            $coupon->title = $request->title;
            $coupon->coupon_code = $request->coupon_code;
            $coupon->percent = $request->percent;
            $coupon->start_date = $request->start_date;
            $coupon->end_date = $request->end_date;
            $coupon->status = $request->status;
            $coupon->lifetime = $request->access;
            $coupon->target_auth_level = $request->auth_level;
            $coupon->bulk_coupon = $request->bulk;
            $coupon->coupon_prefix = $request->prefix;
            $coupon->coupon_postfix = $request->postfix;
            $coupon->target_plan_level = $request->plan_level;
            $saved = $coupon->save();

            if ($saved) {
                return response()->json([
                    'success' => true,
                    'message' => "Successfully Coupon Added",
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

            $coupon = MarketplaceService::findorfail($request->id);
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

            $coupon = MarketplaceService::find($request->id);
            if (!is_null($coupon)) {
                $saved = $coupon->delete();
            }

            if ($saved) {
                return response()->json('Successfully deleted');
            } else {
                return response()->json('Something went wrong', 422);
            }
        }
    }
}
