<?php

namespace App\Http\Controllers\AdminBackend\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('guest')->except('logout');
    // }



    public function loginForm()
    {
        return view('backend.auth.login');
    }

    public function loginProcess(Request $request)
    {

        if ($request->ajax()) {

            $request->validate([
                'email' => 'required|email|exists:admins,email',
                'password' => 'required|min:5|max:30'
            ], [
                'email.exists' => 'This email is not exists'
            ]);


            if (Auth::guard('user')->attempt(['email' => $request->email, 'password' => $request->password, 'status' => 'active'], $request->remember)) {

                return response()->json([
                    'success' => true,
                    'message' => "Successfully Credientials Matched",
                ]);
            } else {
                return response()->json('Email and password mismatch', 422);
            }
        }
    }

    /**
     * logout admin guard
     *
     * @return void
     */
    public function logout()
    {
        Auth::guard('user')->logout();
        return redirect()->route('user.login');
    }
}
