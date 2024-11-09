<?php

namespace App\Http\Controllers\ApiController;

use App\Events\SellerInquiryEvent;
use App\Events\AgentPricingInquiryEvent;
use App\Http\Controllers\Controller;
use App\Models\Inquery;
use App\Models\PricingInquery;
use App\Models\MarketplaceService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\DB;

class InquiryController extends Controller
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

        $data = $request->json()->all();

        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required|min:5|max:150',
            'service_uuid' => 'required',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->all(),
                'status' => JsonResponse::HTTP_BAD_REQUEST,
            ], JsonResponse::HTTP_BAD_REQUEST);
            return;
        }

        $serviceData = MarketplaceService::select('id', 'service_title', 'author_name', 'author_email', 'author_mobile')->where('service_uuid', $data['service_uuid'])->first();

        $inquery = new Inquery();
        $inquery->name = $data['name'];
        $inquery->email = $data['email'];
        $inquery->mobile = $data['mobile'];
        $inquery->message = $data['message'];
        $inquery->service_id = $serviceData->id;
        $inquery->user_id = $data['user_id'];
        $inquery->ip_address =  $request->ip();
        $saved = $inquery->save();

        if ($saved) {

            $subject = "New Inquiry Received";
            event(new SellerInquiryEvent($serviceData, $subject, $data['name'], $data['email'], $data['mobile'], $data['message']));

            return response()->json([
                'message' => 'Inquery has been process successfully',
                'status' => JsonResponse::HTTP_OK,
            ], JsonResponse::HTTP_OK);
        } else {

            return response()->json([
                'message' => 'Data saved failed',
                'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
    
        public function agent(Request $request)
    {

        $data = $request->json()->all();

        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required|min:5|max:150',
            'country' => 'required',
            'state' => 'required',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->all(),
                'status' => JsonResponse::HTTP_BAD_REQUEST,
            ], JsonResponse::HTTP_BAD_REQUEST);
            return;
        }

        $inquery = new PricingInquery();
        $inquery->name = $data['name'];
        $inquery->email = $data['email'];
        $inquery->mobile = $data['mobile'];
        $inquery->message = $data['message'];
        $inquery->user_id = $data['user_id'];
        $inquery->country = $data['country'];
        $inquery->state = $data['state'];
        $inquery->ip_address =  $request->ip();
        $saved = $inquery->save();

        if ($saved) {

            $subject = "New Pricing Inquiry Received";
            event(new AgentPricingInquiryEvent($subject, $data['name'], $data['email'], $data['mobile'], $data['country'], $data['state'], $data['message']));

            return response()->json([
                'message' => 'Inquery has been process successfully',
                'status' => JsonResponse::HTTP_OK,
            ], JsonResponse::HTTP_OK);
        } else {

            return response()->json([
                'message' => 'Data saved failed',
                'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
