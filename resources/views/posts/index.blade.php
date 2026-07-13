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
<body data-auth="{{ auth()->check() ? '1' : '0' }}">

<header class="site-header">
    <div class="container header-inner">
        <a href="/" class="logo">
            <span class="logo-mark">💫</span>
            <span class="logo-text">Pulse</span>
        </a>
        @include('partials.search-box')
        <nav class="main-nav">
            <a href="/" class="nav-link">Главная</a>
            <a href="/post" class="nav-link {{ $onlyFollowing ? '' : 'active' }}">Лента</a>
            @auth
                <a href="{{ route('post.followingFeed') }}" class="nav-link {{ $onlyFollowing ? 'active' : '' }}">Моя лента</a>
            @endauth
            <a href="/hello" class="nav-link">Профиль</a>
        </nav>
        <div class="header-icons">
            <button type="button" class="icon-btn" data-open-modal="modal-new-post" title="Новая публикация">➕</button>
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
        <div class="stories-row" id="stories-row">
            @auth
                <div class="story-item" id="story-self">
                    <div class="story-ring is-you {{ $ownStoryGroup ? 'has-story' : '' }}">
                        <div class="story-avatar">
                            @if(auth()->user()->avatar_url)
                                <img src="{{ auth()->user()->avatar_url }}" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                            @else
                                {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                            @endif
                        </div>
                        <button type="button" class="story-add-badge" id="story-add-badge" title="Добавить историю">➕</button>
                    </div>
                    <span class="story-name">Ваша история</span>
                </div>
            @endauth

            @foreach($otherStoryGroups as $group)
                <div class="story-item" data-user-id="{{ $group['user']->id }}">
                    <div class="story-ring {{ $group['allSeen'] ? 'seen' : '' }}">
                        <div class="story-avatar">
                            @if($group['user']->avatar_url)
                                <img src="{{ $group['user']->avatar_url }}" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                            @else
                                {{ mb_strtoupper(mb_substr($group['user']->name, 0, 1)) }}
                            @endif
                        </div>
                    </div>
                    <span class="story-name">{{ $group['user']->name }}</span>
                </div>
            @endforeach
        </div>

        <script id="stories-json" type="application/json">
            {!! json_encode([
                'own' => $ownStoryGroup ? [
                    'userId' => $ownStoryGroup['user']->id,
                    'name' => 'Ваша история',
                    'initial' => mb_strtoupper(mb_substr($ownStoryGroup['user']->name, 0, 1)),
                    'items' => $ownStoryGroup['items']->map(fn ($s) => [
                        'id' => $s->id,
                        'image' => $s->imageUrl(),
                        'createdAt' => $s->created_at->diffForHumans(),
                    ])->values(),
                ] : null,
                'others' => $otherStoryGroups->map(fn ($g) => [
                    'userId' => $g['user']->id,
                    'name' => $g['user']->name,
                    'initial' => mb_strtoupper(mb_substr($g['user']->name, 0, 1)),
                    'items' => $g['items']->map(fn ($s) => [
                        'id' => $s->id,
                        'image' => $s->imageUrl(),
                        'createdAt' => $s->created_at->diffForHumans(),
                    ])->values(),
                ])->values(),
            ]) !!}
        </script>

        @forelse($posts as $post)
            <article class="post-card" id="post-{{ $post->id }}" data-post-id="{{ $post->id }}" style="--delay: {{ min($loop->index * 0.05, 0.4) }}s">
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
                        <div class="post-time"><a href="{{ route('posts.show', $post) }}">{{ $post->created_at->diffForHumans() }}</a></div>
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
                @if($onlyFollowing)
                    <h3>В вашей ленте пока пусто</h3>
                    <p>Подпишитесь на кого-нибудь, чтобы видеть их публикации здесь.</p>
                @else
                    <h3>Пока нет публикаций</h3>
                    <p>Станьте первым — поделитесь фото прямо сейчас.</p>
                @endif
            </div>
        @endforelse
        </div>
    </div>

    <aside class="feed-sidebar">
        @auth
            <a href="{{ route('profile.show') }}" class="mini-profile">
                <div class="avatar-circle">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</div>
                <div>
                    <div class="mini-profile-name">{{ auth()->user()->name }}</div>
                    <div class="mini-profile-sub">{{ auth()->user()->email }}</div>
                </div>
            </a>
        @endauth

        @if($suggestedUsers->isNotEmpty())
            <div class="suggestions-card">
                <h4>Рекомендации для вас</h4>
                @foreach($suggestedUsers as $suggested)
                    <div class="suggestion-row">
                        <a href="{{ route('users.show', $suggested) }}" class="avatar-circle">{{ mb_strtoupper(mb_substr($suggested->name, 0, 1)) }}</a>
                        <div class="suggestion-info">
                            <div class="suggestion-name"><a href="{{ route('users.show', $suggested) }}">{{ $suggested->name }}</a></div>
                            <div class="suggestion-sub">Новый в Pulse</div>
                        </div>
                        <button type="button" class="follow-btn {{ in_array($suggested->id, $followingIds) ? 'is-following' : '' }}" data-user-id="{{ $suggested->id }}">{{ in_array($suggested->id, $followingIds) ? 'Вы подписаны' : 'Подписаться' }}</button>
                    </div>
                @endforeach
            </div>
        @endif
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
                <div class="field">
                    <select id="composer-category" name="category_id">
                        <option value="">Без категории</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <label>Категория</label>
                </div>
                <div class="field">
                    <input type="text" id="composer-tags" name="tags" placeholder=" ">
                    <label>Теги через запятую</label>
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

{{-- New story modal --}}
<div class="modal-overlay" id="modal-new-story">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Новая история</h3>
            <button type="button" class="modal-close" data-close-modal>✕</button>
        </div>
        <form id="form-new-story" action="{{ route('stories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="dropzone" id="story-dropzone">
                    <div class="dz-icon">📷</div>
                    <div>Нажмите, чтобы выбрать фото для истории</div>
                </div>
                <input type="file" id="story-file" name="image" accept="image/*" hidden>
                <div class="composer-preview" id="story-preview"></div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-ghost" data-close-modal>Отмена</button>
                <button type="submit" class="btn btn-gradient">Опубликовать</button>
            </div>
        </form>
    </div>
</div>

{{-- Story viewer (Instagram-style full-screen) --}}
<div class="story-viewer" id="story-viewer">
    <div class="story-viewer-inner">
        <div class="story-progress-row" id="story-progress-row"></div>
        <div class="story-viewer-head">
            <div class="story-viewer-user">
                <span class="avatar-circle" id="story-viewer-avatar"></span>
                <span id="story-viewer-username"></span>
                <span class="story-viewer-time" id="story-viewer-time"></span>
            </div>
            <button type="button" class="modal-close" id="story-viewer-close">✕</button>
        </div>
        <div class="story-viewer-media">
            <button type="button" class="story-nav story-nav-prev" id="story-nav-prev" aria-label="Предыдущая">‹</button>
            <img id="story-viewer-img" src="" alt="">
            <button type="button" class="story-nav story-nav-next" id="story-nav-next" aria-label="Следующая">›</button>
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
