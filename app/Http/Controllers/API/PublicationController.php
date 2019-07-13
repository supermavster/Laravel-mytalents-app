<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PublicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $publications = Publication::withCount('comments')->get();
        return response()->json($publications, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = \Auth::user();

        Validator::make(
                $request->all(),
                [
                'description'=>'string|required|max:1000',
                'media_file'=>'required|image'
            ],
                [
                'description.string'       => 'La descripcion no puede estar vacia',
                'description.required'     => 'La descripcion es un campo requerida',
                'description.max'          => 'La descripcion admite maximo 1000 caracteres',
                'media_file.required' => 'El archivo multimdia es un campo requerida',
                'media_file.image'    => 'El archivo multimdia tiene que ser una imagen o un video',
                'media_file.video'    => 'El archivo multimdia tiene que ser una imagen o un video'
            ]
            )->validate();

        $fields = $request->all();
        $fields['user_id'] = 5;
        $fields['description'] = $request->description;
        $fields['status'] = Publication::PUBLICATION_ACTIVE;

        if ($request->media_file) {
            $multimedia_name = time().$request->media_file->getClientOriginalName();
            $fields['media_file'] = $multimedia_name;
        }

        $publication = Publication::create($fields);

        Storage::disk('users')->put('Omar_100'.'/'.$publication->media_file, File::get($request->media_file));

        return response()->json($publication, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Publication $publication)
    {
        return response()->json($publication, 200);
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
        $myPublication = Publication::find($id);

        Validator::make($request->all(), [
            'description'      =>  'string',
            'multimedia_path'  =>  'string'
        ]);

        if ($request->has('description')) {
            $myPublication->description = $request->description;
        }

        if ($request->has('multimedia_path')) {
            $myPublication->multimedia_path = $request->multimedia_path;
        }

        if (!$myPublication->isDirty()) {
            return response()->json([
                'error'=>'Se debe especificar al menos un valor diferente para actualizar',
                'code'=>422], 422);
        }

        $myPublication->save();

        return response()->json($publication, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Publication $publication)
    {
        $publication->delete();
        return response()->json($publication, 200);
    }
}
