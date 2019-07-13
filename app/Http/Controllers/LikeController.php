<?php

namespace App\Http\Controllers;

use App\Like;
use App\Publication;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LikeController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
}
