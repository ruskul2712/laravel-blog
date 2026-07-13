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
    <title>Pulse — Поиск{{ $query !== '' ? ': '.$query : '' }}</title>
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
            <a href="{{ route('profile.show') }}" class="nav-link">Профиль</a>
        </nav>
        <div class="header-icons">
            @include('partials.notifications-bell')
            <button type="button" class="icon-btn theme-toggle" title="Сменить тему">
                <span class="icon-light">🌙</span><span class="icon-dark">☀️</span>
            </button>
            @auth
                <a href="{{ route('profile.show') }}" class="avatar-btn" title="Мой профиль">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</a>
            @endauth
            @include('partials.header-auth')
        </div>
    </div>
</header>

<main class="profile-shell">
    <section class="profile-header" style="margin-bottom: 8px;">
        <div class="profile-main">
            <div class="profile-name-row">
                <span class="profile-username">Результаты поиска{{ $query !== '' ? ': “'.$query.'”' : '' }}</span>
            </div>
        </div>
    </section>

    @if($query === '')
        <div class="empty-state">
            <div class="empty-emoji">🔍</div>
            <h3>Введите запрос</h3>
            <p>Ищите людей, публикации или комментарии под постами.</p>
        </div>
    @else
        <h4 style="margin: 18px 4px 8px;">Люди</h4>
        <div class="connections-list">
            @forelse($users as $person)
                <div class="connection-row">
                    <a href="{{ route('users.show', $person) }}" class="avatar-circle">
                        @if($person->avatar_url)
                            <img src="{{ $person->avatar_url }}" alt="{{ $person->name }}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                        @else
                            {{ mb_strtoupper(mb_substr($person->name, 0, 1)) }}
                        @endif
                    </a>
                    <div class="connection-info">
                        <a href="{{ route('users.show', $person) }}" class="connection-name">{{ $person->name }}</a>
                        @if($person->bio)
                            <div class="connection-bio">{{ Str::limit($person->bio, 60) }}</div>
                        @endif
                    </div>
                </div>
            @empty
                <p class="no-comments-hint">Никого не нашли.</p>
            @endforelse
        </div>

        <h4 style="margin: 24px 4px 8px;">Публикации</h4>
        <div class="connections-list">
            @forelse($posts as $post)
                <a href="{{ route('post.feed') }}#post-{{ $post->id }}" class="connection-row" style="text-decoration:none;">
                    <div class="avatar-circle">
                        @if($post->user)
                            {{ mb_strtoupper(mb_substr($post->user->name, 0, 1)) }}
                        @else
                            П
                        @endif
                    </div>
                    <div class="connection-info">
                        <span class="connection-name">{{ $post->title }}</span>
                        <div class="connection-bio">{{ Str::limit($post->description, 90) }}</div>
                    </div>
                </a>
            @empty
                <p class="no-comments-hint">Публикаций не найдено.</p>
            @endforelse
        </div>

        <h4 style="margin: 24px 4px 8px;">Комментарии</h4>
        <div class="connections-list">
            @forelse($comments as $comment)
                <a href="{{ route('post.feed') }}#post-{{ $comment->post_id }}" class="connection-row" style="text-decoration:none;">
                    <div class="avatar-circle">
                        @if($comment->user)
                            {{ mb_strtoupper(mb_substr($comment->user->name, 0, 1)) }}
                        @else
                            П
                        @endif
                    </div>
                    <div class="connection-info">
                        <span class="connection-name">{{ $comment->user->name ?? 'Пользователь' }}</span>
                        <div class="connection-bio">{{ Str::limit($comment->body, 90) }}</div>
                    </div>
                </a>
            @empty
                <p class="no-comments-hint">Комментариев не найдено.</p>
            @endforelse
        </div>
    @endif
</main>

<div id="app-toast" class="toast"></div>

<script src="{{ asset('js/app.js') }}"></script>

</body>
</html>
