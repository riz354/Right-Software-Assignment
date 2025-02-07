<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendAdminUserRegisteredEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => [
                    'required',
                    'min:6',
                    'regex:/[A-Za-z]/',
                    'regex:/[0-9]/',
                ],
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => "Validation Error",
                    'errors' => $validateUser->errors(),
                ]);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            dispatch(new SendAdminUserRegisteredEmail($user));

            return response()->json([
                'status' => true,
                'message' => "New User Registered Successfully",
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'created_at' => $user->created_at->format('M d, Y'), 
                        'updated_at' => $user->updated_at->format('M d, Y'), 
                    ],
                    'token' => $user->createToken('API TOKEN')->plainTextToken,
                ],
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required',
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validateUser->errors(),
                ]);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Credentials',
                ], 401);
            }

            $token = $user->createToken('API Token')->plainTextToken;
            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'created_at' => $user->created_at->format('M d, Y'), 
                        'updated_at' => $user->updated_at->format('M d, Y'), 
                    ],
                    'token' => $token,
                ],
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    
}
