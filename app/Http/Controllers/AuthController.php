<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\UserRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Auth;

class AuthController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|max:100',
            'password' => 'required',
        ]);

        if ($validator->fails()) 
        {
            return response()->json(
                [
                    'message' => 'Datos inválidos',
                    'errors' => $validator->errors()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $credentials = $request->only('email', 'password');

        try 
        {
            if ($token = $this->guard()->attempt($credentials))
            {
                return response()->json(
                    [
                        'message' => 'Inicio de sesión correcto',
                        'token' => $token,
                        'user' => $this->userRepository->getUserByEmail($request->get('email'))
                    ],
                    Response::HTTP_OK
                );
            }

            return response()->json(
                [
                    'message' => 'No autorizado'
                ], 
                Response::HTTP_UNAUTHORIZED
            );
        }
        catch (Exception $e)
        {
            return response()->json(
                [
                    'message' => 'Ocurrió un problema',
                    'exception' => $e
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }
}
