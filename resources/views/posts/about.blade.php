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
    <title>Pulse — Профиль</title>
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
            <a href="/post" class="nav-link">Лента</a>
            <a href="/my-name" class="nav-link active">Профиль</a>
        </nav>
        <div class="header-icons">
            <button type="button" class="icon-btn" title="Настройки">⚙️</button>
            <button type="button" class="icon-btn theme-toggle" title="Сменить тему">
                <span class="icon-light">🌙</span><span class="icon-dark">☀️</span>
            </button>
            <a href="/my-name" class="avatar-btn" title="Профиль">Р</a>
        </div>
    </div>
</header>

<main class="profile-shell">
    <section class="profile-header">
        <div class="avatar-circle profile-avatar">
            👨‍💻
            <span class="profile-online-dot" title="В сети"></span>
        </div>

        <div class="profile-main">
            <div class="profile-name-row">
                <span class="profile-username">rustam.dev <span class="verified-badge" title="Подтверждённый аккаунт">✅</span></span>
                <button type="button" class="btn btn-ghost btn-sm">Редактировать профиль</button>
                <button type="button" class="btn btn-ghost btn-sm" title="Настройки">⚙️</button>
            </div>

            <div class="profile-stats">
                <span class="profile-stat"><b id="postsCount">6</b>публикаций</span>
                <span class="profile-stat"><b class="stat-number" data-target="1284">0</b>подписчиков</span>
                <span class="profile-stat"><b class="stat-number" data-target="312">0</b>подписок</span>
            </div>

            <div class="profile-fullname">Рустам</div>
            <p class="profile-bio">Backend-разработчик, люблю Laravel, чистый код и хороший кофе. ☕️📦
Делюсь моментами из жизни разработчика и не только.
<a href="mailto:{{ config('mail.from.address', 'nurcha777@gmail.com') }}">✉️ nurcha777@gmail.com</a></p>
        </div>
    </section>

    <nav class="profile-tabs">
        <button type="button" class="profile-tab active" data-target="tab-posts">📸 Публикации</button>
        <button type="button" class="profile-tab" data-target="tab-saved">🔖 Сохранённые</button>
        <button type="button" class="profile-tab" data-target="tab-tagged">🏷️ Отметки</button>
    </nav>

    <div class="profile-tab-panel active" id="tab-posts">
        <div class="post-grid">
            <div class="grid-tile" style="background: linear-gradient(135deg,#1f2937,#405de6);">
                💻
                <div class="grid-tile-tools">
                    <button type="button" class="grid-tile-edit" title="Редактировать">✏️</button>
                    <button type="button" class="grid-tile-delete" title="Удалить">🗑️</button>
                </div>
                <div class="grid-overlay">❤️ 128 &nbsp; 💬 2</div>
            </div>
            <div class="grid-tile" style="background: linear-gradient(135deg,#0f2027,#2c5364);">
                🎮
                <span class="grid-tile-video-badge">▶</span>
                <div class="grid-tile-tools">
                    <button type="button" class="grid-tile-edit" title="Редактировать">✏️</button>
                    <button type="button" class="grid-tile-delete" title="Удалить">🗑️</button>
                </div>
                <div class="grid-overlay">❤️ 89 &nbsp; 💬 1</div>
            </div>
            <div class="grid-tile" style="background: linear-gradient(135deg,#00b09b,#96c93d);">
                🏔️
                <div class="grid-tile-tools">
                    <button type="button" class="grid-tile-edit" title="Редактировать">✏️</button>
                    <button type="button" class="grid-tile-delete" title="Удалить">🗑️</button>
                </div>
                <div class="grid-overlay">❤️ 256 &nbsp; 💬 4</div>
            </div>
            <div class="grid-tile" style="background: linear-gradient(135deg,#7b4397,#dc2430);">
                🎨
                <div class="grid-tile-tools">
                    <button type="button" class="grid-tile-edit" title="Редактировать">✏️</button>
                    <button type="button" class="grid-tile-delete" title="Удалить">🗑️</button>
                </div>
                <div class="grid-overlay">❤️ 74 &nbsp; 💬 0</div>
            </div>
            <div class="grid-tile" style="background: linear-gradient(135deg,#f7971e,#ffd200);">
                🍰
                <div class="grid-tile-tools">
                    <button type="button" class="grid-tile-edit" title="Редактировать">✏️</button>
                    <button type="button" class="grid-tile-delete" title="Удалить">🗑️</button>
                </div>
                <div class="grid-overlay">❤️ 210 &nbsp; 💬 0</div>
            </div>
            <div class="grid-tile" style="background: linear-gradient(135deg,#4568dc,#b06ab3);">
                🌊
                <div class="grid-tile-tools">
                    <button type="button" class="grid-tile-edit" title="Редактировать">✏️</button>
                    <button type="button" class="grid-tile-delete" title="Удалить">🗑️</button>
                </div>
                <div class="grid-overlay">❤️ 163 &nbsp; 💬 3</div>
            </div>
        </div>
    </div>

    <div class="profile-tab-panel" id="tab-saved">
        <div class="empty-state">
            <div class="empty-emoji">🔖</div>
            <h3>Пока ничего не сохранено</h3>
            <p>Нажимайте на значок 🔖 под публикацией, чтобы сохранить её сюда.</p>
        </div>
    </div>

    <div class="profile-tab-panel" id="tab-tagged">
        <div class="empty-state">
            <div class="empty-emoji">🏷️</div>
            <h3>Отметок пока нет</h3>
            <p>Здесь появятся публикации, на которых вас отметили друзья.</p>
        </div>
    </div>
</main>

{{-- Confirm modal (reused for delete grid tile) --}}
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
