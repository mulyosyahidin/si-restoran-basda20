<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'string'],
            'password' => ['required', 'string'],
            'remember_me' => ['boolean']
        ]);

        if ($validator->fails()) {
            return response()
                ->json([
                    'error' => true,
                    'validations' => $validator->errors()
                ], 422);
        }

        $credentials = request(['email', 'password']);
        if ( ! Auth::attempt($credentials)) {
            return response()
                ->json([
                    'error' => true,
                    'message' => 'Email atau Password salah'
                ], 401);
        }

        $user = auth()->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);

        $token->save();

        return response()
            ->json([
                'success' => true,
                'role' => $user->roles[0]->name,
                'token' => [
                    'accessToken' => $tokenResult->accessToken,
                    'expiresAt' => $tokenResult->token->expires_at
                ]
            ]);
    }

    public function logout()
    {
        auth()->user()->token()->revoke();
        auth('web')->logout();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil logout'
        ]);
    }
}
