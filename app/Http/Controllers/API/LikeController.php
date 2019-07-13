<?php

namespace App\Http\Controllers\API;

use App\Like;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $likes = Like::all();
        return response()->json($likes, 200);
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
        
        $like_exist = Like::where('user_id', $user->id)
                          ->where('publication_id', $request->publication_id)
                          ->count();

        if ($like_exist == 0) {
            $like = new Like();

            $like->user_id = $user->id;
            $like->publication_id = $request->publication_id;

            $like->save();

            return response()->json([
                'like'=>$like
            ]);
        } else {
            return response()->json([
                'message'=>'El like ya existe'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
          $user = \Auth::user();
        
        //ver si existe el like y no duplicarlo
        $like = Like::where('user_id', $user->id)
                          ->where('publication_id', $publication_id)
                          ->first();

        if ($like) {
            $like->delete();

            return response()->json([
                'like'=>$like,
                'message'=>'Has dado dislike'
            ]);
        } else {
            return response()->json([
                'message'=>'El like NO existe'
            ]);
        }
    }
}
