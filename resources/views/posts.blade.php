@extends('master')

@section('page_title', 'Posts')


@section('content')

@if (Session::has('success'))
	<div class="alert alert-icon alert-success full-width" role="alert">
		<i class="fe fe-check mr-2" aria-hidden="true"></i> {{ Session::get('success') }}
	</div>
@endif

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

{{ $posts->links() }}

<profiles-modal-component></profiles-modal-component>

@endsection
