<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Utils\ResponseUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function user() 
    {
        $user = auth()->user();
        $user = new UserResource($user);
        $data = [
            'user' => $user,
        ];

        return response()->json(ResponseUtil::getResponseArray($data, true));
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            $response = ResponseUtil::getResponseArray(null, false, 'Validation failed', $validator->errors());
            return response()->json($response, 419);
        }

        $user = User::where('email', $request->email)->first();

        if($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken('userToken')->plainTextToken;
            $user = new UserResource($user);
            $data = compact('user', 'token');
            $response = ResponseUtil::getResponseArray($data, true);
            return response()->json($response);

        } else {
            $message = 'Invalid email or password';
            $response = ResponseUtil::getResponseArray(null, false, $message);
            return response()->json($response, 419);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            $response = ResponseUtil::getResponseArray(null, false, 'Validation failed', $validator->errors());
            return response()->json($response, 419);
        }

        $user = User::create($request->only(['name', 'email', 'password']));
        $user = new UserResource($user);

        $token = $user->createToken('userToken')->plainTextToken;
        $data = compact('user', 'token');
        $response = ResponseUtil::getResponseArray($data, true);
        return response()->json($response);
    }
}
