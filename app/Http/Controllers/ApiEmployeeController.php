<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;



class ApiEmployeeController extends Controller
{
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
        $token = $user->createToken('myapptoken')->plainTextToken;
        // dd($user->id);
        $employee = Employee::create([
            'organization_id' => 1,
            'user_id' => $user->id,
            'profession' => $request->profession,
        ]);
        // $user->merge($token);
        $response = Employee::where('emp_id', '=', $employee->emp_id)->with('user')->first();
        return response()->json([
            'employee' => $response,
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

        return response()->json(['user' => $user, 'token' => $token]);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'logged out'
        ];
    }

}
