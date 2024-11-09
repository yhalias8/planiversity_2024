<?php

namespace App\Http\Controllers\AdminBackend;

use App\Http\Controllers\Controller;
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
}
