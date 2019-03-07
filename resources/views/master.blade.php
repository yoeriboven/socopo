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
    <link rel="icon" href="./favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" type="image/x-icon" href="./favicon.ico" />
    <title>@yield('page_title') - Commenter</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,300i,400,400i,500,500i,600,600i,700,700i&amp;subset=latin-ext">
   {{--  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900|Montserrat:400,500,600,700" rel="stylesheet"> --}}
    <link href="https://fonts.googleapis.com/css?family=Mukta:400,500,600|Oxygen:400,700|Rubik:400,500,700|Ubuntu:400,500,700" rel="stylesheet">

    <link href="{{ mix('css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet" />
</head>
<body>
    <div id="app" class="page">
        <div class="page-main">
            <div class="header py-5">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <div class="d-flex">
                                <a class="header-brand" href="/">
                                    <h2>Commenter</h2>
                                </a>
                                <div class="d-flex order-lg-2 ml-auto">
                                    <div class="dropdown">
                                        <a href="#" class="nav-link pr-0 leading-none" data-toggle="dropdown">
                                            <span class="avatar" style="background-image: url('demo/images/37.jpg');"></span>
                                            <span class="ml-2 d-none d-lg-block">
                                                <span class="text-default">Jane Pearson</span>
                                                <small class="text-muted d-block mt-1">Administrator</small>
                                            </span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item" href="#">
                                                <i class="dropdown-icon fe fe-user"></i> Profile
                                            </a>

                                            <a class="dropdown-item" href="#">
                                                <i class="dropdown-icon fe fe-settings"></i> Settings
                                            </a>

                                            <a class="dropdown-item" href="#">
                                            <span class="float-right"><span class="badge badge-primary">6</span></span>
                                                <i class="dropdown-icon fe fe-mail"></i> Inbox
                                            </a>

                                            <a class="dropdown-item" href="#">
                                                <i class="dropdown-icon fe fe-send"></i> Message
                                            </a>

                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">
                                                <i class="dropdown-icon fe fe-help-circle"></i> Need help?
                                            </a>

                                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                             document.getElementById('logout-form').submit();">
                                                <i class="dropdown-icon fe fe-log-out"></i> Sign out
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <a href="#" class="header-toggler d-lg-none ml-3 ml-lg-0" data-toggle="collapse" data-target="#mainMenu">
                                    <span class="header-toggler-icon"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="my-3 my-md-5">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
</body>
</html>
