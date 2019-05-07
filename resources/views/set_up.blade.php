<div class="card text-center" style="padding:30px 0;">
	<h3>Set up your account</h3>

	<div class="row justify-content-center {{ $hasProfiles ? 'text-muted' : ''}}" style="margin-top:20px;">
		<div class="col-5">
			<h4><strong>Step 1: </strong>Add profiles</h4>
			<p>Profiles are the Instagram accounts of which you want to be notified of new posts.</p>
			<a href="#profiles" class="btn btn-primary {{ $hasProfiles ? 'disabled' : ''}}" data-toggle="modal" data-target="#profilesModal">Add profiles</a>
		</div>
	</div>

	<div class="row justify-content-center {{ $hasSlack ? 'text-muted' : ''}}" style="margin-top:40px;">
		<div class="col-5">
			<h4><strong>Step 2: </strong>Connect to Slack</h4>
			<p>We need permission to send notifications to your Slack workspace.</p>
			<a href="{{ route('slack.login') }}" class="btn btn-primary {{ $hasSlack ? 'disabled' : ''}}">Connect to Slack</a>
		</div>
	</div>
</div>
