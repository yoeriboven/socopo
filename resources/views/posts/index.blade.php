@extends('master')

@section('page_title', 'Posts')


@section('content')
<p class="text-right font-weight-bold"><a href="#profiles" id="modalOpener" data-toggle="modal" data-target="#profilesModal">Manage profiles</a></p>

<div class="card" style="min-height: 1000px;">
    @foreach ($posts as $post)
    	{{ $post->caption }}
    	<a href="">{{ '@'.$post->profile->username}}</a>
    	<hr/>
    @endforeach
</div>

<profiles-modal-component></profiles-modal-component>

@endsection
