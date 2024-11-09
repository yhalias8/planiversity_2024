<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceService;
use App\Models\MarketplaceWishList;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        try {

            $query = MarketplaceService::select('id', 'service_uuid', 'service_title', 'category_id', 'service_image', 'author_name', 'regular_price', 'sale_price', 'member_price', 'service_description', 'author_description', 'status')->where('status', 'active')->orderBy('id', 'asc')
                ->with(['category' => function ($n) {
                    $n->select('id', 'category_name');
                }])
                ->with(['wishlist' => function ($q) {
                    $q->select('id', 'service_id')->where('uuid', request('uuid'));
                }])->withCount('reviews');

            if (request('category') != 0) {
                $query->where('category_id', request('category'));
            }
            if (request('search')) {
                $query->where('service_title', 'Like', '%' . request('search') . '%');
            }

            $service = $query->simplePaginate(4);
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'data' => $service,
            'message' => 'Succeed',
            'status' => JsonResponse::HTTP_OK,
        ], JsonResponse::HTTP_OK);
    }


    public function wishlist(Request $request)
    {
        try {
            $list = MarketplaceWishList::select('service_id')->where('uuid', request('uuid'))->pluck('service_id');;
            $list = $list->all();
            $query = MarketplaceService::select('id', 'service_uuid', 'service_title', 'category_id', 'service_image', 'author_name', 'regular_price', 'sale_price', 'member_price', 'service_description', 'author_description', 'status')->whereIn('id', $list)->where('status', 'active')->orderBy('id', 'asc')
                ->with(['category' => function ($n) {
                    $n->select('id', 'category_name');
                }])
                ->with(['wishlist' => function ($q) {
                    $q->select('id', 'service_id')->where('uuid', request('uuid'));
                }])->withCount('reviews');

            $service = $query->simplePaginate(4);
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'data' => $service,
            'message' => 'Succeed',
            'status' => JsonResponse::HTTP_OK,
        ], JsonResponse::HTTP_OK);
    }


    public function single($id)
    {
        try {
            $query = MarketplaceService::select('id', 'service_uuid', 'service_title', 'category_id', 'service_image', 'author_name', 'regular_price', 'sale_price', 'member_price', 'status')->where('status', 'active')->where('service_uuid', $id)
                ->with(['category' => function ($n) {
                    $n->select('id', 'category_name');
                }])->withCount('reviews');
            $service = $query->first();
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'data' => $service,
            'message' => 'Succeed',
            'status' => JsonResponse::HTTP_OK,
        ], JsonResponse::HTTP_OK);
    }
}
