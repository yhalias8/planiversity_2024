<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceWishList;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Exception;


class WishController extends Controller
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
    public function store(Request $request)
    {

        $data = $request->json()->all();
        $rules = [
            //'wish_id' => 'digits:8', //Must be a number and length of value is 8
            'service_id' => 'required',
            'uuid' => 'required',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->all(),
                'status' => JsonResponse::HTTP_BAD_REQUEST,
            ], JsonResponse::HTTP_BAD_REQUEST);
            return;
        }

        $hold = null;
        $id = null;
        if (empty($data['wish_id'])) {
            $hold = 'Add';

            $wish = new MarketplaceWishList();
            $wish->service_id = $data['service_id'];
            $wish->uuid = $data['uuid'];
            $saved = $wish->save();
            $id = $wish->id;
        } else {
            $hold = 'Remove';
            $wish = MarketplaceWishList::find($data['wish_id']);
            if (!is_null($wish)) {
                $saved = $wish->delete();
            }
        }
        if ($saved) {
            return response()->json([
                'data' => $hold,
                'id' => $id,
                'message' => 'Succeed',
                'status' => JsonResponse::HTTP_OK,
            ], JsonResponse::HTTP_OK);
        } else {

            return response()->json([
                'message' => 'failed',
                'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
