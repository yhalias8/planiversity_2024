<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceCategory;
use App\Models\MarketplaceOrder;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

class OrderController extends Controller
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
    public function index()
    {
        try {
            $category = MarketplaceCategory::select('id', 'category_name', 'category_image', 'slug')->where('status', 'active')->orderBy('id', 'asc')->withCount('services')->get();
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'data' => $category,
            'total_count' => MarketplaceCategory::totalServices(),
            'message' => 'Succeed',
            'status' => JsonResponse::HTTP_OK,
        ], JsonResponse::HTTP_OK);
    }


    public function single($id)
    {

        try {
            $query = MarketplaceOrder::select('id', 'order_number', 'service_id', 'service_price', 'user_id', 'status', 'created_at')->where('order_number', $id)
                ->with(['services' => function ($n) {
                    $n->select('id', 'service_title');
                }]);
            $order = $query->first();
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'data' => $order,
            'message' => 'Succeed',
            'status' => JsonResponse::HTTP_OK,
        ], JsonResponse::HTTP_OK);
    }
}
