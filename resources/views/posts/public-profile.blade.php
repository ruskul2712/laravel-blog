<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <script>
        (function () {
            var saved = localStorage.getItem('pulse-theme');
            var theme = saved || (window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark');
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages.css') }}">
    <title>Pulse — Профиль {{ $user->name }}</title>
</head>
<body>

<header class="site-header">
    <div class="container header-inner">
        <a href="/" class="logo">
            <span class="logo-mark">💫</span>
            <span class="logo-text">Pulse</span>
        </a>
        @include('partials.search-box')
        <nav class="main-nav">
            <a href="/" class="nav-link">Главная</a>
            <a href="/post" class="nav-link">Лента</a>
            <a href="/hello" class="nav-link">Профиль</a>
        </nav>
        <div class="header-icons">
            @include('partials.notifications-bell')
            <button type="button" class="icon-btn theme-toggle" title="Сменить тему">
                <span class="icon-light">🌙</span><span class="icon-dark">☀️</span>
            </button>
            @auth
                <a href="/hello" class="avatar-btn" title="Мой профиль">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</a>
            @endauth
            @include('partials.header-auth')
        </div>
    </div>
</header>

<main class="profile-shell">
    <section class="profile-header">
        <div class="avatar-circle profile-avatar">
            @if($user->avatar_url)
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
            @else
                {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
            @endif
        </div>

        <div class="profile-main">
            <div class="profile-name-row">
                <span class="profile-username">{{ $user->name }}</span>
                @auth
                    <button type="button" class="btn {{ $isFollowing ? 'btn-ghost' : 'btn-gradient' }} btn-sm follow-btn {{ $isFollowing ? 'is-following' : '' }}" data-user-id="{{ $user->id }}">{{ $isFollowing ? 'Вы подписаны' : 'Подписаться' }}</button>
                @endauth
            </div>

            <div class="profile-stats">
                <span class="profile-stat"><b>{{ $posts->count() }}</b>публикаций</span>
                <a href="{{ route('users.followers', $user) }}" class="profile-stat"><b id="followersCount">{{ $followersCount }}</b>подписчиков</a>
                <a href="{{ route('users.following', $user) }}" class="profile-stat"><b>{{ $followingCount }}</b>подписок</a>
            </div>

            <p class="profile-bio">{{ $user->bio ?: 'Пользователь пока ничего не рассказал(а) о себе.' }}</p>
        </div>
    </section>

    <nav class="profile-tabs">
        <button type="button" class="profile-tab active" data-target="tab-posts">📸 Публикации</button>
    </nav>

    <div class="profile-tab-panel active" id="tab-posts">
        @if($posts->isEmpty())
            <div class="empty-state">
                <div class="empty-emoji">📸</div>
                <h3>Пока нет публикаций</h3>
                <p>{{ $user->name }} ещё ничего не опубликовал(а).</p>
            </div>
        @else
            <div class="post-grid">
                @foreach($posts as $post)
                    <div class="grid-tile" data-post-id="{{ $post->id }}"
                         @if($post->imageUrl())
                             style="background-image:url('{{ $post->imageUrl() }}');background-size:cover;background-position:center;"
                         @else
                             style="background: linear-gradient(135deg,#1f2937,#405de6);"
                         @endif>
                        @unless($post->imageUrl())
                            📝
                        @endunless
                        <div class="grid-overlay">❤️ {{ $post->likes_count }} &nbsp; 💬 {{ $post->comments_count }}</div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</main>

<div id="app-toast" class="toast"></div>

<script src="{{ asset('js/app.js') }}"></script>

</body>
</html>
