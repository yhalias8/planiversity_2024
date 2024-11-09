<?php

namespace App\Http\Controllers\AdminBackend;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceOrder;
use App\Models\MarketplaceCategory;
use App\Models\UserList;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarketplaceOrderController extends Controller
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
            $data =  MarketplaceOrder::select('id', 'created_at', 'service_id', 'service_price', 'uuid', 'user_id', 'guest', 'status')->orderBy('id', 'DESC')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('service_title', function ($row) {
                    return $row->services->service_title;
                })
                ->editColumn('author_name', function ($row) {
                    return $row->services->author_name;
                })
                ->editColumn('category_name', function ($row) {
                    $category = MarketplaceCategory::find($row->services->category_id);
                    return $category->category_name;
                })
                ->editColumn('user_name', function ($row) {
                    return MarketplaceOrder::orderUserProcess($row);
                })
                ->rawColumns(['service_title', 'author_name', 'category_name', 'user_name'])
                ->make(true);
        }
        return view('backend.pages.main.admin.marketplace_order');
    }

    public function edit($id)
    {
        $singleData = MarketplaceOrder::where('id', $id)->first();
        $order_user = MarketplaceOrder::orderUserProcess($singleData);
        $order_category = MarketplaceOrder::orderCategoryProcess($singleData->services->category_id);
        return view('backend.pages.main.admin.edit_order_view', compact('singleData', 'order_user', 'order_category'));
    }

    public function destroy(Request $request)
    {

        if ($request->ajax()) {

            $validate = $request->validate([
                'id' => 'required',
            ]);

            $service = MarketplaceOrder::find($request->id);
            if (!is_null($service)) {
                $saved = $service->delete();
            }

            if ($saved) {
                return response()->json('Successfully deleted');
            } else {
                return response()->json('Something went wrong', 422);
            }
        }
    }
}
