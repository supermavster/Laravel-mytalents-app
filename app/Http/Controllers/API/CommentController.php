<?php

namespace App\Http\Controllers\API;

use App\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comment = Comment::all();
        return response()->json($comment, 200);
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

        $publicationRoot = Publication::find($request->publication_id);

        $validate = $this->validate($request, [
                'content'=>'string|required'
            ]);

        $fields = $request->all();
        $fields['user_id'] = 5;
        $fields['publication_id'] = 1;
        $fields['content'] = $request->content;
        $fields['status'] = Comment::COMMENT_ACTIVE;

        $comment = Comment::create($fields);

        return response()->json($comment, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $comment = Comment::find($id);
        return response()->json($comment, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Comment $comment)
    {
       $myComment = Comment::find($comment);

        Validator::make($request->all(), [
            'content'=>'string'
        ]);

        if ($request->has('content')) {
            $myComment->content = $request->content;
        }

        if (!$myComment->isDirty()) {
            return response()->json([
                'error'=>'Se debe especificar al menos un valor diferente para actualizar',
                'code'=>422], 422);
        }

        $myComment->update();

        return response()->json($myComment, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response()->json($comment, 200);
    }
}
