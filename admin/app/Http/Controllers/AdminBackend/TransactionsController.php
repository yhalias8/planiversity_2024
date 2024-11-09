<?php

namespace App\Http\Controllers\AdminBackend;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceOrder;
use App\Models\MarketplaceOrderPayment;
use App\Models\PaymentList;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class TransactionsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data =  PaymentList::select(
                'id_user',
                'transaction_id',
                'plan_type',
                'payment_type',
                DB::raw("DATE_FORMAT(date_paid, '%b %d, %Y %h:%i %p') as date_paid"),
                DB::raw("DATE_FORMAT(date_expire, '%b %d, %Y %h:%i %p') as date_expire"),
                DB::raw("DATE_FORMAT(created_at, '%b %d, %Y %h:%i %p') as created_at"),
                'amount',
                'ip_address',
                'status'
            )->orderBy('id_payment', 'DESC')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('user_name', function ($row) {
                    $user = PaymentList::userProcess($row->id_user);
                    return $user->name;
                })
                ->editColumn('user_type', function ($row) {
                    $user = PaymentList::userProcess($row->id_user);
                    return $user->account_type;
                })
                ->editColumn('user_email', function ($row) {
                    $user = PaymentList::userProcess($row->id_user);
                    return $user->email;
                })
                ->rawColumns(['user_name', 'user_type', 'user_email'])
                ->make(true);
        }

        return view('backend.pages.main.admin.billing_transactions');
    }

    public function service(Request $request)
    {

        if ($request->ajax()) {
            $data =  MarketplaceOrderPayment::select(
                'transaction_id',
                'order_id',
                'payment_type',
                'created_at',
                'amount',
                'ip_address',
                'fname',
                'lname',
                'status'
            )->orderBy('id', 'DESC')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('user', function ($row) {
                    $order = MarketplaceOrder::orderUserProcess($row->order);
                    return $order;
                })
                ->editColumn('order_number', function ($row) {
                    $order = $row->order->order_number;
                    return $order;
                })
                ->editColumn('service_title', function ($row) {
                    $order = $row->order->services->service_title;
                    return $order;
                })
                ->rawColumns(['user', 'order_number'])
                ->make(true);
        }
        return view('backend.pages.main.admin.service_transactions');
    }


    public function order_payment_list(Request $request)
    {
        if ($request->ajax()) {
            $data =  MarketplaceOrderPayment::select(
                'id',
                'transaction_id',
                'order_id',
                'plan_type',
                'payment_type',
                'created_at',
                'amount',
                'ip_address',
                'fname',
                'lname',
                'status'
            )->orderBy('id', 'DESC');

            if (request('order_id')) {
                $data->where('order_id', request('order_id'));
            }

            $data = $data->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addIndexColumn()
                ->editColumn('user', function ($row) {
                    $order = MarketplaceOrder::orderUserProcess($row->order);
                    return $order;
                })
                ->editColumn('order_number', function ($row) {
                    $order = $row->order->order_number;
                    return $order;
                })
                ->editColumn('service_title', function ($row) {
                    $order = $row->order->services->service_title;
                    return $order;
                })
                ->rawColumns(['user', 'order_number'])
                ->make(true);
        }
    }
}
