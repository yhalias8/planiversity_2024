<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

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
            $query = MarketplaceService::select('id', 'service_title', 'category_id', 'service_image', 'author_name', 'regular_price', 'status')->where('status', 'active')->orderBy('id', 'asc')
                ->with(['category' => function ($n) {
                    $n->select('id', 'category_name');
                }]);
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
}
