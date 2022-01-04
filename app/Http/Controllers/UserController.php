<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //create user account
    public function register(Request $request)
    {
        $fields = validator::make(
            $request->all(),
            [
                'email' => 'required|string|unique:users,email',
                'password' => 'required|min:3|string|confirmed',
            ]
        );
        if ($fields->fails()) {
            return response($fields->errors());
        }
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'full_name' => $request->full_name,
        ]);
        // dd($user);
        $token = $user->createToken('myapptoken')->plainTextToken;
        // $user->merge($token);

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function login(Request $request)
    {

        $fields = $request->validate(
            [
                'email' => 'required|string',
                'password' => 'required|string',
            ]
        );
        $user = User::where('email', $fields['email'])->first();
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                "message" => "Incorrect Credentials. Please check that both your email and password are correct"
            ], 401);
        }
        $token = $user->createToken('myapptoken')->plainTextToken;
        // $response = [];
        // return response()->json(["data" => [
        //     'user' => $user,
        //     'token' => $token
        // ]]);
        return response()->json(['user' => $user, 'token' => $token]);
    }
    public function admin()
    {
        if (Auth::check()) {
            Auth::logout();
        }
        return view('admin.login.login');
    }

    public function login_web(Request $request)
    {
        $fields = $request->validate(
            [
                'email' => 'required|string',
                'password' => 'required|string',
            ]
        );
        // dd($fields);
        $user = User::where('email', $fields['email'])->first();
        // dd($user);
        if ($user->role != 1) return view('admin.login.login');
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                "message" => "Incorrect Credentials. Please check that both your email and password are correct"
            ], 401);
        }
        $token = $user->createToken('myapptoken')->plainTextToken;
        if ($token) Auth::login($user);
        return redirect('/admin/dashboard');
    }

    public function admin_logout()
    {
        Auth::logout();
        return redirect('/admin/dashboard');
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'logged out'
        ];
    }

    //update user account
    public function update_account(Request $request)
    {
        logger($request);
        $user = User::find($request->id);
        $user->full_name = $request->full_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();
        return response()->json(['user' => $user, 'token' => $request->token]);
    }

    //fetch all users account
    public function all_account()
    {
        $user = User::all();
        return $user;
    }

    //delete user account
    public function delete_account($id)
    {
        $user = User::find($id);
        $user->delete();
        return (['message' => 'Deleted Succesfully']);
    }

    //search user by name or email
    public function search_account($user_name)
    {
        $user = User::where('name', 'like', '%' . $user_name . '%')->get();
        return $user;
    }

    // public function is_user_input_valid(Request $req)
    // {
    //     $rules = array(
    //         'name' => 'required|min:4|max:50',
    //         'roles' => 'required',
    //         'pass' => 'required|min:8|max:50',
    //         'email' => 'required|email|min:12|max:50'
    //     );
    //     $valid = Validator::make($req->all(), $rules);
    //     if ($valid->fails()) {
    //         return $valid->errors();
    //     } else {
    //         return ["message" => "valid"];
    //     }
    // }
    public function upload_pic(Request $req)
    {
        try {
            $is_image = Validator::make($req->all(), ['file' => 'required|mimes:png,jpg,jpeg,gif']);
            if ($is_image->fails()) {
                return response()->json(['errors' => $is_image->errors()], 401);
            } else {
                $file = $req->file('file')->store("Pics");
                return response()->json([
                    "sucess" => true,
                    "message" => "uploaded Succesfully",
                    "file" => $file
                ]);
            }
        } catch (PostTooLargeException $e) {
            return response()->json(
                [
                    "message" => $e
                ]
            );
        }
    }
}
