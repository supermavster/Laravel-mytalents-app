<?php

namespace App\Http\Controllers;

use App\User;
use App\Publication;
use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PublicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /*
         * Funcion que trae la lista de todas las publicaciones con el numero de comentarios
         */
    public function listPublications(Request $request)
    {
        if ($request->has('search')&&!empty($request->search)) {
            $publications = Publication::withTrashed()
                                        ->where('description', 'LIKE', '%'.$request->search.'%')
                                        ->orWhere('status', 'LIKE', $request->search.'%')
                                        ->withCount('comments')
                                        ->with('users')
                                        ->paginate(10);
        } else {
            $publications = Publication::withTrashed()->withCount('comments')
                ->with('users')
                ->paginate(10);
        }

        return view('publications.mainPublications', ['data'=>$publications]);
    }

    /*
     * Funcion que trae las publicaciones de un usuario especifico
     */
    public function publicationsByUser($id)
    {
        $user = User::withTrashed()->find($id);

        $myPublications = Publication::withTrashed()->where('user_id', $user->id)
            ->withCount('comments')
            ->with('users')
            ->paginate(10);
        return view('publications.publicationsByUser', ['user'=>$user,'data'=>$myPublications]);
    }

    /*
     * Funcion que trae los comentarios de una publicacion especifica
    */
    public function commentsByPublication($publication, $user)
    {
        $user = User::withTrashed()->find($user);

        $myPublication = Publication::withTrashed()->where('id', $publication)
            ->with('comments.users')->get();

        //return $myPublication;

        return view('publications.commentsByPublication', ['user'=>$user,'data'=>$myPublication]);
    }

    public function publicationSpecific($id)
    {
        $publication = Publication::withTrashed()->where('id', $id)->withCount('comments')
                ->with('users')
                ->get();

        //return $publication;
        return view('publications.publicationSpecific', ['data'=>$publication]);
    }

    public function desactivatePublication(Request $request){
        
        $publication = Publication::findOrFail($request->publication_id);
        $publication->status='inactivo';
        $publication->update();

        $publication->delete();

        return back()->with(['message'=>'Publicación Desactivada Correctamente']);
    }

    public function activatePublication(Request $request){

        Publication::onlyTrashed()->find($request->publication_id)->restore();

        $publication = Publication::find($request->publication_id);
        $publication->status = 'activo';
        $publication->update();
        
        return back()->with(['message'=>'Publicación Activada Correctamente']);
    }  
}
