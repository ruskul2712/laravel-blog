@auth
    <div class="header-auth">
        <span class="header-auth-name" title="{{ Auth::user()->email }}">{{ Auth::user()->name }}</span>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-ghost btn-sm">Выйти</button>
        </form>
    </div>
@else
    <div class="header-auth">
        <a href="{{ route('login') }}" class="btn btn-ghost btn-sm">Войти</a>
        <a href="{{ route('register') }}" class="btn btn-gradient btn-sm">Регистрация</a>
    </div>
@endauth
