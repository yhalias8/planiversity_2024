<?php

namespace App\Http\Controllers\AdminBackend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\UserList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
//use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProfileController extends Controller
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
    public function index()
    {

        // $singleData = UserList::select('', '')->where('id', $id)
        //     ->withCount('reviews')->first();
        return view('backend.pages.main.admin.profile_section');
    }

    public function profileUpdate(Request $request)
    {

        if ($request->ajax()) {

            $user = auth()->user();

            $validated = $request->validate([
                'name' => 'required|min:3|max:255',
                'email' => 'required|unique:admins|min:3|max:255',
                'email' => 'required|max:255|unique:admins,email,' . $user->id,
                'mobile_no' => 'required|min:5|max:255',
            ]);


            $admin = Admin::findorfail($user->id);
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->mobile_no = $request->mobile_no;
            $saved = $admin->save();

            if ($saved) {
                return response()->json([
                    'success' => true,
                    'message' => "Successfully Profile Updated",
                ]);
            } else {
                return response()->json('Something went wrong', 422);
            }
        }
    }

    public function passwordUpdate(Request $request)
    {

        if ($request->ajax()) {

            $user = auth()->user();

            $validated = $request->validate([
                'password' => 'required|min:8|max:255',
                'confirm_password' => 'required|min:8|max:255',
            ]);

            $admin = Admin::findorfail($user->id);
            $admin->password = Hash::make($request->password);
            $saved = $admin->save();

            if ($saved) {
                return response()->json([
                    'success' => true,
                    'message' => "Successfully Password Updated",
                ]);
            } else {
                return response()->json('Something went wrong', 422);
            }
        }
    }

    public function profileImageUpdate(Request $request)
    {

        $validatedData = $request->validate([
            'image' => 'required',
        ]);

        $user = auth()->user();

        $image = $request->image;
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $key = Str::random(40);
        $image_name = $key . '-' . time() . '.png';

        $path = public_path() . '/profile/' . $image_name;
        $image_process = file_put_contents($path, base64_decode($image));

        if ($image_process) {

            $admin = Admin::findorfail($user->id);
            $admin->picture = $image_name;
            $saved = $admin->save();

            if ($saved) {
                return response()->json([
                    'success' => true,
                    'image' => $image_name,
                    'message' => "Successfully Profile Image Updated",
                ]);
            } else {
                return response()->json('Something went wrong', 422);
            }
        } else {
            return response()->json('Something went wrong', 422);
        }
    }
}
