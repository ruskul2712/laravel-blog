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
    <title>{{ $post->title }} — Pulse</title>
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
                <a href="/hello" class="avatar-btn" title="Профиль">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</a>
            @endauth
            @include('partials.header-auth')
        </div>
    </div>
</header>

<main class="feed-shell">
    <div>
        <a href="{{ route('post.feed') }}" class="nav-link">← Назад в ленту</a>

        <article class="post-card" id="post-{{ $post->id }}" data-post-id="{{ $post->id }}">
            <div class="post-card-head">
                @if($post->user)
                    <a href="{{ route('users.show', $post->user) }}" class="avatar-circle">{{ mb_strtoupper(mb_substr($post->user->name, 0, 1)) }}</a>
                @else
                    <div class="avatar-circle">П</div>
                @endif

                <div class="post-head-meta">
                    <div class="post-username">
                        @if($post->user)
                            <a href="{{ route('users.show', $post->user) }}">{{ $post->user->name }}</a>
                        @else
                            Пользователь
                        @endif
                    </div>
                    <div class="post-time">{{ $post->created_at->format('d.m.Y H:i') }} · {{ $post->created_at->diffForHumans() }}</div>
                </div>

                @can('update', $post)
                    <div class="post-menu-wrap">
                        <button type="button" class="icon-btn post-menu-trigger">⋯</button>
                        <div class="post-menu-dropdown">
                            <button type="button" class="post-menu-edit" data-title="{{ $post->title }}" data-description="{{ $post->description }}">✏️ Редактировать</button>
                            <button type="button" class="post-menu-delete danger-item">🗑️ Удалить</button>
                        </div>
                    </div>
                @endcan
            </div>

            @if($post->image)
                <div class="post-media">
                    <img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}" style="width:100%; height:100%; object-fit:cover;">
                </div>
            @endif

            <div class="post-actions">
                <button type="button" class="icon-btn like-btn {{ $isLiked ? 'liked' : '' }}">{{ $isLiked ? '❤️' : '🤍' }}</button>
                <button type="button" class="icon-btn comment-focus-btn">💬</button>
                <button type="button" class="icon-btn repost-btn {{ $isReposted ? 'reposted' : '' }}" title="Репостнуть">📤</button>
                <span class="spacer"></span>
                <button type="button" class="icon-btn bookmark-btn {{ $isBookmarked ? 'saved' : '' }}">{{ $isBookmarked ? '📌' : '🔖' }}</button>
            </div>

            <div class="post-likes">
                Нравится: <span class="like-count">{{ $post->likes_count }}</span>
                <span class="stat-sep">·</span>
                Репостов: <span class="repost-count">{{ $post->reposts_count }}</span>
            </div>

            <div class="post-caption">
                <span class="cap-username">
                    @if($post->user)
                        <a href="{{ route('users.show', $post->user) }}">{{ $post->user->name }}</a>
                    @else
                        Пользователь
                    @endif
                </span><span class="cap-text"><strong class="cap-title">{{ $post->title }}</strong> {{ $post->description }}</span>
            </div>

            @if($post->category || $post->tags->isNotEmpty())
                <div class="post-tags">
                    @if($post->category)
                        <span class="post-category-badge">{{ $post->category->name }}</span>
                    @endif
                    @foreach($post->tags as $tag)
                        #{{ $tag->name }}
                    @endforeach
                </div>
            @endif

            <div class="post-comments">
                @forelse($post->comments as $comment)
                    <div class="comment-row" data-comment-id="{{ $comment->id }}">
                        @if($comment->user)
                            <a href="{{ route('users.show', $comment->user) }}" class="avatar-circle">{{ mb_strtoupper(mb_substr($comment->user->name, 0, 1)) }}</a>
                        @else
                            <div class="avatar-circle">П</div>
                        @endif
                        <div class="comment-body">
                            <div class="comment-text-line">
                                <span class="c-username">
                                    @if($comment->user)
                                        <a href="{{ route('users.show', $comment->user) }}">{{ $comment->user->name }}</a>
                                    @else
                                        Пользователь
                                    @endif
                                </span><span class="c-text">{{ $comment->body }}</span>
                                <div class="comment-time">{{ $comment->created_at->diffForHumans() }}</div>
                            </div>
                            <form class="comment-edit-form">
                                <input type="text" value="{{ $comment->body }}" placeholder=" ">
                                <button type="button" class="btn btn-primary btn-sm comment-save-btn">Сохранить</button>
                                <button type="button" class="btn btn-ghost btn-sm comment-cancel-btn">Отмена</button>
                            </form>
                        </div>
                        @can('update', $comment)
                            <div class="comment-tools">
                                <button type="button" class="comment-edit-btn" title="Редактировать">✏️</button>
                                <button type="button" class="comment-delete-btn" title="Удалить">🗑️</button>
                            </div>
                        @endcan
                    </div>
                @empty
                    <p class="no-comments-hint">Комментариев пока нет — будьте первым.</p>
                @endforelse
            </div>

            @auth
                <div class="add-comment-row">
                    <button type="button" class="emoji-pick">😊</button>
                    <input type="text" placeholder="Добавить комментарий...">
                    <button type="button" class="post-comment-btn" disabled>Опубликовать</button>
                </div>
            @endauth
        </article>
    </div>
</main>

{{-- Edit post modal --}}
<div class="modal-overlay" id="modal-edit-post">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Редактировать подпись</h3>
            <button type="button" class="modal-close" data-close-modal>✕</button>
        </div>
        <form id="form-edit-post">
            <div class="modal-body">
                <div class="field">
                    <input type="text" id="edit-post-title" placeholder=" ">
                    <label>Заголовок публикации</label>
                </div>
                <div class="field">
                    <textarea id="edit-post-caption" placeholder=" " rows="3"></textarea>
                    <label>Подпись</label>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-ghost" data-close-modal>Отмена</button>
                <button type="submit" class="btn btn-gradient">Сохранить</button>
            </div>
        </form>
    </div>
</div>

{{-- Confirm modal (reused for delete post / comment) --}}
<div class="modal-overlay" id="modal-confirm">
    <div class="modal-box" style="max-width: 360px;">
        <div class="modal-header">
            <h3>Подтвердите действие</h3>
            <button type="button" class="modal-close" data-close-modal>✕</button>
        </div>
        <p class="modal-body-tight confirm-message">Вы уверены?</p>
        <div class="modal-actions">
            <button type="button" class="btn btn-ghost" data-close-modal>Отмена</button>
            <button type="button" class="btn btn-danger" id="confirm-action-btn">Удалить</button>
        </div>
    </div>
</div>

<div id="app-toast" class="toast"></div>

<script src="{{ asset('js/app.js') }}"></script>

@if(session('status'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var toast = document.getElementById('app-toast');
            if (!toast) return;
            toast.textContent = @json(session('status'));
            toast.classList.add('show');
            setTimeout(function () { toast.classList.remove('show'); }, 2800);
        });
    </script>
@endif

</body>
</html>
