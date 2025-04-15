<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{
    public function auth(Request $request)
    {
        $user = User::query()
            ->where('email', $request->email)
            ->whereStatus(true)
            ->first();

        if (!$user) {
            return $this->responseWithErrorMessage('auth.failed', [], 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            return $this->responseWithErrorMessage('auth.failed', [], 401);
        }

        $user->load(['role:id,uuid,name,key']);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json([
            'access_token' => $accessToken,
            'user' => new UserResource($user)
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return $this->responseWithMessage('auth.logout');
    }
}
