<div class="header py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="d-flex">
                    <a class="header-brand" href="{{ route('home') }}">
                        <h2>Commenter</h2>
                    </a>

                    <div class="d-flex order-md-2 ml-auto">
                        <div class="nav-item d-md-flex">
                          <a href="{{ route('settings') }}"><i class="fe fe-settings"></i></a>
                        </div>
                        <div class="nav-item d-md-flex">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                <i class="fe fe-log-out"></i>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
