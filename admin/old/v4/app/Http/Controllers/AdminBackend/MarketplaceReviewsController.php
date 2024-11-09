<?php

namespace App\Http\Controllers\AdminBackend;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceOrder;
use App\Models\MarketplaceCategory;
use App\Models\MarketplaceServiceReview;
use App\Models\UserList;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarketplaceReviewsController extends Controller
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
            $data =  MarketplaceServiceReview::select('id', 'created_at', 'service_id', 'rating', 'review', 'ip', 'user_id', 'status')->orderBy('id', 'DESC');
            if (request('service_id')) {
                $data->where('service_id', request('service_id'));
            }

            $data = $data->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('user_name', function ($row) {
                    return MarketplaceServiceReview::serviceUserRating($row);
                })
                ->rawColumns(['user_name'])
                ->make(true);
        }
        //return view('backend.pages.main.admin.marketplace_order');
    }


    public function store(Request $request)
    {

        if ($request->ajax()) {

            $validated = $request->validate([
                'service_id' => 'required',
                'review' => 'required',
                'rating' => 'required',
            ]);

            $review = new MarketplaceServiceReview();
            $review->service_id = $request->service_id;
            $review->review = $request->review;
            $review->rating = $request->rating;
            $review->status = $request->status;
            $saved = $review->save();

            if ($saved) {
                return response()->json([
                    'success' => true,
                    'message' => "Successfully Review Added",
                ]);
            } else {
                return response()->json('Something went wrong', 422);
            }
        }
    }

    public function update(Request $request)
    {

        if ($request->ajax()) {


            $validated = $request->validate([
                'id' => 'required',
                'review' => 'required',
                'rating' => 'required',
            ]);

            $review = MarketplaceServiceReview::findorfail($request->id);
            $review->review = $request->review;
            $review->rating = $request->rating;
            $review->status = $request->status;
            $saved = $review->save();

            if ($saved) {
                return response()->json([
                    'success' => true,
                    'message' => "Successfully Review Updated",
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

            $review = MarketplaceServiceReview::find($request->id);
            if (!is_null($review)) {
                $saved = $review->delete();
            }

            if ($saved) {
                return response()->json('Successfully deleted');
            } else {
                return response()->json('Something went wrong', 422);
            }
        }
    }
}
