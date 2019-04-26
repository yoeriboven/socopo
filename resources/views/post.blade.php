
<div class="card insta_post">
	<div class="row">
		<div class="col-md-8 insta_image">
			<img src="{{ $post->image_url }}" />
		</div>
		<div class="col-md-4">

		<div class="d-flex align-items-center pt-2 mt-auto">
			<a href="https://www.instagram.com/{{ $post->profile->username }}" target="_blank" rel="noopener">
				<div class="avatar avatar-md mr-3" style="background-image: url({{ $post->profile->avatar }})"></div>
			</a>

			<div>
				<a href="./profile.html" class="text-default">{{ '@'.$post->profile->username}}</a>
				<small class="d-block text-muted">{{ $post->posted_at->diffForHumans() }}</small>
			</div>
		</div>

		<p class="caption">
			{{ $post->caption }}
		</p>

		<a class="btn btn-primary" href="{{ $post->post_url }}" target="_blank" rel="noopener">Comment on post</a>

		</div>
	</div>
</div>
