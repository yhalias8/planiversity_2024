<?php

namespace App\Http\Controllers\AdminBackend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Analytics;
use Spatie\Analytics\Period;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    public function index()
    {
        return view('backend.pages.main.admin.home');

        ///$analyticsData = Analytics::fetchMostVisitedPages(Period::days(30), $maxResults = 20);

        // foreach ($analyticsData as $data) {
        //     echo $data['pageTitle'] . "<br/>";
        // }


        //dd($analyticsData);
    }

    public function test()
    {
        // $analyticsData = Analytics::performQuery(
        //     Period::days(30),
        //     'ga:sessions',
        //     [
        //         'metrics' => 'ga:sessions,
        //         ga:pageviews',
        //         'dimensions' => 'ga:country'
        //     ]
        // );

        // dd($analyticsData);


        $analyticsData = Analytics::performQuery(
            Period::days(7),
            'ga:sessions',
            [
                'metrics' => 'ga:sessions, ga:pageviews',
                'dimensions' => 'ga:yearMonth'
            ]
        );

        //dd($analyticsData);


        // $response = Analytics::performQuery(
        //     Period::days(7),
        //     'ga:pageviews',
        //     [
        //         'metrics' => 'ga:users',
        //         'dimensions' => 'ga:pagePath,ga:pageTitle,ga:country',
        //         'sort' => '-ga:users',
        //         'max-results' => 20,

        //         //, ga:pageviews
        //     ],
        // );

        $response = Analytics::performQuery(
            Period::days(7),
            'ga:users',
            [
                'dimensions' => 'ga:country',
                'sort' => '-ga:users',
                'max-results' => 10,
            ]
        );

        dd($response);

        return collect($response['rows'] ?? [])->map(fn (array $pageRow) => [
            'url' => $pageRow[0],
            'pageTitle' => $pageRow[1],
            'pageViews' => (int) $pageRow[2],
        ]);
    }

    function demo()
    {

        $analyticsData = Analytics::fetchTotalVisitorsAndPageViews(Period::days(7), $maxResults = 3);

        // $response = Analytics::performQuery(
        //     Period::days(7),
        //     'ga:device',
        //     'ga:users',
        //     [
        //         //'dimensions' => 'ga:device',
        //         'metrics' => 'ga:devices',
        //         'sort' => '-ga:users',
        //         'max-results' => 10,
        //     ]
        // );

        $analyticsData = Analytics::performQuery(
            Period::days(7),
            'ga:users'
        );

        //$deviceType = Ana::deviceType(Period::days(7));

        $days = 30;

        //$deviceType = Analytics::performQuery(Period::days($days), 'ga:users', ['dimensions' => 'ga:campaign,ga:source,ga:medium']);
        //$deviceType = Analytics::performQuery(Period::days(7), 'ga:users', ['dimensions' => 'ga:traffic']);


        $deviceType = Analytics::performQuery(
            Period::days(7),
            'ga:sessions',
            [
                'dimensions' => 'ga:medium',
                'sort' => '-ga:sessions',
            ]
        );



        //$deviceType = Analytics::fetchTopReferrers(Period::days(7));

        dd($deviceType);

        //dd($analyticsData);
    }
}
