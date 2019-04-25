<?php

namespace App\Http\Controllers;

use App\Repositories\PostRepository;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PostRepository $posts)
    {
        return view('posts', [
            'posts' => $posts->forUser(auth()->user(), 10)
        ]);
    }
}
