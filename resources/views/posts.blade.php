@extends('master')

@section('page_title', 'Posts')


@section('content')
<p class="text-right font-weight-bold"><a href="#profiles" id="modalOpener" data-toggle="modal" data-target="#profilesModal">Manage profiles</a></p>

<div class="card" style="min-height: 1000px;">
	@if (count($posts) == 0)
		Geen posts. Ga naar profile manager.
	@else
	    @foreach ($posts as $post)
	    	{{ $post->caption }}
	    	<a href="https://www.instagram.com/{{ $post->profile->username}}"
				target="_blank"
	    		rel="noopener">
	    		{{ '@'.$post->profile->username}}
	    	</a>
	    	<img src="{{ $post->image_url }}" />
	    	{{ $post->posted_at->diffForHumans() }}
	    	<hr/>
	    @endforeach
    @endif
</div>

<profiles-modal-component></profiles-modal-component>

@endsection
