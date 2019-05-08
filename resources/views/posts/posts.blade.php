@extends('master')

@section('page_title', 'Posts')


@section('content')

@if (Session::has('success'))
	<div class="alert alert-icon alert-success full-width" role="alert">
		<i class="fe fe-check mr-2" aria-hidden="true"></i> {{ Session::get('success') }}
	</div>
@endif

@if (Session::has('status'))
	<div class="alert alert-icon alert-success full-width" role="alert">
		<i class="fe fe-check mr-2" aria-hidden="true"></i> {{ Session::get('status') }}
	</div>
@endif

<p class="text-right font-weight-bold"><a href="#profiles" id="modalOpener" data-toggle="modal" data-target="#profilesModal">Manage profiles</a></p>

@if (!$hasProfiles || !$hasSlack)
	@include('posts._set_up')
@elseif (count($posts) == 0)
	@include('posts._no_posts')
@else
    @each('posts._post', $posts, 'post')

    {{ $posts->links() }}
@endif

<profiles-modal-component></profiles-modal-component>

@endsection
