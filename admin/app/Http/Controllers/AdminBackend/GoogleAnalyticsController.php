<?php

namespace App\Http\Controllers\AdminBackend;

use App\Http\Controllers\Controller;
use Analytics;
use Spatie\Analytics\Period;

class GoogleAnalyticsController extends Controller
{

    static function topCountriesByUser($days = 7, $maxResults = 4)
    {
        $country = Analytics::performQuery(
            Period::days($days),
            'ga:users',
            [
                'dimensions' => 'ga:country',
                'sort' => '-ga:users',
                'max-results' => $maxResults,
            ]
        );

        $result = collect($country['rows'] ?? [])->map(function (array $dateRow) {
            return [
                'country' =>  $dateRow[0],
                'users' => (int) $dateRow[1],
            ];
        });

        return response()->json([
            'success' => true,
            'data' => (object) array(
                'labels' => $result->pluck('country'),
                'values' => $result->pluck('users')
            ),
            'count' => $maxResults,
            'message' => "Successfully retrieved data ",
        ]);
    }


    static function fetchTopBrowsers($days = 7, $maxResults = 4)
    {

        $analyticsData = Analytics::fetchTopBrowsers(Period::days($days), $maxResults = $maxResults);

        $result = collect($analyticsData ?? [])->map(function (array $dateRow) {
            return [
                'browser' =>  $dateRow['browser'],
                'sessions' => (int) $dateRow['sessions'],
            ];
        });

        return response()->json([
            'success' => true,
            'data' => (object) array(
                'labels' => $result->pluck('browser'),
                'values' => $result->pluck('sessions')
            ),
            'count' => $maxResults,
            'message' => "Successfully retrieved data ",
        ]);
    }


    static function topDeviceByUser($days = 7, $maxResults = 4)
    {

        $country = Analytics::performQuery(
            Period::days($days),
            'ga:users',
            [
                'dimensions' => 'ga:deviceCategory',
                'sort' => '-ga:users',
                'max-results' => $maxResults,
            ]
        );

        $result = collect($country['rows'] ?? [])->map(function (array $dateRow) {
            return [
                'device' =>  $dateRow[0],
                'users' => (int) $dateRow[1],
            ];
        });

        return response()->json([
            'success' => true,
            'data' => (object) array(
                'labels' => $result->pluck('device'),
                'values' => $result->pluck('users')
            ),
            'count' => $maxResults,
            'message' => "Successfully retrieved data ",
        ]);
    }


    static function topMediumBySession($days = 7, $maxResults = 4)
    {
        $country = Analytics::performQuery(
            Period::days($days),
            'ga:users',
            [
                'dimensions' => 'ga:medium',
                'sort' => '-ga:users',
                'max-results' => $maxResults,
            ]
        );

        $result = collect($country['rows'] ?? [])->map(function (array $dateRow) {
            return [
                'medium' =>  $dateRow[0],
                'users' => (int) $dateRow[1],
            ];
        });

        return response()->json([
            'success' => true,
            'data' => (object) array(
                'labels' => $result->pluck('medium'),
                'values' => $result->pluck('users')
            ),
            'count' => $maxResults,
            'message' => "Successfully retrieved data ",
        ]);
    }

    static function topUserTypes($days = 7)
    {
        $analyticsData = Analytics::fetchUserTypes(Period::days($days));

        $result = collect($analyticsData ?? [])->map(function (array $dateRow) {
            return [
                'values' => (int) $dateRow['sessions'],
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => "Successfully retrieved data ",
        ]);
    }


    static function topMostVisitedPages($days = 7, $maxResults = 4)
    {

        $analyticsData = Analytics::fetchMostVisitedPages(Period::days($days), $maxResults = $maxResults);

        $result = collect($analyticsData ?? [])->map(function (array $dateRow) {
            return [
                $dateRow['url'],
                $dateRow['pageTitle'],
                $dateRow['pageViews'],
            ];
        });


        return response()->json([
            'success' => true,
            'data' => $result,
            'count' => $maxResults,
            'message' => "Successfully retrieved data ",
        ]);
    }

    static function totalVisitorAndViews($days = 7, $maxResults = 4)
    {

        $analyticsData = Analytics::fetchTotalVisitorsAndPageViews(Period::days($days), $maxResults = $maxResults);

        $result = collect($analyticsData ?? [])->map(function (array $dateRow) {
            return [
                date('d-m-Y', strtotime($dateRow['date'])),
                $dateRow['visitors'],
                $dateRow['pageViews'],
            ];
        });


        return response()->json([
            'success' => true,
            'data' => $result,
            'count' => $maxResults,
            'message' => "Successfully retrieved data ",
        ]);
    }
}
