<?php

namespace App\Http\Controllers\AdminBackend;

use App\Http\Controllers\Controller;
use App\Models\CouponList;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CouponController extends Controller
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
            $data =  CouponList::select('id', 'title', 'coupon_code', 'percent', 'start_date', 'end_date', 'status', 'lifetime', 'bulk_coupon', 'target_auth_level', 'lifetime', 'bulk_coupon', 'coupon_prefix', 'coupon_postfix', 'target_plan_level')->orderBy('id', 'DESC')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
        return view('backend.pages.main.admin.coupon');
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

            $coupon = new CouponList();
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

            $coupon = CouponList::findorfail($request->id);
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

            $coupon = CouponList::find($request->id);
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
