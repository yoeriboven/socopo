<?php

namespace App\Http\Controllers;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('posts.posts', [
            'posts' => auth()->user()->feed(10),
        ]);
    }
}
