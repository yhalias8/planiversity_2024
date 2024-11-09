<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
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

            $query = BlogPost::select('id', 'post_title', 'post_slug', 'post_status', 'post_type', 'pin_post', 'featured_image', 'author_id', 'categories', 'published_at')->orderBy('id','desc')->where([
                ['post_status', '=', 'published'],
                ['post_type', '=', 'public'],
            ])
                ->with(['author' => function ($n) {
                    $n->select('id', 'author_name', 'slug', 'photo');
                }]);

            if (request('category') != 0) {
                $category = request('category');
                $query->whereRaw("FIND_IN_SET($category, categories)");
            }

            if (request('author') != 0) {
                $query->where('author_id', request('author'));
            }

            if (request('search')) {
                $query->where('post_title', 'Like', '%' . request('search') . '%');
            }

            $total_count = $query->get()->count();
            $blog_list = $query->simplePaginate(4);
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        //$total_count_plus = $query->get();

        return response()->json([
            'data' => $blog_list,
            'total_count' => $total_count,
            'message' => 'Succeed',
            'status' => JsonResponse::HTTP_OK,
        ], JsonResponse::HTTP_OK);
    }

    public function blogCategoryList()
    {
        try {
            $category = BlogCategory::select('id', 'category_name', 'slug')->where('status', 'active')->orderBy('id', 'asc')->get();
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'data' => $category,
            'total_count' => BlogCategory::totalblog(),
            'message' => 'Succeed',
            'status' => JsonResponse::HTTP_OK,
        ], JsonResponse::HTTP_OK);
    }

    public function single(Request $request)
    {
        try {
            $query = BlogPost::select('id', 'post_title', 'post_slug', 'post_content', 'featured_image', 'seo_keyword', 'seo_title', 'seo_description', 'author_id', 'categories', 'published_at')->where([
                ['post_status', '=', 'published'],
                ['post_type', '=', 'public'],
                ['post_slug', '=', request('slug')]
            ])
                ->with(['author' => function ($n) {
                    $n->select('id', 'author_name', 'slug', 'photo');
                }]);
        } catch (Exception $e) {
            return [];
        }

        $blog_data = $query->first();
        return $blog_data;
    }
}
