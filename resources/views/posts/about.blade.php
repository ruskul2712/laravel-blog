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
            <a href="/hello" class="nav-link active">Профиль</a>
        </nav>
        <div class="header-icons">
            <button type="button" class="icon-btn" title="Настройки" data-open-modal="modal-edit-profile">⚙️</button>
            <button type="button" class="icon-btn theme-toggle" title="Сменить тему">
                <span class="icon-light">🌙</span><span class="icon-dark">☀️</span>
            </button>
            <a href="/hello" class="avatar-btn" title="Профиль">{{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}</a>
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
            <span class="profile-online-dot" title="В сети"></span>
        </div>

        <div class="profile-main">
            <div class="profile-name-row">
                <span class="profile-username">{{ $user->name }}</span>
                <button type="button" class="btn btn-ghost btn-sm" data-open-modal="modal-edit-profile">✏️ Редактировать профиль</button>
                <button type="button" class="btn btn-ghost btn-sm" title="Настройки" data-open-modal="modal-edit-profile">⚙️</button>
            </div>

            <div class="profile-stats">
                <span class="profile-stat"><b id="postsCount">{{ $posts->count() }}</b>публикаций</span>
                <span class="profile-stat"><b class="stat-number" data-target="0">0</b>подписчиков</span>
                <span class="profile-stat"><b class="stat-number" data-target="0">0</b>подписок</span>
            </div>

            <p class="profile-bio">{{ $user->bio ?: 'Пока ничего не рассказал(а) о себе — самое время это исправить в настройках профиля.' }}
<a href="mailto:{{ $user->email }}">✉️ {{ $user->email }}</a></p>
        </div>
    </section>

    <nav class="profile-tabs">
        <button type="button" class="profile-tab active" data-target="tab-posts">📸 Публикации</button>
        <button type="button" class="profile-tab" data-target="tab-saved">🔖 Сохранённые</button>
        <button type="button" class="profile-tab" data-target="tab-tagged">🏷️ Отметки</button>
    </nav>

    <div class="profile-tab-panel active" id="tab-posts">
        @if($posts->isEmpty())
            <div class="empty-state">
                <div class="empty-emoji">📸</div>
                <h3>Пока нет публикаций</h3>
                <p>Опубликуйте что-нибудь в <a href="{{ route('post.feed') }}">ленте</a>, и это появится здесь.</p>
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
                        <div class="grid-tile-tools">
                            <a href="{{ route('post.feed') }}" class="grid-tile-edit" title="Редактировать в ленте">✏️</a>
                        </div>
                        <div class="grid-overlay">❤️ {{ $post->likes_count }} &nbsp; 💬 {{ $post->comments_count }}</div>
                    </div>
                @endforeach
            </div>
        @endif
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

{{-- Settings / edit profile modal --}}
<div class="modal-overlay @if($errors->any()) open @endif" id="modal-edit-profile">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Настройки профиля</h3>
            <button type="button" class="modal-close" data-close-modal>✕</button>
        </div>
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="avatar-edit-row">
                    <label for="avatar-input" class="avatar-circle profile-edit-avatar" title="Нажмите, чтобы выбрать фото">
                        <img id="avatar-preview-img" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:50%;{{ $user->avatar_url ? '' : 'display:none;' }}" @if($user->avatar_url) src="{{ $user->avatar_url }}" @endif>
                        <span id="avatar-preview-letter" style="{{ $user->avatar_url ? 'display:none;' : '' }}">{{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}</span>
                        <span class="avatar-edit-overlay">Изменить фото</span>
                    </label>
                    <input type="file" name="avatar" id="avatar-input" accept="image/png,image/jpeg,image/webp" hidden>
                </div>
                @error('avatar')
                    <p class="field-error" style="display:block;text-align:center;">{{ $message }}</p>
                @enderror

                <div class="field">
                    <input type="text" name="name" placeholder=" " value="{{ old('name', $user->name) }}" required maxlength="255">
                    <label>Имя</label>
                </div>
                @error('name')
                    <p class="field-error" style="display:block;">{{ $message }}</p>
                @enderror

                <div class="field">
                    <textarea name="bio" placeholder=" " rows="4" maxlength="500">{{ old('bio', $user->bio) }}</textarea>
                    <label>О себе</label>
                </div>
                @error('bio')
                    <p class="field-error" style="display:block;">{{ $message }}</p>
                @enderror
                <p class="field-hint">Эту фотографию, имя и описание увидят все, кто зайдёт в ваш профиль.</p>

                <div class="field" style="margin-top: 22px;">
                    <input type="email" value="{{ $user->email }}" disabled>
                    <label style="top:5px;font-size:.68rem;font-weight:700;">Email</label>
                </div>
                <p class="field-hint">Email пока нельзя изменить — эта настройка появится вместе со входом в аккаунт.</p>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-ghost" data-close-modal>Отмена</button>
                <button type="submit" class="btn btn-gradient">Сохранить</button>
            </div>
        </form>
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
