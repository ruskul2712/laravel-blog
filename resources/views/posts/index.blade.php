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
    <title>Posts</title>
</head>
<body>

<header class="site-header">
    <div class="container header-inner">
        <a href="/" class="logo">
            <span class="logo-mark">💫</span>
            <span class="logo-text">Pulse</span>
        </a>
        <div class="header-search">
            <span class="search-icon">🔍</span>
            <input type="text" placeholder="Поиск" disabled>
        </div>
        <nav class="main-nav">
            <a href="/" class="nav-link">Главная</a>
            <a href="/post" class="nav-link active">Лента</a>
            <a href="/hello" class="nav-link">Профиль</a>
        </nav>
        <div class="header-icons">
            <button type="button" class="icon-btn" data-open-modal="modal-new-post" title="Новая публикация">➕</button>
            <button type="button" class="icon-btn" title="Уведомления">🔔</button>
            <button type="button" class="icon-btn theme-toggle" title="Сменить тему">
                <span class="icon-light">🌙</span><span class="icon-dark">☀️</span>
            </button>
            <a href="/hello" class="avatar-btn" title="Профиль">Р</a>
        </div>
    </div>
</header>

<main class="feed-shell">
    <div>
        <div class="stories-row">
            <div class="story-item">
                <div class="story-ring is-you"><div class="story-avatar">➕</div></div>
                <span class="story-name">Ваша история</span>
            </div>
            <div class="story-item">
                <div class="story-ring"><div class="story-avatar">🧑‍🎨</div></div>
                <span class="story-name">anna</span>
            </div>
            <div class="story-item">
                <div class="story-ring"><div class="story-avatar">🎮</div></div>
                <span class="story-name">igor</span>
            </div>
            <div class="story-item">
                <div class="story-ring seen"><div class="story-avatar">🍰</div></div>
                <span class="story-name">marina</span>
            </div>
            <div class="story-item">
                <div class="story-ring seen"><div class="story-avatar">🚴</div></div>
                <span class="story-name">dima</span>
            </div>
            <div class="story-item">
                <div class="story-ring"><div class="story-avatar">📷</div></div>
                <span class="story-name">sonya</span>
            </div>
        </div>

        @forelse($posts as $post)
            <article class="post-card" id="post-{{ $post->id }}" data-post-id="{{ $post->id }}" style="--delay: {{ min($loop->index * 0.05, 0.4) }}s">
                <div class="post-card-head">
                    <div class="avatar-circle">{{ mb_strtoupper(mb_substr($post->user->name ?? 'П', 0, 1)) }}</div>

                    <div class="post-head-meta">
                        <div class="post-username">{{ $post->user->name ?? 'Пользователь' }}</div>
                        <div class="post-time">{{ $post->created_at->diffForHumans() }}</div>
                    </div>

                    <div class="post-menu-wrap">
                        <button type="button" class="icon-btn post-menu-trigger">⋯</button>
                        <div class="post-menu-dropdown">
                            <button type="button" class="post-menu-edit" data-title="{{ $post->title }}" data-description="{{ $post->description }}">✏️ Редактировать</button>
                            <button type="button" class="post-menu-delete danger-item">🗑️ Удалить</button>
                        </div>
                    </div>
                </div>

                @if($post->image)
                    <div class="post-media">
                        <img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}" style="width:100%; height:100%; object-fit:cover;">
                    </div>
                @endif

                <div class="post-actions">
                    <button type="button" class="icon-btn like-btn {{ in_array($post->id, $likedPostIds) ? 'liked' : '' }}">{{ in_array($post->id, $likedPostIds) ? '❤️' : '🤍' }}</button>
                    <button type="button" class="icon-btn comment-focus-btn">💬</button>
                    <button type="button" class="icon-btn repost-btn {{ in_array($post->id, $repostedPostIds) ? 'reposted' : '' }}" title="Репостнуть">📤</button>
                    <span class="spacer"></span>
                    <button type="button" class="icon-btn bookmark-btn {{ in_array($post->id, $bookmarkedPostIds) ? 'saved' : '' }}">{{ in_array($post->id, $bookmarkedPostIds) ? '📌' : '🔖' }}</button>
                </div>

                <div class="post-likes">
                    Нравится: <span class="like-count">{{ $post->likes_count }}</span>
                    <span class="stat-sep">·</span>
                    Репостов: <span class="repost-count">{{ $post->reposts_count }}</span>
                </div>

                <div class="post-caption">
                    <span class="cap-username">{{ $post->user->name ?? 'Пользователь' }}</span><span class="cap-text"><strong class="cap-title">{{ $post->title }}</strong> {{ $post->description }}</span>
                </div>

                <div class="post-comments">
                    @forelse($post->comments as $comment)
                        <div class="comment-row" data-comment-id="{{ $comment->id }}">
                            <div class="avatar-circle">{{ mb_strtoupper(mb_substr($comment->user->name ?? 'П', 0, 1)) }}</div>
                            <div class="comment-body">
                                <div class="comment-text-line">
                                    <span class="c-username">{{ $comment->user->name ?? 'Пользователь' }}</span><span class="c-text">{{ $comment->body }}</span>
                                    <div class="comment-time">{{ $comment->created_at->diffForHumans() }}</div>
                                </div>
                                <form class="comment-edit-form">
                                    <input type="text" value="{{ $comment->body }}" placeholder=" ">
                                    <button type="button" class="btn btn-primary btn-sm comment-save-btn">Сохранить</button>
                                    <button type="button" class="btn btn-ghost btn-sm comment-cancel-btn">Отмена</button>
                                </form>
                            </div>
                            <div class="comment-tools">
                                <button type="button" class="comment-edit-btn" title="Редактировать">✏️</button>
                                <button type="button" class="comment-delete-btn" title="Удалить">🗑️</button>
                            </div>
                        </div>
                    @empty
                        <p class="no-comments-hint">Комментариев пока нет — будьте первым.</p>
                    @endforelse
                </div>

                <div class="add-comment-row">
                    <button type="button" class="emoji-pick">😊</button>
                    <input type="text" placeholder="Добавить комментарий...">
                    <button type="button" class="post-comment-btn" disabled>Опубликовать</button>
                </div>
            </article>
        @empty
            <div class="empty-state">
                <div class="empty-emoji">📝</div>
                <h3>Пока нет публикаций</h3>
                <p>Станьте первым — поделитесь фото прямо сейчас.</p>
            </div>
        @endforelse
        </div>
    </div>

    <aside class="feed-sidebar">
        <div class="mini-profile">
            <div class="avatar-circle">Р</div>
            <div>
                <div class="mini-profile-name">Рустам</div>
                <div class="mini-profile-sub">@rustam.dev</div>
            </div>
        </div>

        <div class="suggestions-card">
            <h4>Рекомендации для вас</h4>
            <div class="suggestion-row">
                <div class="avatar-circle">С</div>
                <div class="suggestion-info">
                    <div class="suggestion-name">sonya.photo</div>
                    <div class="suggestion-sub">Новый в Pulse</div>
                </div>
                <button type="button" onclick="this.textContent = this.textContent==='Подписаться' ? 'Вы подписаны' : 'Подписаться';">Подписаться</button>
            </div>
            <div class="suggestion-row">
                <div class="avatar-circle">П</div>
                <div class="suggestion-info">
                    <div class="suggestion-name">pavel.k</div>
                    <div class="suggestion-sub">Подписан на anna_sokolova</div>
                </div>
                <button type="button" onclick="this.textContent = this.textContent==='Подписаться' ? 'Вы подписаны' : 'Подписаться';">Подписаться</button>
            </div>
            <div class="suggestion-row">
                <div class="avatar-circle">К</div>
                <div class="suggestion-info">
                    <div class="suggestion-name">katya.travel</div>
                    <div class="suggestion-sub">Популярно у вас</div>
                </div>
                <button type="button" onclick="this.textContent = this.textContent==='Подписаться' ? 'Вы подписаны' : 'Подписаться';">Подписаться</button>
            </div>
        </div>
    </aside>
</main>

<button type="button" class="fab-add" data-open-modal="modal-new-post" title="Новая публикация">➕</button>

{{-- New post modal --}}
<div class="modal-overlay" id="modal-new-post">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Новая публикация</h3>
            <button type="button" class="modal-close" data-close-modal>✕</button>
        </div>
        <form id="form-new-post" action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="dropzone" id="composer-dropzone">
                    <div class="dz-icon">📷</div>
                    <div>Нажмите, чтобы выбрать фото или видео</div>
                </div>
                <input type="file" id="composer-file" name="image" accept="image/*" hidden>
                <div class="composer-preview" id="composer-preview"></div>
                <div class="field">
                    <input type="text" id="composer-title" name="title" placeholder=" ">
                    <label>Заголовок публикации</label>
                </div>
                <div class="field">
                    <textarea id="composer-caption" name="description" placeholder=" " rows="3"></textarea>
                    <label>Подпись к публикации</label>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-ghost" data-close-modal>Отмена</button>
                <button type="submit" class="btn btn-gradient">Поделиться</button>
            </div>
        </form>
    </div>
</div>

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

</body>
</html>
