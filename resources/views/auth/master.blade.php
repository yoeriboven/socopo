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
    <title>@yield('page_title') - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600&amp;subset=latin-ext">
    <link href="{{ mix('css/dashboard.css') }}" rel="stylesheet">
    <style>
        #logo > h2{
            color:#4392F1;
            font-size:40px;
        }
        #logo:hover {
            text-decoration: none;
        }
        #logo:hover > h2 {
            color:#327dd6;
        }
    </style>
</head>
<body class="">
    <div class="page">
        <div class="page-single">
            <div class="container">
                <div class="row">
                    <div class="col col-login mx-auto">
                        <div class="text-center mb-6">
                            <a id="logo" href="{{ url('/') }}">
                                <h2>Socopo</h2>
                            </a>
                        </div>

                        @yield('content')

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
