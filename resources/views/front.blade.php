<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSS -->
    <link href="{{ mix('css/front.css') }}" rel="stylesheet" />

    <title>Top Comments on Instagram - {{ config('app.name', 'Socopo') }}</title>
</head>
<body>
    <div class="container">
        <nav>
            <div class="row">
                <div class="col-md-6">
                    <h1 id="logo">Socopo</h1>
                </div>

                <div class="col-md-6" style="">
                    @auth
                        <a href="{{ route('home') }}" class="btn btn-primary float-right ml-3">View Dashboard</a>
                    @endauth

                    @guest
                        <a href="{{ route('register') }}" class="btn btn-primary float-right ml-3">Try for free</a>
                        <a href="{{ route('login') }}" class="btn float-right">Log in</a>
                    @endguest
                </div>
            </div>

        </nav>

        <div class="row pt-3">
            <main class="col-7">
                <h2>Top Comments on Instagram</h2>
                <h3>Get more engagement by being the first comment users see.</h3>
                <p>Immediately write a comment when a user uploads a new post and it'll have the greatest amount of impressions.</p>
                <p class="mb-3">Add Instagram profiles to {{ config('app.name', 'Socopo') }} and we will continuously check whether they upload something new. You will receive a notification on Slack the second a new post shows up.</p>
                <a href="{{ route('register') }}" class="btn btn-lg btn-primary mt-2">Try for free</a>
            </main>

            <aside class="col-5">
                <div class="feature-post mt-4">
                    <img src="{{ asset('images/insta-post.png') }}" />
                </div>
            </aside>
        </div>

        <footer>
            &#169; 2019 - <a href="https://www.yoeri.me" target="_blank" rel="noopener">Yoeri.me</a> - <a href="mailto:yoeri@yoeri.me">Contact</a>
        </footer>
    </div>
</body>
</html>
