<?php

namespace App\Http\Controllers;

use App\User;
use App\Comment;
use App\Publication;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listComments(Request $request)
    {
        if ($request->has('search')&&!empty($request->search)) {
            $comment = Comment::withTrashed()->where('content', 'LIKE', '%'.$request->search.'%')
                                ->orWhere('status', 'LIKE', $request->search.'%')
                                ->with('users', 'publications.users')
                                ->paginate(10);
        } else {
            $comment = Comment::withTrashed()->with('users', 'publications.users')->paginate(10);
        }

        return view('comments.mainComments', ['data'=>$comment]);
    }

    public function commentsByUser($id,Request $request)
    {
        $user = User::withTrashed()->find($id);

        if ($request->has('search')&&!empty($request->search)) {
            $comments = Comment::withTrashed()->where('user_id', $id)
                                              ->orWhere('content', 'LIKE', '%'.$request->search.'%')
                                              ->get();
        }else{
            $comments = Comment::withTrashed()->where('user_id', $id)->get();
        }
        return view('comments.commentsByUser', ['user'=>$user,'data'=>$comments]);
    }

    public function desactivateComment(Request $request){

        $comment = Comment::findOrFail($request->comment_id);
        $comment->status = 'inactivo';
        $comment->update();

        $comment->delete();
        return back()->with(['message'=>'Comentario Desactivado Correctamente']);;
    }

    public function activateComment(Request $request){
        
        Comment::onlyTrashed()->find($request->comment_id)->restore();

        $comment = Comment::find($request->comment_id);
        $comment->status = 'activo';
        $comment->update();

        return back()->with(['message'=>'Comentario Activado Correctamente']);;
    }    
}
