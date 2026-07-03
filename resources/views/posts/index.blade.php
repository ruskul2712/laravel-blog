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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages.css') }}">
    <title>Pulse — Лента</title>
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
            <a href="/my-name" class="nav-link">Профиль</a>
        </nav>
        <div class="header-icons">
            <button type="button" class="icon-btn" data-open-modal="modal-new-post" title="Новая публикация">➕</button>
            <button type="button" class="icon-btn" title="Уведомления">🔔</button>
            <button type="button" class="icon-btn theme-toggle" title="Сменить тему">
                <span class="icon-light">🌙</span><span class="icon-dark">☀️</span>
            </button>
            <a href="/my-name" class="avatar-btn" title="Профиль">Р</a>
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

        <div class="feed">
            {{-- Post 1 --}}
            <article class="post-card" id="post-1" style="--delay: 0s">
                <div class="post-card-head">
                    <div class="avatar-circle">Р</div>
                    <div class="post-head-meta">
                        <div class="post-username">Рустам <span class="verified-badge" title="Подтверждённый аккаунт">✅</span></div>
                        <div class="post-time">2 ч · Алматы</div>
                    </div>
                    <div class="post-menu-wrap">
                        <button type="button" class="icon-btn post-menu-trigger">⋯</button>
                        <div class="post-menu-dropdown">
                            <button type="button" class="post-menu-edit">✏️ Редактировать</button>
                            <button type="button" class="post-menu-delete danger-item">🗑️ Удалить</button>
                        </div>
                    </div>
                </div>
                <div class="post-media" style="background: linear-gradient(135deg,#1f2937,#405de6);">💻</div>
                <div class="post-actions">
                    <button type="button" class="icon-btn like-btn">🤍</button>
                    <button type="button" class="icon-btn comment-focus-btn">💬</button>
                    <button type="button" class="icon-btn">📤</button>
                    <span class="spacer"></span>
                    <button type="button" class="icon-btn bookmark-btn">🔖</button>
                </div>
                <div class="post-likes">Нравится: <span class="like-count" data-count="128">128</span></div>
                <div class="post-caption">
                    <span class="cap-username">Рустам</span><span class="cap-text">Ночной дедлайн, но кофе спасает ☕</span>
                </div>
                <div class="post-tags">#laravel #devlife #pulse</div>
                <div class="post-comments">
                    <div class="comment-row">
                        <div class="avatar-circle">А</div>
                        <div class="comment-body">
                            <div class="comment-text-line">
                                <span class="c-username">anna</span><span class="c-text">Красивый сетап!</span>
                                <div class="comment-time">1 ч</div>
                            </div>
                            <form class="comment-edit-form">
                                <input type="text" placeholder=" ">
                                <button type="button" class="btn btn-primary btn-sm comment-save-btn">Сохранить</button>
                                <button type="button" class="btn btn-ghost btn-sm comment-cancel-btn">Отмена</button>
                            </form>
                        </div>
                        <div class="comment-tools">
                            <button type="button" class="comment-delete-btn" title="Удалить">🗑️</button>
                        </div>
                    </div>
                    <div class="comment-row mine">
                        <div class="avatar-circle">Вы</div>
                        <div class="comment-body">
                            <div class="comment-text-line">
                                <span class="c-username">Вы</span><span class="c-text">Какая клавиатура, подскажи?</span>
                                <div class="comment-time">40 мин</div>
                            </div>
                            <form class="comment-edit-form">
                                <input type="text" placeholder=" ">
                                <button type="button" class="btn btn-primary btn-sm comment-save-btn">Сохранить</button>
                                <button type="button" class="btn btn-ghost btn-sm comment-cancel-btn">Отмена</button>
                            </form>
                        </div>
                        <div class="comment-tools">
                            <button type="button" class="comment-edit-btn" title="Редактировать">✏️</button>
                            <button type="button" class="comment-delete-btn" title="Удалить">🗑️</button>
                        </div>
                    </div>
                </div>
                <div class="add-comment-row">
                    <button type="button" class="emoji-pick">😊</button>
                    <input type="text" placeholder="Добавить комментарий...">
                    <button type="button" class="post-comment-btn" disabled>Опубликовать</button>
                </div>
            </article>

            {{-- Post 2 --}}
            <article class="post-card" id="post-2" style="--delay: .08s">
                <div class="post-card-head">
                    <div class="avatar-circle">А</div>
                    <div class="post-head-meta">
                        <div class="post-username">anna_sokolova</div>
                        <div class="post-time">5 ч</div>
                    </div>
                    <div class="post-menu-wrap">
                        <button type="button" class="icon-btn post-menu-trigger">⋯</button>
                        <div class="post-menu-dropdown">
                            <button type="button" class="post-menu-edit">✏️ Редактировать</button>
                            <button type="button" class="post-menu-delete danger-item">🗑️ Удалить</button>
                        </div>
                    </div>
                </div>
                <div class="post-media" style="background: linear-gradient(135deg,#fcaf45,#e1306c);">🌅</div>
                <div class="post-actions">
                    <button type="button" class="icon-btn like-btn liked">❤️</button>
                    <button type="button" class="icon-btn comment-focus-btn">💬</button>
                    <button type="button" class="icon-btn">📤</button>
                    <span class="spacer"></span>
                    <button type="button" class="icon-btn bookmark-btn">🔖</button>
                </div>
                <div class="post-likes">Нравится: <span class="like-count" data-count="342">342</span></div>
                <div class="post-caption">
                    <span class="cap-username">anna_sokolova</span><span class="cap-text">Рассвет на набережной сегодня был просто космос 🌇</span>
                </div>
                <div class="post-comments">
                    <div class="comment-row mine">
                        <div class="avatar-circle">Вы</div>
                        <div class="comment-body">
                            <div class="comment-text-line">
                                <span class="c-username">Вы</span><span class="c-text">Обалденное фото!</span>
                                <div class="comment-time">4 ч</div>
                            </div>
                            <form class="comment-edit-form">
                                <input type="text" placeholder=" ">
                                <button type="button" class="btn btn-primary btn-sm comment-save-btn">Сохранить</button>
                                <button type="button" class="btn btn-ghost btn-sm comment-cancel-btn">Отмена</button>
                            </form>
                        </div>
                        <div class="comment-tools">
                            <button type="button" class="comment-edit-btn" title="Редактировать">✏️</button>
                            <button type="button" class="comment-delete-btn" title="Удалить">🗑️</button>
                        </div>
                    </div>
                    <div class="comment-row">
                        <div class="avatar-circle">М</div>
                        <div class="comment-body">
                            <div class="comment-text-line">
                                <span class="c-username">marina</span><span class="c-text">Куда бежала в такую рань? 😄</span>
                                <div class="comment-time">3 ч</div>
                            </div>
                            <form class="comment-edit-form">
                                <input type="text" placeholder=" ">
                                <button type="button" class="btn btn-primary btn-sm comment-save-btn">Сохранить</button>
                                <button type="button" class="btn btn-ghost btn-sm comment-cancel-btn">Отмена</button>
                            </form>
                        </div>
                        <div class="comment-tools">
                            <button type="button" class="comment-delete-btn" title="Удалить">🗑️</button>
                        </div>
                    </div>
                </div>
                <div class="add-comment-row">
                    <button type="button" class="emoji-pick">😊</button>
                    <input type="text" placeholder="Добавить комментарий...">
                    <button type="button" class="post-comment-btn" disabled>Опубликовать</button>
                </div>
            </article>

            {{-- Post 3 — video --}}
            <article class="post-card" id="post-3" style="--delay: .16s">
                <div class="post-card-head">
                    <div class="avatar-circle">И</div>
                    <div class="post-head-meta">
                        <div class="post-username">igor.plays</div>
                        <div class="post-time">8 ч</div>
                    </div>
                    <div class="post-menu-wrap">
                        <button type="button" class="icon-btn post-menu-trigger">⋯</button>
                        <div class="post-menu-dropdown">
                            <button type="button" class="post-menu-edit">✏️ Редактировать</button>
                            <button type="button" class="post-menu-delete danger-item">🗑️ Удалить</button>
                        </div>
                    </div>
                </div>
                <div class="post-media" style="background: linear-gradient(135deg,#0f2027,#2c5364);">
                    🎮
                    <div class="play-overlay"><div class="play-btn">▶</div></div>
                    <span class="video-badge">Видео</span>
                </div>
                <div class="post-actions">
                    <button type="button" class="icon-btn like-btn">🤍</button>
                    <button type="button" class="icon-btn comment-focus-btn">💬</button>
                    <button type="button" class="icon-btn">📤</button>
                    <span class="spacer"></span>
                    <button type="button" class="icon-btn bookmark-btn">🔖</button>
                </div>
                <div class="post-likes">Нравится: <span class="like-count" data-count="89">89</span></div>
                <div class="post-caption">
                    <span class="cap-username">igor.plays</span><span class="cap-text">Затащили рейтинговую катку до 3 ночи 😅🎮</span>
                </div>
                <div class="post-comments">
                    <div class="comment-row">
                        <div class="avatar-circle">Д</div>
                        <div class="comment-body">
                            <div class="comment-text-line">
                                <span class="c-username">dima</span><span class="c-text">Легенда, го завтра ещё</span>
                                <div class="comment-time">6 ч</div>
                            </div>
                            <form class="comment-edit-form">
                                <input type="text" placeholder=" ">
                                <button type="button" class="btn btn-primary btn-sm comment-save-btn">Сохранить</button>
                                <button type="button" class="btn btn-ghost btn-sm comment-cancel-btn">Отмена</button>
                            </form>
                        </div>
                        <div class="comment-tools">
                            <button type="button" class="comment-delete-btn" title="Удалить">🗑️</button>
                        </div>
                    </div>
                </div>
                <div class="add-comment-row">
                    <button type="button" class="emoji-pick">😊</button>
                    <input type="text" placeholder="Добавить комментарий...">
                    <button type="button" class="post-comment-btn" disabled>Опубликовать</button>
                </div>
            </article>

            {{-- Post 4 — no comments yet --}}
            <article class="post-card" id="post-4" style="--delay: .24s">
                <div class="post-card-head">
                    <div class="avatar-circle">М</div>
                    <div class="post-head-meta">
                        <div class="post-username">marina.bakes</div>
                        <div class="post-time">1 д</div>
                    </div>
                    <div class="post-menu-wrap">
                        <button type="button" class="icon-btn post-menu-trigger">⋯</button>
                        <div class="post-menu-dropdown">
                            <button type="button" class="post-menu-edit">✏️ Редактировать</button>
                            <button type="button" class="post-menu-delete danger-item">🗑️ Удалить</button>
                        </div>
                    </div>
                </div>
                <div class="post-media" style="background: linear-gradient(135deg,#f7971e,#ffd200);">🍰</div>
                <div class="post-actions">
                    <button type="button" class="icon-btn like-btn">🤍</button>
                    <button type="button" class="icon-btn comment-focus-btn">💬</button>
                    <button type="button" class="icon-btn">📤</button>
                    <span class="spacer"></span>
                    <button type="button" class="icon-btn bookmark-btn">🔖</button>
                </div>
                <div class="post-likes">Нравится: <span class="like-count" data-count="210">210</span></div>
                <div class="post-caption">
                    <span class="cap-username">marina.bakes</span><span class="cap-text">Испекла шарлотку по бабушкиному рецепту 🍎🥧</span>
                </div>
                <div class="post-comments">
                    <p class="no-comments-hint">Комментариев пока нет — будьте первым.</p>
                </div>
                <div class="add-comment-row">
                    <button type="button" class="emoji-pick">😊</button>
                    <input type="text" placeholder="Добавить комментарий...">
                    <button type="button" class="post-comment-btn" disabled>Опубликовать</button>
                </div>
            </article>

            @if($posts->count())
                <div class="empty-state" style="margin-top: 8px;">
                    <div class="empty-emoji">📝</div>
                    <h3>{{ $posts->count() }} {{ $posts->count() == 1 ? 'запись' : 'записей' }} из старого блога</h3>
                    <p>Демо-лента выше показывает новый формат Pulse. Старые текстовые посты пока не перенесены в новый вид.</p>
                </div>
            @endif
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
        <form id="form-new-post">
            <div class="modal-body">
                <div class="dropzone" id="composer-dropzone">
                    <div class="dz-icon">📷</div>
                    <div>Нажмите, чтобы выбрать фото или видео</div>
                </div>
                <input type="file" id="composer-file" accept="image/*,video/*" hidden>
                <div class="composer-preview" id="composer-preview"></div>
                <div class="field">
                    <textarea id="composer-caption" placeholder=" " rows="3"></textarea>
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
