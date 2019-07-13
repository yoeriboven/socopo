<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Language" content="en" />
    <meta name="msapplication-TileColor" content="#2d89ef">
    <meta name="theme-color" content="#4188c9">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('page_title') - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600&amp;subset=latin-ext">
    <link href="{{ mix('css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet" />
    @include('partials._google_analytics')
</head>
<body>
    <div id="app" class="page">
        <div class="flex-fill">
            @include('partials._header')

            <div class="my-3 my-md-5">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>

            @include('partials._footer')
        </div>
    </div>

<script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
@yield('footer')

</body>
</html>
