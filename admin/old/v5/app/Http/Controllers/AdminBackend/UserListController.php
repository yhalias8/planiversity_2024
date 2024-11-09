<?php

namespace App\Http\Controllers\AdminBackend;

use App\Http\Controllers\Controller;
use App\Models\PaymentList;
use App\Models\UserList;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class UserListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data =  UserList::select('id', 'name', 'email', DB::raw("DATE_FORMAT(date_created, '%b %d, %Y %h:%i %p') as date_created"), DB::raw("DATE_FORMAT(date_current_login, '%b %d, %Y %h:%i %p') as date_current_login"), 'active', 'account_type')->where('account_type', '<>', 'Admin')->orderBy('id', 'DESC')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
        return view('backend.pages.main.admin.users');
    }

    public function user_payment_list(Request $request)
    {
        if ($request->ajax()) {
            $data =  PaymentList::select(
                'transaction_id',
                'plan_type',
                'payment_type',
                DB::raw("DATE_FORMAT(date_paid, '%b %d, %Y %h:%i %p') as date_paid"),
                DB::raw("DATE_FORMAT(date_expire, '%b %d, %Y %h:%i %p') as date_expire"),
                DB::raw("DATE_FORMAT(created_at, '%b %d, %Y %h:%i %p') as created_at"),
                'amount',
                'ip_address',
                'status'
            )->where('id_user', $request->uid)->orderBy('id_payment', 'DESC')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function view($id)
    {
        //$listData = UserList::where('claim_no', $request->claim_no)->where('type', $request->type)->first();
        //$role = UserList::findById($id, 'id');
        $singleData = UserList::where('id', $id)->first();
        if ($singleData) {
            $singleData = (object) $singleData->toArray();
        }

        //dd($singleData);
        return view('backend.pages.main.admin.single_user_view', compact('singleData'));
    }


    public function store(Request $request)
    {
        if ($request->ajax()) {

            $validate = $request->validate([
                'name' => 'required|max:255',
                'email' => 'required|unique:users|max:255',
                'password' => 'required',
                'account_type' => 'required',
            ]);

            $user = new UserList();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = $this->password_hasing($request->password);
            $user->account_type = $request->account_type;
            $user->active = $request->status;
            $saved = $user->save();

            if ($saved) {
                return response()->json([
                    'success' => true,
                    'message' => "Successfully User Added",
                ]);
            } else {
                return response()->json('Something went wrong', 422);
            }
        }
    }

    public function statusUpdate(Request $request)
    {
        if ($request->ajax()) {

            $request->validate([
                'status' => 'required',
                'uid' => 'required',
            ]);

            $user = UserList::findorfail($request->uid);
            $user->active = $request->status;
            $saved = $user->save();

            $status = $request->status == 1 ? "Active" : "Pending";

            if ($saved) {
                return response()->json([
                    'success' => true,
                    'message' => "Status Successfully Updated",
                    'status' => $status,
                ]);
            } else {
                return response()->json('Something went wrong', 422);
            }
        }
    }

    private function password_hasing($plainTextPassword)
    {

        $options = [
            'cost' => 10
        ];

        $current_hash = password_hash($plainTextPassword, PASSWORD_BCRYPT, $options);

        return $current_hash;
    }

    public function destroy(Request $request)
    {

        if ($request->ajax()) {

            $validate = $request->validate([
                'id' => 'required',
            ]);

            $user = UserList::find($request->id);
            if (!is_null($user)) {
                $saved = $user->delete();
            }

            if ($saved) {
                return response()->json('Successfully deleted');
            } else {
                return response()->json('Something went wrong', 422);
            }
        }
    }
}
