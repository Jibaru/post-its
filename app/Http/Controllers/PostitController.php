<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\FileRepositoryInterface;
use App\Contracts\Repositories\PostitRepositoryInterface;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class PostitController extends Controller
{
    private PostitRepositoryInterface $postitRepository;
    private FileRepositoryInterface $fileRepository;

    public function __construct(
        PostitRepositoryInterface $postitRepository,
        FileRepositoryInterface $fileRepository
    )
    {
        $this->postitRepository = $postitRepository;
        $this->fileRepository = $fileRepository;
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

        return response()->json(
            [
                'postit' => $this->postitRepository->savePostit($postitData) 
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
