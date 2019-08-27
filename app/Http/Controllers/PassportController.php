<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PassportController extends Controller
{
    /**
     * Handles Registration Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('foodos')->accessToken;

        $details = [
            'status' => 200,
            'message' => 'user created',
            'data' => $token
            ];

        return response()->json($details);
    }

    /**
     * Handles Login Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('foodos')->accessToken;

            $details = [
                'status' => 200,
                'message' => 'user authenticated ',
                'data' => $token
            ];

            return response()->json($details);
        } else {
            return response()->json(['status'=> 401, 'error' => 'UnAuthorised'], 401);
        }
    }

    /**
     * Returns Authenticated User Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function details()
    {
        $details = [
            'status' => 200,
            'message' => 'user authenticated ',
            'data' => auth()->user()
        ];

        return response()->json($details);
    }

    public function logout() {

        $user = '';
        if (Auth::check()) {
            $user = Auth::user()->token();
            $user->revoke();
        }
        $details = [
            'status' => 200,
            'message' => 'user logged out ',
            'data' => "plz login aagin"
        ];

        return response()->json($details);

    }
}
