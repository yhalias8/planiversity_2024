<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\ChatRequest;
use App\Models\People;
use App\Models\MarketplaceCategory;
use App\Models\MasterTrip;
use App\Models\MigrationMaster;
use App\Models\UserList;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\DB;

class MigrationController extends Controller
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
            'customer_number' => 'required',
            'packet_number' => 'required',
            'people_id' => 'required',
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

        $customer_response = UserList::where('customer_number', $data['customer_number'])->value('id');

        if ($customer_response) {

            $packet_response = MasterTrip::where(

                [
                    'packet_number' => $data['packet_number'],
                    'id_user' => $customer_response,
                ]
            )->value('id_trip');

            if ($packet_response) {

                $status = ["pending", "accepted", "completed"];

                $migration_response = MigrationMaster::where(
                    [
                        'trip_id' => $packet_response,
                        'people_id' => $data['people_id'],
                    ]
                )->whereIn('status', $status)->value('id');


                if (empty($migration_response)) {

                    $migration_id = $this->migrationEntry($packet_response, $data['people_id'], $customer_response, $data['user_id'], "Migration", "pending");
                    $request_id = $this->requestEntry("migration", $migration_id, 1, "accepted", "declined", 0);

                    $serder_id = $data['user_id'];
                    $recipient_id = $customer_response;


                    $chat_response = ChatConversation::where(function ($query) use ($serder_id, $recipient_id) {
                        $query->where('sender_id', $serder_id)
                            ->where('recipient_id', $recipient_id);
                    })->orWhere(function ($query) use ($serder_id, $recipient_id) {
                        $query->where('sender_id', $recipient_id)
                            ->where('recipient_id', $serder_id);
                    })->value('id');
                    

                    if (empty($chat_response)) {
                        $chat_id = $this->chatEntry($data['user_id'], $customer_response);
                        $message_saved = $this->messageEntry($chat_id, $data['user_id'], $this->messageProcess(), $request_id, 0);
                    } else {
                        $message_saved = $this->messageEntry($chat_response, $data['user_id'], $this->messageProcess(), $request_id, 0);
                    }

                    if ($message_saved) {

                        return response()->json([
                            'message' => 'Migration processed successfully',
                            'status' => JsonResponse::HTTP_OK,
                        ], JsonResponse::HTTP_OK);
                    } else {

                        return response()->json([
                            'message' => 'Migration processed failed',
                            'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
                    }
                } else {

                    return response()->json([
                        'message' => 'Migration record exists',
                        'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                    ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
                }
            } else {

                return response()->json([
                    'message' => 'Packet number missmatch',
                    'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }
        } else {
            // The record does not exist or the 'customer_number' column is null
            return response()->json([
                'message' => 'Customer number missmatch',
                'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }


    public function connect(Request $request)
    {

        $data = $request->json()->all();

        $rules = [
            //'customer_number' => 'required',
            'trip_id' => 'required',
            'people_id' => 'required',
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


        $people_response = People::where('id_employee', $data['people_id'])->value('employee_id');
        $customer_response = UserList::where('customer_number', $people_response)->value('id');

        // if ($customer_response) {
        //     //echo $response;

        //     $packet_response = MasterTrip::where(

        //         [
        //             'packet_number' => $data['packet_number'],
        //             'id_user' => $customer_response,
        //         ]
        //     )->value('id_trip');

        //     if ($packet_response) {

        //$status = ["pending", "declined"];
        $status = ["accepted", "completed"];

        $migration_response = MigrationMaster::where(
            [
                'trip_id' => $data['trip_id'],
                'people_id' => $data['people_id'],
            ]
        )->whereIn('status', $status)->value('id');


        if (empty($migration_response)) {

            $migration_id = $this->migrationEntry($data['trip_id'], $data['people_id'], $data['user_id'], $customer_response, "Connection", "accepted");
            $request_id = $this->requestEntry("migration", $migration_id, 0, null, null, 0);

            $chat_response = ChatConversation::where(
                [
                    'sender_id' => $data['user_id'],
                    'recipient_id' => $customer_response,
                ]
            )->orWhere([
                'sender_id' => $customer_response,
                'recipient_id' => $data['user_id'],
            ])->value('id');

            if (empty($chat_response)) {
                $chat_id = $this->chatEntry($data['user_id'], $customer_response);
                $message_saved = $this->messageEntry($chat_id, $data['user_id'], $this->connectMessageProcess(), $request_id, 0);
            } else {
                $message_saved = $this->messageEntry($chat_response, $data['user_id'], $this->connectMessageProcess(), $request_id, 0);
            }

            if ($message_saved) {

                return response()->json([
                    'message' => 'Connection processed successfully',
                    'status' => JsonResponse::HTTP_OK,
                ], JsonResponse::HTTP_OK);
            } else {

                return response()->json([
                    'message' => 'Migration processed failed',
                    'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }
        } else {

            return response()->json([
                'message' => 'Migration record exists',
                'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }


        // else {

        //     return response()->json([
        //         'message' => 'Packet number missmatch',
        //         'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
        //     ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        // }



        // } else {
        //     // The record does not exist or the 'customer_number' column is null
        //     return response()->json([
        //         'message' => 'Customer number missmatch',
        //         'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
        //     ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        // }
    }

    private function migrationEntry($trip_id, $people_id, $planner_user_id, $modifier_user_id, $type, $status)
    {
        $migration = new MigrationMaster();
        $migration->trip_id = $trip_id;
        $migration->people_id = $people_id;
        $migration->planner_user_id = $planner_user_id;
        $migration->modifier_user_id = $modifier_user_id;
        $migration->type = $type;
        $migration->status = $status;
        $migration_saved = $migration->save();
        return $migration->id;
    }

    private function requestEntry($type, $migration_id, $action, $true_flag, $false_flag, $payment)
    {
        $chatRequest = new ChatRequest();
        $chatRequest->type = $type;
        $chatRequest->migration_id = $migration_id;
        $chatRequest->is_action = $action;
        $chatRequest->true_flag = $true_flag;
        $chatRequest->false_flag = $false_flag;
        $chatRequest->is_payment = $payment;
        $request_saved = $chatRequest->save();
        return $chatRequest->id;
    }


    private function messageProcess()
    {
        return "Here is a request for migration.";
    }

    private function connectMessageProcess()
    {
        return "Trip Connected.";
    }

    private function chatEntry($sender_id, $recipient_id)
    {
        $chat = new ChatConversation();
        $chat->sender_id = $sender_id;
        $chat->recipient_id = $recipient_id;
        $chat_saved = $chat->save();
        return $chat->id;
    }

    private function messageEntry($chat_id, $sender_id, $message, $request_id, $seen)
    {

        $chatMessage = new ChatMessage();
        $chatMessage->chat_id = $chat_id;
        $chatMessage->sender_id = $sender_id;
        $chatMessage->message = $message;
        $chatMessage->request_id = $request_id;
        $chatMessage->is_seen = $seen;
        $message_saved = $chatMessage->save();
        return $message_saved;
    }


    public function status_action(Request $request)
    {

        $data = $request->json()->all();

        $rules = [
            'migration_number' => 'required',
            'status' => 'required',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->all(),
                'status' => JsonResponse::HTTP_BAD_REQUEST,
            ], JsonResponse::HTTP_BAD_REQUEST);
            return;
        }

        // if ($hold = UserList::select('id')->where('customer_number', $data['customer_number'])->exists()) {
        //     dd($hold);
        // } else {
        // }

        $migration_response = MigrationMaster::where(
            [
                'id' => $data['migration_number'],
                'status' => "pending",
            ]
        )->value('id');

        if ($migration_response) {

            $migration = MigrationMaster::findorfail($data['migration_number']);
            $migration->status = $data['status'];
            $saved = $migration->save();
            if ($saved) {
                return response()->json([
                    'message' => 'Migration processed successfully',
                    'status' => JsonResponse::HTTP_OK,
                ], JsonResponse::HTTP_OK);
            } else {

                return response()->json([
                    'message' => 'Migration processed failed',
                    'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }
        } else {
            // The record does not exist or the 'customer_number' column is null
            return response()->json([
                'message' => 'The migration is already underway.',
                'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
