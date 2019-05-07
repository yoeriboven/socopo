<div class="header py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="d-flex">
                    <a class="header-brand" href="/">
                        <h2>Commenter</h2>
                    </a>

                    @unless (isset($hideButtons) && $hideButtons)
                        <div class="d-flex order-lg-2 ml-auto">
                            <div class="nav-item d-none d-md-flex">
                              <a href="/settings"><i class="fe fe-settings"></i></a>
                            </div>
                            <div class="nav-item d-none d-md-flex">
                                <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    <i class="fe fe-log-out"></i>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </div>
                        <a href="#" class="header-toggler d-lg-none ml-3 ml-lg-0" data-toggle="collapse" data-target="#mainMenu">
                            <span class="header-toggler-icon"></span>
                        </a>
                    @endunless
                </div>
            </div>
        </div>
    </div>
</div>
