<header class="bd-header">
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/home">Sport Record</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    @if (Auth::check())
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="javascript:;">Mypage(작업중)</a>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/api/map/edit">Register</a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/api/map">List</a>
                    </li>
                    @if (Auth::check())
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/logout">Logout</a>
                    </li>
                    @endif
                    @yield('masternav_extra_item')
                </ul>

            </div>
        </div>
    </nav>
</header>
@yield('masternav_script')
