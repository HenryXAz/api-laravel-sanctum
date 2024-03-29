<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
  public function login(Request $request): JsonResponse
  {
    $validator = Validator::make(
      $request->all(),
      [
        'email' => 'required|email',
        'password' => 'required|min:6|confirmed',
      ]
    );

    if ($validator->fails()) {
      return new JsonResponse(
        $validator->errors(),
        Response::HTTP_BAD_REQUEST,
      );
    }

    $credentials = [
      'email' => $request->email,
      'password' => $request->password,
    ];

    $user = Auth::attempt($credentials) ? Auth::user() : null;

    if ($user) {
      $token['token'] = $user->createToken('signInToken')->plainTextToken;

      return new JsonResponse(
        [
          $token,
          'status' => 'success',
        ],
        Response::HTTP_OK,
      );
    }

    return new JsonResponse(
      [
        'status' => 'unauthorized',
      ],
      Response::HTTP_UNAUTHORIZED,
    );
  }

  public function signUp(Request $request): JsonResponse
  {

    $validator = Validator::make($request->all(), [
      'name' => 'required|min:3',
      'email' => 'required|email|max:100|unique:users',
      'password' => 'required|min:6|confirmed'
    ]);
    if ($validator->fails()) {
      return new JsonResponse($validator->errors(), Response::HTTP_NOT_FOUND);
    }

    $user = new User();

    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = Hash::make($request->password);

    $user->save();
    $token['token'] = $user->createToken('signInToken')->plainTextToken;

    return new JsonResponse(
      [
        $token,
        'status' => 'success',
      ],
      Response::HTTP_OK,
    );
  }

  public function profile(Request $request): JsonResponse
  {

    if (Auth::user()) {
      return new JsonResponse(
        [
          Auth::user(),
          'status' => 'success',
        ],
        Response::HTTP_OK,
      );
    }

    return new JsonResponse(
      [
        'status' => 'unauthorized',
        Response::HTTP_UNAUTHORIZED,
      ],
      Response::HTTP_UNAUTHORIZED,
    );
  }

  public function logout(Request $request): JsonResponse
  {
    Auth::user()->tokens()->delete();

    return new JsonResponse(
      [
        'status' => 'sucess',
      ],
      Response::HTTP_OK,
    );
  }
}
