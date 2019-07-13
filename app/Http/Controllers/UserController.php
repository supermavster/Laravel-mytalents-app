<?php

namespace App\Http\Controllers;

use App\Like;
use App\User;
use App\Talent;
use App\Follower;
use App\TalentType;
use App\Publication;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listUsers(Request $request)
    {
        if ($request->has('search')&&!empty($request->search)){
            $users = User::withTrashed()->where('name', 'LIKE', '%'.$request->search.'%')
                            ->orWhere('surname', 'LIKE', '%'.$request->search.'%')
                            ->orWhere('email', 'LIKE', '%'.$request->search.'%')
                            ->orWhere('phone', 'LIKE', '%'.$request->search.'%')
                            ->orWhere('status', 'LIKE', $request->search.'%')
                            ->withCount('publications', 'comments')
                            ->paginate(10);
        } else {
            $users = User::withTrashed()->withCount('publications', 'comments')->paginate(10);
        }

        return view('users.mainUsers', ['data'=>$users]);
    }

    public function userProfile($id)
    {
        //data basic user
        $profile = User::withTrashed()->find($id);

        //total likes for all publications
        $publications = Publication::where('user_id', $id)->withCount('likes')->get();

        //talents
        $talent_type = TalentType::all();
        $myTalents = User::withTrashed()->find($id)->talents;
        $talentsMusic = Talent::where('talent_type_id', 1)->get();
        $talentsSport = Talent::where('talent_type_id', 2)->get();

        //return $myTalents;

        //total number of followers and continued
        $seguidores = Follower::where('user_id', $id)->count();
        $seguidos = Follower::where('follower_id', $id)->count();

        //list of followers
        $listSeguidores = User::withTrashed()->find($id)->followers()->with('follower')->get();
        $listSeguidos = User::withTrashed()->find($id)->followings()->with('user')->get();

        //return $listSeguidores;

        return view('users.userProfile', ['seguidos'=>$seguidos,'seguidores'=>$seguidores,'listSeguidos'=>$listSeguidos,'listSeguidores'=>$listSeguidores,'likes'=>$publications,'talentsMusic'=>$talentsMusic,'talentsSport'=>$talentsSport,'talent_type'=>$talent_type,'myTalents'=>$myTalents,'data'=>$profile]);
    }

    public function userModify($id, Request $request)
    {

        $user = User::find($id);

        $credentials = $request->only(
          'gender',
          'phone'
            );
        
        Validator::make(
            $credentials,
            [
            'gender'        =>  'string',
            'phone'         =>  'numeric'
        ],
            [
            'gender.string'    =>  'El genero no puede estar vacio',
            'phone.numeric'    =>  'El numero telefonico solo puede contener valores numericos'
        ])->validate();

        if ($request->has('gender')) {
            $user->gender = $request->gender;
        }
        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }      

        if ($request->has('listTalents')) {
            
            $user->talents()->detach();

            foreach ($request->listTalents as $talent) {
                DB::table('talent_user')
                    ->insert(array('user_id'=>$id,'talent_id' => $talent));
   
            }
        }
        
        $user->update();

        $message = 'Usuario Modificado Satisfactoriamente';

        if ($request->ajax()) {
            return $message;
        }        
    }

    public function activateUser(Request $request){
        User::onlyTrashed()->find($request->user_id)->restore();
        $user = User::find($request->user_id);
        $user->status = 'activo';
        $user->save();

        return back()->with(['message'=>'Usuario Activado Correctamente']);
    }

    public function desactivateUser(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $user->status = 'inactivo';
        $user->save();

        $user->delete();
        return back()->with(['message'=>'Usuario Desactivado Correctamente']);
    }

}
