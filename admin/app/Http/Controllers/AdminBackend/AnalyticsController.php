<?php

namespace App\Http\Controllers\AdminBackend;

use App\Http\Controllers\Controller;
use App\Models\CouponList;
use App\Models\UserList;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{

    static function userCalculationProcess()
    {
        $active_users = UserList::select('id')->where('active', 1)->count();

        $paid_users = DB::table('users')
            ->whereIn('id', function ($query) {
                $query->select(DB::raw("(SELECT DISTINCT id_user FROM `payments` WHERE payments.id_user=users.id AND (date_expire>=NOW() AND status='succeeded') ORDER BY `payments`.`id_user`)"));
            })->count();

        $active_coupons = CouponList::select('id')->where('status', 'active')->count();

        $result = [
            array(
                'values' => $active_users,
            ),
            array(
                'values' => $paid_users,
            ),
            array(
                'values' => $active_coupons,
            )
        ];

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => "Successfully retrieved data ",
        ]);
    }
}
