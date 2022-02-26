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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $groups = ($request->has('page_size')) 
            ? $this->groupRepository->getAllGroups($request->query('page_size'))
            : $this->groupRepository->getAllGroups();

        return response()->json(
            $groups,
            Response::HTTP_OK
        );
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
        $validator = $this->groupIdValidator($id);

        if ($validator->fails())
        {
            return response()->json(
                [
                    'message' => 'Grupo no encontrado',
                    'errors' => $validator->errors()
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        return response()->json(
            [
                'group' => $this->groupRepository->getGroupById($id)
            ],
            Response::HTTP_OK,
        );
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
     * Soft-delte the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $validator = $this->groupIdValidator($id);

        if ($validator->fails())
        {
            return response()->json(
                [
                    'message' => 'Grupo no encontrado',
                    'errors' => $validator->errors()
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $this->groupRepository->deleteGroup($id);

        return response()->json(
            [
                "message" => "Grupo eliminado"
            ],
            Response::HTTP_OK,
        );
    }

    /**
     * Subscribe user to the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function subscribe($id)
    {
        $validator = $this->groupIdValidator($id);

        if ($validator->fails())
        {
            return response()->json(
                [
                    'message' => 'Grupo no encontrado',
                    'errors' => $validator->errors()
                ],
                Response::HTTP_NOT_FOUND
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

        if (!is_null(
            $this->groupRepository
                ->getGroupById($id)
                ->users
                ->firstWhere('id', $user->id)))
        {
            return response()->json(
                [
                    'message' => 'Ya es miembro del grupo'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $this->groupRepository->attachUserToGroup($id, $user->id);

        return response()->json(
            [
                'message' => 'Suscrito al grupo',
                'group' => [
                    'id' => $id,
                ]
            ],
            Response::HTTP_OK
        );
    }

    private function groupIdValidator($id)
    {
        return Validator::make(
            [ 'id' => $id ],
            [ 'id' => 'required|exists:groups,id' ]);
    }
}
