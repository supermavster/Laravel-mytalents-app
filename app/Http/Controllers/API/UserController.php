<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::withCount('publications', 'comments')->get();
        return response()->json($users,200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = User::withCount('publications', 'comments')->get();
        return response()->json($data,200);
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
        Validator::make(
            $request->all(),
            [
            'name'          =>  'string',
            'surname'       =>  'string',
            'birthday'      =>  'date',
            'gender'        =>  'string',
            'phone'         =>  'numeric',
            'email'         =>  'email|unique',
            'profile_photo' =>  'image'
        ],
            [
            'name.string'      =>  'El nombre no puede estar vacio',
            'surname.string'   =>  'El apellido no puede estar vacio',
            'gender.string'    =>  'El genero no puede estar vacio',
            'phone.numeric'    =>  'El numero telefonico solo puede contener valores numericos',
            'email.email'      =>  'El correo electronico ingresado no es valido',
            'email.unique'     =>  'El correo electronico ingresado ya se encuentra registrado en el sistema',
            'profile_photo.image' =>  'La extension de la foto asignada no es valida'
        ]
        )->validate();

        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('surname')) {
            $user->surname = $request->surname;
        }
        if ($request->has('birthday')) {
            $user->birthday = $request->birthday;
        }
        if ($request->has('gender')) {
            $user->gender = $request->gender;
        }
        if ($request->has('profile_photo')) {
            $image_name = time().$request->profile_photo->getClientOriginalName();
            $user->profile_photo = $image_name;
    
            Storage::disk('users')->put($user->name.'_'.$user->id.'/'.$user->profile_photo, File::get($request->profile_photo));
        }
        if (!$user->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $user->update();

        return response()->json($user,200);
    }
}
