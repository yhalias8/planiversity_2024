<?php

namespace App\Http\Controllers\AdminBackend;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceService;
use Illuminate\Http\Request;

class ApplicationSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    public function index()
    {
        return view('backend.pages.main.admin.settings');
    }

    public function demo()
    {

        $data['service_uuid'] = "58ff9fef-c2ef-4412-be8c-cf6a356fbb6d";
        $serviceData = (object) MarketplaceService::select('id', 'service_title', 'member_price', 'regular_price', 'sale_price')->where('service_uuid', $data['service_uuid'])->first();
        echo $serviceData->regular_price;
        dd($serviceData);
        $itemPrice = MarketplaceService::price_calculation($serviceData, 0);
    }
}
