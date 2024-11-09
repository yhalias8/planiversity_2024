<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceCategory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

class CategoryController extends Controller
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
}
