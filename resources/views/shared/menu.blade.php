<nav id="menu" class="navbar navbar-expand-lg bg-gold">

    <section id="social" class="d-none d-sm-block">
        Retrouvez-nous - <i class="fab fa-facebook"></i> - <i class="fas fa-at"></i>
    </section>


    <a class="navbar-brand" href="{{ url('/') }}">
        <img src="{{ asset('img/logo.png') }}" alt="LOGO">  {{ config('app.name', 'Hanif - Forum') }} - BETA
    </a>

    <button class="navbar-toggler"
            type="button"
            data-toggle="collapse"
            data-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation">
        @if(Auth::check())
            <img class="rounded float-left" src="{{ asset(Auth::user()->picture) }}" alt="#"> {{ Auth::user()->pseudo }}
        @else
            Menu
        @endif
        <i class="fas fa-bars"></i>
    </button>

    <div id="navbarNav" class="collapse navbar-collapse">
        <ul id="navbar" class="navbar-nav ml-auto">
            @restrictedArea('guest')
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Se connecter</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">S'inscrire</a></li>
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profil') }}">
                        <img class="rounded float-left" src="{{ asset(Auth::user()->picture) }}" alt="#"> Mon profil
                    </a>
                </li>
                @restrictedArea('vip')
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="{{ url('dashboard-vip') }}">Modération</a>
                    </li>
                @endrestrictedArea

                @restrictedArea('admin')
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="{{ url('/administration') }}">Administration</a>
                    </li>
                @endrestrictedArea

                <li class="nav-item">
                    <a class="nav-link"
                       href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Déconnexion
                    </a>
                </li>
            @endrestrictedArea
        </ul>
    </div>

    <!-- nothing special here lol -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>
</nav>