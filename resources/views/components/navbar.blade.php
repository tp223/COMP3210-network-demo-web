<div class="container">
    <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
            <span class="fs-4">Indoor Mapping Demo</span>
        </a>

        <ul class="nav nav-pills">
            <li class="nav-item"><a href="{{ route('index') }}" class="nav-link @if (Route::currentRouteName() == 'index') active @endif" aria-current="page">Home</a></li>
            @if (Auth::check())
                <li class="nav-item"><a href="{{ route('dashboard') }}" class="nav-link @if (Route::currentRouteName() == 'dashboard') active @endif">Dashboard</a></li>
                <li class="nav-item"><a href="{{ route('logout') }}" class="nav-link">Logout</a></li>
            @else
                <li class="nav-item"><a href="{{ route('login') }}" class="nav-link @if (Route::currentRouteName() == 'login') active @endif">Login</a></li>
                <li class="nav-item"><a href="{{ route('register') }}" class="nav-link @if (Route::currentRouteName() == 'register') active @endif">Register</a></li>
            @endif
        </ul>
    </header>
</div>