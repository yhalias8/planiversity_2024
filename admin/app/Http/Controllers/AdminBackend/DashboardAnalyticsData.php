<?php

namespace App\Http\Controllers\AdminBackend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminBackend\GoogleAnalyticsController;
use App\Http\Controllers\AdminBackend\AnalyticsController;


class DashboardAnalyticsData extends Controller
{
    function topCountries(Request $request)
    {
        $data = GoogleAnalyticsController::topCountriesByUser($request->days, $request->length);

        return $data;
    }

    function topBrowsers(Request $request)
    {

        $data = GoogleAnalyticsController::fetchTopBrowsers($request->days, $request->length);
        return $data;
    }

    function topDevice(Request $request)
    {

        $data = GoogleAnalyticsController::topDeviceByUser($request->days, $request->length);
        return $data;
    }

    function topMedium(Request $request)
    {

        $data = GoogleAnalyticsController::topMediumBySession($request->days, $request->length);
        return $data;
    }

    function userType(Request $request)
    {

        $data = GoogleAnalyticsController::topUserTypes($request->days);
        return $data;
    }

    function topVisitedPages(Request $request)
    {

        $data = GoogleAnalyticsController::topMostVisitedPages($request->days, $request->length);
        return $data;
    }

    function totalVisitor(Request $request)
    {

        $data = GoogleAnalyticsController::totalVisitorAndViews($request->days, $request->length);
        return $data;
    }

    function userCalculation()
    {

        $data = AnalyticsController::userCalculationProcess();
        return $data;
    }
}
