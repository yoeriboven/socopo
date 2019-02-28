@extends('master')

@section('page_title', 'Posts')


@section('content')

<div class="card" style="min-height: 1000px;">
    @foreach ($posts as $post)
    	{{ $post->caption }}
    	<hr/>
    @endforeach
</div>

@endsection
