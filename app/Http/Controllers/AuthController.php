<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $formFields = $request->validate([
            "name" => ["required", "min:3"],
            "email" => ["required", "email", Rule::unique("users", "email")],
            "password" => ["required", "confirmed", "min:6"]
        ]);

        // Hash Password
        $formFields["password"] = bcrypt($formFields["password"]);

        // Create User
        $user = User::create($formFields);
        if ($user) {
            return response()->json([
                "type" => "success",
                "message" => "User created succesfully. Now you can go to login endpoint and login."
            ], 201);
        }

    }
    public function login(Request $request)
    {
        $formFields = $request->validate([
            "email" => ["required", "email"],
            "password" => ["required", "min:6"]
        ]);

        if (auth()->attempt($formFields)) {
            $user = auth()->user();
            $access_token = $user->createToken("LaraPass");

            return response()->json([
                "type" => "success",
                "message" => "You have successfully login. Use your access token to authorize yourself.",
                "token" => $access_token
            ], 200);
        }else {
            return response()->json([
                "type" => "error",
                "message" => "The data you have entered is incorrect.",
            ], 401);
        }

    }
}
