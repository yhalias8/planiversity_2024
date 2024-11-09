<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Models\ConnectDetails;
use App\Models\MigrationMaster;
use App\Models\TripComment;
use App\Models\TripMaster;
use App\Models\UserList;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\DB;

class TripInfoController extends Controller
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

        $data = $request->all();

        $rules = [
            'trip_id' => 'required',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->all(),
                'status' => JsonResponse::HTTP_BAD_REQUEST,
            ], JsonResponse::HTTP_BAD_REQUEST);
            return;
        }

        $trip_id = $request->trip_id;

        $query = TripMaster::select('id_trip', 'packet_number', 'id_user', 'title', 'itinerary_type', 'transport')
            ->where('id_trip', $trip_id)->first();

        $user_data = UserList::select('id', 'name', 'email')
            ->where('id', $query->id_user)
            ->addSelect(DB::raw('null as picture'))
            ->selectRaw('COALESCE(picture, null) as photo')
            ->addSelect(DB::raw('1 as photo_connect'))
            ->addSelect(DB::raw('\'collaborator\' as role'));

        $user_list = ConnectDetails::select('users.id', 'users.name', 'users.email', 'users.picture', 'employees.photo', 'employees.photo_connect','employees.role')
            ->join('connect_master', 'connect_details.connect_id', 'connect_master.id')
            ->join('employees', 'connect_details.people_id', 'employees.id_employee')
            ->leftJoin('travel_groups', 'connect_master.group_id', 'travel_groups.id')
            ->join('users', 'users.customer_number', 'employees.employee_id')
            ->where('connect_master.id_trip', $trip_id);

        $user_list_all = $user_data->union($user_list)->orderBy('id', 'asc')->get();
        $usersCount = $user_list_all->count();

        $output = array(
            "trip_info" => $query,
            "user_list" => $user_list_all,
            "user_count" => $usersCount
        );


        return response()->json([
            'data' => $output,
            'message' => 'Succeed',
            'status' => JsonResponse::HTTP_OK,
        ], JsonResponse::HTTP_OK);
    }

    public function comment(Request $request)
    {

        $data = $request->all();

        $rules = [
            'trip_id' => 'required',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->all(),
                'status' => JsonResponse::HTTP_BAD_REQUEST,
            ], JsonResponse::HTTP_BAD_REQUEST);
            return;
        }

        $trip_id = $request->trip_id;

        $query = TripComment::select('id', 'comment', 'user_id', 'created_at')->where('id_trip', $trip_id)->orderBy('id', 'asc')
            ->with(['user' => function ($n) {
                $n->select('id', 'name');
            }]);

        $output = $query->get();

        return response()->json([
            'data' => $output,
            'message' => 'Succeed',
            'status' => JsonResponse::HTTP_OK,
        ], JsonResponse::HTTP_OK);
    }

    public function commentProcess(Request $request)
    {

        $data = $request->json()->all();

        $rules = [
            'id_trip' => 'required',
            'commentfield' => 'required',
            'user_id' => 'required',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->all(),
                'status' => JsonResponse::HTTP_BAD_REQUEST,
            ], JsonResponse::HTTP_BAD_REQUEST);
            return;
        }

        $comment = new TripComment();
        $comment->id_trip = $data['id_trip'];
        $comment->user_id = $data['user_id'];
        $comment->comment = $data['commentfield'];
        $saved = $comment->save();

        if ($saved) {
            return response()->json([
                'message' => 'Successfully Comment Added',
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
