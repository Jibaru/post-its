<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\FileRepositoryInterface;
use App\Contracts\Repositories\GroupRepositoryInterface;
use App\Contracts\Repositories\PostitRepositoryInterface;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\Jobs\NewPostitCreatedJob;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostitController extends Controller
{
    private PostitRepositoryInterface $postitRepository;
    private GroupRepositoryInterface $groupRepository;
    private UserRepositoryInterface $userRepository;
    private FileRepositoryInterface $fileRepository;

    public function __construct(
        PostitRepositoryInterface $postitRepository,
        UserRepositoryInterface $userRepository,
        GroupRepositoryInterface $groupRepository,
        FileRepositoryInterface $fileRepository
    )
    {
        $this->postitRepository = $postitRepository;
        $this->userRepository = $userRepository;
        $this->groupRepository = $groupRepository;
        $this->fileRepository = $fileRepository;
    }

    /**
     * Display a listing of the resource by groupId
     *
     * @return \Illuminate\Http\Response
     */
    public function listByGroupId($id)
    {
        $validator = Validator::make([
            'id' => $id
        ],[
            'id' => 'required|integer|exists:groups'
        ]);

        if ($validator->fails()) 
        {
            return response()->json(
                [
                    'message' => 'Grupo no encontrado',
                    'errors' => $validator->errors()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        return response()->json(
            [
                'postits' => $this->postitRepository->getPostitsByGroupId($id)
            ],
            Response::HTTP_OK
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:100',
            'description' => 'required',
            'image' => 'image'
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

        $postitData = [
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'group_id' => $id,
            'user_id' => $user->id
        ];

        if ($request->has('image')) 
        {
            $fileName = date('m-d-Y-His A e') . $request->file('image')->getClientOriginalName();
            $fileContent = file_get_contents($request->file('image'));

            $fileUrl = $this->fileRepository->saveFile($fileName, $fileContent);

            $postitData += [ 'image_url' => $fileUrl ];
        }

        $postit = $this->postitRepository->savePostit($postitData);

        $this->sendEmail($id, $postit, $user);

        return response()->json(
            [
                'postit' => $postit
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
        $validator = Validator::make([
            'id' => $id
        ],[
            'id' => 'required|integer|exists:postits'
        ]);

        if ($validator->fails())
        {
            return response()->json(
                [
                    'message' => 'Nota no encontrada',
                    'errors' => $validator->errors()
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        return response()->json(
            [
                'postit' => $this->postitRepository->getPostitById($id)
            ],
            Response::HTTP_OK,
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $validator = Validator::make([
            'id' => $id
        ],[
            'id' => 'required|integer|exists:postits'
        ]);

        if ($validator->fails())
        {
            return response()->json(
                [
                    'message' => 'Nota no encontrada',
                    'errors' => $validator->errors()
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $this->postitRepository->deletePostitById($id);

        return response()->json(
            [
                "message" => "Nota eliminada"
            ],
            Response::HTTP_OK,
        );
    }

    private function sendEmail($groupId, $postit, $userCreator)
    {
        $users = $this->userRepository->getUsersInGroup($groupId);

        if ($users->isNotEmpty()) {
            $group = $this->groupRepository->getGroupById($groupId);

            $usersEmails = $users->map(function ($user) {
                return $user['email'];
            })->toArray();

            dispatch(new NewPostitCreatedJob($group, $postit, $userCreator, $usersEmails));
        }
    }
}
