<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            </ul>

            @if (!Auth::check())
                <a class="nav-link " href="{{ route('login') }}" tabindex="-1" aria-disabled="true"><button
                        class="btn btn-outline-success me-2" type="submit">Login</button></a>
            @else
                <a href="#" class="nav-link dropdown-toggle me-2" data-bs-toggle="dropdown">
                    <span class="d-none d-md-inline">{{ Auth::user()->name ?? '' }}</span>
                </a>
                <ul class="dropdown-menu  dropdown-menu-end" style="border: none">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger me-2">Log out</button>
                    </form>
                </ul>
            @endif

            <a class="nav-link " href="{{ route('category.index') }}" tabindex="-1" aria-disabled="true"><button
                    class="btn btn-outline-success" type="submit">Dashbaord</button></a>
        </div>
    </div>
</nav>