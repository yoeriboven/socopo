<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PostRepository;

class PostController extends Controller
{
    /**
     * The PostRepository object.
     *
     * @var array
     */
    protected $posts;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PostRepository $posts)
    {
        $this->posts = $posts;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dump($this->posts->forUser(auth()->user()));
        return view('posts.index', [
            'posts' => $this->posts->forUser(auth()->user())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
}
