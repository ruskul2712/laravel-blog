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
    <title>Pulse — заходи и делись моментами</title>
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
            <a href="/" class="nav-link active">Главная</a>
            <a href="/post" class="nav-link">Лента</a>
            <a href="/hello" class="nav-link">Профиль</a>
        </nav>
        <div class="header-icons">
            <button type="button" class="icon-btn theme-toggle" title="Сменить тему">
                <span class="icon-light">🌙</span><span class="icon-dark">☀️</span>
            </button>
        </div>
    </div>
</header>

<main class="container landing-shell">
    <div class="landing-showcase">
        <div class="landing-glow"></div>

        <div class="mock-stack">
            <div class="mock-card card-a">
                <div class="mock-media" style="background: linear-gradient(135deg,#405de6,#833ab4);">🌄</div>
                <div class="mock-line w-80"></div>
                <div class="mock-line w-60"></div>
            </div>
            <div class="mock-card card-b">
                <div class="mock-media" style="background: linear-gradient(135deg,#e1306c,#fcaf45);">🍕</div>
                <div class="mock-line w-60"></div>
                <div class="mock-line w-80"></div>
            </div>
            <div class="mock-card card-c">
                <div class="mock-media" style="background: linear-gradient(135deg,#00b09b,#2196f3);">🐶</div>
                <div class="mock-line w-80"></div>
                <div class="mock-line w-60"></div>
            </div>
        </div>

        <div class="landing-tagline">
            <h1>Делись моментами в <span class="accent">Pulse</span></h1>
            <p>Публикуй фото и видео, читай ленту друзей, комментируй и находи новых людей — всё в одном месте.</p>
        </div>

        <div class="perk-row">
            <span class="perk-pill">📸 Фото и видео</span>
            <span class="perk-pill">💬 Комментарии</span>
            <span class="perk-pill">🌗 Тёмная / светлая тема</span>
            <span class="perk-pill">🔒 Приватность</span>
        </div>
    </div>

    <div class="auth-panel">
        <div class="auth-tabs">
            <button type="button" class="auth-tab active" data-target="form-login">Вход</button>
            <button type="button" class="auth-tab" data-target="form-register">Регистрация</button>
            <span class="auth-tab-indicator"></span>
        </div>

        <form id="form-login" class="auth-form active">
            <h2>С возвращением 👋</h2>
            <p class="form-sub">Войди, чтобы увидеть ленту друзей</p>

            <div class="field">
                <input type="email" placeholder=" " required>
                <label>Email</label>
            </div>
            <div class="field">
                <input type="password" placeholder=" " required>
                <label>Пароль</label>
            </div>

            <div class="auth-remember">
                <label><input type="checkbox"> Запомнить меня</label>
                <a href="#">Забыли пароль?</a>
            </div>

            <button type="submit" class="btn btn-gradient btn-block">Войти</button>
            <a href="/post" class="btn btn-ghost btn-block" style="margin-top: 10px;">Войти как гость</a>

            <p class="landing-footer-note">Нет аккаунта? <a href="#" data-switch-tab="form-register">Зарегистрироваться</a></p>
        </form>

        <form id="form-register" class="auth-form">
            <h2>Создать аккаунт ✨</h2>
            <p class="form-sub">Это займёт меньше минуты</p>

            <div class="field">
                <input type="text" placeholder=" " required>
                <label>Имя пользователя</label>
            </div>
            <div class="field">
                <input type="email" placeholder=" " required>
                <label>Email</label>
            </div>
            <div class="field">
                <input type="password" placeholder=" " required>
                <label>Пароль</label>
            </div>

            <button type="submit" class="btn btn-gradient btn-block">Зарегистрироваться</button>
            <a href="/post" class="btn btn-ghost btn-block" style="margin-top: 10px;">Войти как гость</a>

            <p class="landing-footer-note">Уже есть аккаунт? <a href="#" data-switch-tab="form-login">Войти</a></p>
        </form>
    </div>
</main>

<footer class="site-footer">
    <div class="container footer-inner">
        <p>&copy; {{ date('Y') }} Блог Кульжанова Рустама</p>

        <div class="footer-contacts">
            наш номер телефона
            <a href="tel:+77761550530" class="footer-contact">📞 +7 (777) 155-05-30</a>
            наша электронная почта
            <a href="ruskul2712@gmail.com" class="footer-contact">✉️ ruskul2712@gmail.com</a>
        </div>

        <div class="footer-social">
            <span class="footer-social-label">Мы в социальных сетях:</span>

            <a href="https://www.instagram.com/rustamkulzhanov/" class="footer-social-link" target="_blank" rel="noopener" title="Instagram">📷 Instagram</a>
        </div>
    </div>
</footer>

<div id="app-toast" class="toast"></div>

<script src="{{ asset('js/app.js') }}"></script>

</body>
</html>
