<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\LoginRequest;
use App\Http\Requests\API\Auth\RegisterRequest;
use Illuminate\Http\JsonResponse;
use Modules\User\Constants\UserType;
use Modules\User\Entities\Owner;
use Modules\User\Entities\User;

class AuthController extends Controller
{
    /**
     * Login function
     * @param LoginRequest $request
     * @return response
     */
    public function login(LoginRequest $request)
    {
        if (!auth()->attempt($request->data())) {
            return response()->json(['message' => 'Login Gagal'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $user = auth()->user();

        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json(['user' => $user, 'access_token' => $accessToken], JsonResponse::HTTP_OK);
    }

    /**
     * Register Owner function
     * @param LoginRequest $request
     * @return response
     */
    public function registerOwner(RegisterRequest $request)
    {
        $user = Owner::create($request->data());

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['user' => $user, 'access_token' => $accessToken], JsonResponse::HTTP_CREATED);
    }

    /**
     * Register Regular/Premium user function
     * @param LoginRequest $request
     * @return response
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->data();
        $data['type'] = $request->type ?? UserType::REGULAR;

        $user = User::create($data);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['user' => $user, 'access_token' => $accessToken], JsonResponse::HTTP_CREATED);
    }
}