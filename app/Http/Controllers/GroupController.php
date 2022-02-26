<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\GroupRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Auth;

class GroupController extends Controller
{
    private GroupRepositoryInterface $groupRepository;

    public function __construct(GroupRepositoryInterface $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'description' => 'required'
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

        $user = Auth::user();

        if (is_null($user))
        {
            return response()->json(
                [
                    'message' => 'Autenticación inválida'
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }

        return response()->json(
            [
                'message' => 'Grupo creado',
                'group' => $this->groupRepository->saveGroup([
                    'name' => $request->get('name'),
                    'description' => $request->get('description'),
                    'created_by_id' => $user->id
                ])
            ],
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
