<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(
        UserRepositoryInterface $userRepository
    )
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Store a newly user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|max:100',
            'email' => 'required|unique:users|max:100',
            'first_name' => 'required|max:60',
            'last_name' => 'required|max:60',
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

        return response()->json(
            [
                'message' => 'Usuario creado',
                'user' => $this->userRepository->register($request->all())
            ],
            Response::HTTP_CREATED
        );
    }
}
