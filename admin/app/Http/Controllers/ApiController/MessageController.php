<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\MarketplaceCategory;
use App\Models\People;
use App\Models\TravelGroup;
use App\Models\UserList;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
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
            'conversation_id' => 'required',
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

        try {

            $id = $request->conversation_id;

            $messages = ChatMessage::select('id', 'message', 'request_id', 'is_seen', 'sender_id', 'created_at')->where("chat_id", $id)->orderBy('id', 'desc')
                ->with(
                    [
                        'senders' => function ($n) {
                            $n->select('id', 'name', 'picture');
                        }
                    ]
                )
                //->with(['requests', 'requests.payments']);
                ->with(
                    [
                        'requests' => function ($n) {
                            $n->select('id', 'type', 'migration_id', 'is_action', 'true_flag', 'false_flag', 'is_payment', 'payment_offer_id');
                        },
                        'requests.migrations' => function ($n) {
                            $n->select('id', 'trip_id', 'planner_user_id', 'type', 'status');
                            $n->with(['trips' => function ($s) {
                                $s->select('id_trip', 'itinerary_type', 'transport');
                            }]);
                        },
                        'requests.payments' => function ($n) {
                            $n->select('id', 'title', 'amount', 'description', 'user_id');
                        }
                    ]
                );


            $response_main = $messages->simplePaginate(4);


            $response = $response_main->toArray();
            $response =  $response['data'];

            $filteredProducts = [];
            $uuid = $request->uuid;

            $newArray = array_filter($response, function ($value)  use (&$filteredProducts, $uuid) {
                // only include values that are greater than 
                if ($value['sender_id'] != $uuid && $value['is_seen'] == 0) {
                    array_push($filteredProducts, $value['id']);
                }
            });
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'data' => $response_main,
            'actions' => $filteredProducts,
            //'total_count' => MarketplaceCategory::totalServices(),
            'message' => 'Succeed',
            'status' => JsonResponse::HTTP_OK,
        ], JsonResponse::HTTP_OK);
    }

    public function head(Request $request)
    {

        $data = $request->all();
        $rules = [
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

        try {
            $user_id = $request->uuid;
            $head = ChatConversation::select('id', DB::raw('(CASE WHEN sender_id = ' . $user_id . ' THEN recipient_id ELSE sender_id END) AS recipient_id','need_action'))
                ->where(function ($query) use ($user_id) {
                    $query->where('sender_id', $user_id)
                        ->orWhere('recipient_id', $user_id);
                })->orderBy('id', 'desc')
                ->with(
                    [
                        'recipient' => function ($n) {
                            $n->select('id', 'name', 'picture','account_type');
                        }
                    ]
                );
            //$response = $head->get();
            $response = $head->simplePaginate(7);
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'data' => $response,
            //'total_count' => MarketplaceCategory::totalServices(),
            'message' => 'Succeed',
            'status' => JsonResponse::HTTP_OK,
        ], JsonResponse::HTTP_OK);
    }


    public function recipientList(Request $request)
    {
        $data = $request->all();
        $rules = [
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

        try {
            $user_id = $request->uuid;
            $subhead = DB::table('chat_conversations')->select(DB::raw('(CASE WHEN sender_id = ' . $user_id . ' THEN recipient_id ELSE sender_id END) AS recipient_id'))
                ->where(function ($query) use ($user_id) {
                    $query->where('sender_id', $user_id)
                        ->orWhere('recipient_id', $user_id);
                })->orderBy('id', 'asc');
                
            if (request('search')) {
                $head = UserList::select('id', 'name', 'customer_number', 'picture')->where('active', 1)->whereNotIn('id', $subhead)->orderBy('id', 'desc');
            } else {
                $people = new People();
                $head = $people->connection($user_id, $subhead);
            }                

            if (request('search')) {
                $search = $request->search;
                //$head->where('service_title', 'Like', '%' . request('search') . '%');
                $head->where(function ($query) use ($search) {
                    $query->where('name', 'Like', '%' . $search . '%')
                        ->orWhere('customer_number', $search);
                });
            }
            $response = $head->simplePaginate(16);
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'data' => $response,
            //'total_count' => MarketplaceCategory::totalServices(),
            'message' => 'Succeed',
            'status' => JsonResponse::HTTP_OK,
        ], JsonResponse::HTTP_OK);
    }


    public function message_process(Request $request)
    {
        $data = $request->json()->all();

        $rules = [
            'conversation_id' => 'required',
            'message' => 'required',
            'sender_id' => 'required',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->all(),
                'status' => JsonResponse::HTTP_BAD_REQUEST,
            ], JsonResponse::HTTP_BAD_REQUEST);
            return;
        }

        try {

            $chat = new ChatMessage();
            $chat->chat_id = $data['conversation_id'];
            $chat->sender_id = $data['sender_id'];
            $chat->message = $data['message'];
            $saved = $chat->save();
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($saved) {

            return response()->json([
                'message' => 'Message sent successfully',
                'status' => JsonResponse::HTTP_OK,
            ], JsonResponse::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'Data saved failed',
                'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function message_start(Request $request)
    {
        $data = $request->json()->all();

        $rules = [
            'recipient_id' => 'required',
            'sender_id' => 'required',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->all(),
                'status' => JsonResponse::HTTP_BAD_REQUEST,
            ], JsonResponse::HTTP_BAD_REQUEST);
            return;
        }

        try {

            $chat_response = ChatConversation::where(function ($query) use ($data) {
                $query->where('sender_id', $data['sender_id'])
                    ->where('recipient_id', $data['recipient_id']);
            })->orWhere(function ($query) use ($data) {
                $query->where('sender_id', $data['recipient_id'])
                    ->where('recipient_id', $data['sender_id']);
            })->value('id');

            if (empty($chat_response)) {
                
                $chat = new ChatConversation();
                $chat->sender_id = $data['sender_id'];
                $chat->recipient_id = $data['recipient_id'];
                $saved = $chat->save();
                $insertedId = $chat->id;
                
            } else {

                return response()->json([
                    'message' => 'Conversation created failed',
                    'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($saved) {

            return response()->json([
                'message' => 'Conversation created successfully',
                'conversation' => $insertedId,
                'status' => JsonResponse::HTTP_OK,
            ], JsonResponse::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'Data saved failed',
                'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function message_seen(Request $request)
    {
        $data = $request->json()->all();

        $rules = [
            'action_id' => 'required',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->all(),
                'status' => JsonResponse::HTTP_BAD_REQUEST,
            ], JsonResponse::HTTP_BAD_REQUEST);
            return;
        }

        try {
            $where_array = explode(",", $data['action_id']);
            $saved = ChatMessage::where('is_seen', 0)
                ->whereIn('id', $where_array)
                ->update(['is_seen' => 1]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($saved) {

            return response()->json([
                'message' => 'Message seen successfully',
                'status' => JsonResponse::HTTP_OK,
            ], JsonResponse::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'Data saved failed',
                'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function message_notification(Request $request)
    {
        $data = $request->all();

        $rules = [
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

        try {

            $list = ChatConversation::where(
                [
                    'sender_id' => $request->uuid,
                ]
            )->orWhere([
                'recipient_id' => $request->uuid,
            ])->pluck('id');

            $list = $list->all();



            $count = ChatMessage::where('is_seen', 0)->where('sender_id', '!=', $request->uuid)->whereIn('chat_id', $list)->count();
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        return response()->json([
            'message' => 'Message successfully fetched',
            'data' => $count,
            'status' => JsonResponse::HTTP_OK,
        ], JsonResponse::HTTP_OK);
    }
    
    public function group_message(Request $request)
    {

        $data = $request->json()->all();

        $rules = [
            'travel_group' => 'required',
            'employee_id' => 'required',
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

        $people_response = $data['employee_id'];
        $customer_response = UserList::where('customer_number', $people_response)->value('id');
        $user_response = UserList::where('id', $data['user_id'])->value('name');
        $group_response = TravelGroup::where('id', $data['travel_group'])->value('group_name');

        $chat_response = ChatConversation::where([
            'sender_id' => $data['user_id'],
            'recipient_id' => $customer_response,
        ])->orWhere([
            'sender_id' => $customer_response,
            'recipient_id' => $data['user_id'],
        ])->value('id');

        if (empty($chat_response)) {
            $chat_id = $this->chatEntry($data['user_id'], $customer_response);
            $message_saved = $this->messageEntry($chat_id, $data['user_id'], $this->groupMessageProcess($user_response, $group_response));
        } else {
            $message_saved = $this->messageEntry($chat_response, $data['user_id'], $this->groupMessageProcess($user_response, $group_response));
        }

        if ($message_saved) {

            return response()->json([
                'message' => 'Group added successfully',
                'status' => JsonResponse::HTTP_OK,
            ], JsonResponse::HTTP_OK);
        } else {

            return response()->json([
                'message' => 'Group mesage processed failed',
                'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    private function groupMessageProcess($user_name, $group_name)
    {
        $message = "$user_name added you to Group \"$group_name\"";
        return $message;
    }

    private function chatEntry($sender_id, $recipient_id)
    {
        $chat = new ChatConversation();
        $chat->sender_id = $sender_id;
        $chat->recipient_id = $recipient_id;
        $chat_saved = $chat->save();
        return $chat->id;
    }

    private function messageEntry($chat_id, $sender_id, $message)
    {
        $chatMessage = new ChatMessage();
        $chatMessage->chat_id = $chat_id;
        $chatMessage->sender_id = $sender_id;
        $chatMessage->message = $message;
        $message_saved = $chatMessage->save();
        return $message_saved;
    }
}
