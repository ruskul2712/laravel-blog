/* =========================================================
   Pulse — shared front-end behaviour.
   Posts, likes, bookmarks, reposts and comments are persisted
   through the Laravel backend (see routes/web.php); everything
   else here (theme, modals, composer preview, ...) stays client-side.
   ========================================================= */
(function () {
    'use strict';

    const MOODS = ['🌄', '🍕', '🐶', '🏔️', '🎨', '🌊', '🍜', '🐱', '🌸', '🚀', '🎧', '🏖️'];
    let pendingConfirm = null;

    document.addEventListener('DOMContentLoaded', () => {
        initThemeToggle();
        initFloatingLabels();
        initModalSystem();
        initAuthTabs();
        initPostMenus();
        initLikes();
        initBookmarks();
        initReposts();
        initComments();
        initComposer();
        initProfileTabs();
        initProfileGrid();
        initVerifiedBadge();
        initStatCounters();
        initConfirmModal();
        initAvatarPreview();
    });

    /* ---------- Backend API helper ---------- */
    function csrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.content : '';
    }

    function apiRequest(url, method, data) {
        return fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-CSRF-TOKEN': csrfToken(),
            },
            body: data === undefined ? undefined : JSON.stringify(data),
        }).then(async (res) => {
            if (!res.ok) {
                let message = 'Что-то пошло не так, попробуйте ещё раз.';
                try {
                    const payload = await res.json();
                    message = payload.message || message;
                } catch (err) { /* response had no JSON body */ }
                throw new Error(message);
            }
            return res.status === 204 ? null : res.json();
        });
    }

    /* ---------- Theme ---------- */
    function initThemeToggle() {
        document.querySelectorAll('.theme-toggle').forEach((btn) => {
            btn.addEventListener('click', () => {
                const root = document.documentElement;
                const current = root.getAttribute('data-theme') === 'light' ? 'light' : 'dark';
                const next = current === 'light' ? 'dark' : 'light';
                root.setAttribute('data-theme', next);
                localStorage.setItem('pulse-theme', next);
            });
        });
    }

    /* ---------- Floating labels for textareas (placeholder-shown fallback) ---------- */
    function initFloatingLabels() {
        document.querySelectorAll('.field textarea').forEach((el) => {
            const sync = () => el.classList.toggle('has-value', el.value.trim().length > 0);
            el.addEventListener('input', sync);
            sync();
        });
    }

    /* ---------- Generic modal open/close ---------- */
    function initModalSystem() {
        document.addEventListener('click', (e) => {
            const opener = e.target.closest('[data-open-modal]');
            if (opener) {
                openModal(opener.getAttribute('data-open-modal'));
            }

            const closer = e.target.closest('[data-close-modal]');
            if (closer) {
                closeModal(closer.closest('.modal-overlay'));
            }

            if (e.target.classList.contains('modal-overlay')) {
                closeModal(e.target);
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-overlay.open').forEach(closeModal);
            }
        });
    }

    function openModal(id) {
        const el = document.getElementById(id);
        if (el) el.classList.add('open');
    }

    function closeModal(overlay) {
        if (overlay) overlay.classList.remove('open');
    }

    /* ---------- Confirm modal (delete post / comment / grid tile) ---------- */
    function initConfirmModal() {
        const confirmBtn = document.getElementById('confirm-action-btn');
        if (!confirmBtn) return;
        confirmBtn.addEventListener('click', () => {
            if (typeof pendingConfirm === 'function') pendingConfirm();
            pendingConfirm = null;
            closeModal(document.getElementById('modal-confirm'));
        });
    }

    function askConfirm(message, onConfirm) {
        const modal = document.getElementById('modal-confirm');
        if (!modal) {
            onConfirm();
            return;
        }
        modal.querySelector('.confirm-message').textContent = message;
        pendingConfirm = onConfirm;
        openModal('modal-confirm');
    }

    /* ---------- Auth tabs (landing page) ---------- */
    function initAuthTabs() {
        const tabs = document.querySelectorAll('.auth-tab');
        if (!tabs.length) return;
        const indicator = document.querySelector('.auth-tab-indicator');

        tabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                tabs.forEach((t) => t.classList.remove('active'));
                tab.classList.add('active');

                document.querySelectorAll('.auth-form').forEach((f) => f.classList.remove('active'));
                const target = document.getElementById(tab.dataset.target);
                if (target) target.classList.add('active');

                if (indicator) {
                    indicator.classList.toggle('to-register', tab.dataset.target === 'form-register');
                }
            });
        });

        document.querySelectorAll('[data-switch-tab]').forEach((link) => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const tab = document.querySelector(`.auth-tab[data-target="${link.dataset.switchTab}"]`);
                if (tab) tab.click();
            });
        });

        document.querySelectorAll('.auth-form').forEach((form) => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                showToast('Это демо-форма — бэкенда пока нет 🙂');
            });
        });
    }

    /* ---------- Post "..." menu (edit/delete) ---------- */
    function initPostMenus() {
        document.addEventListener('click', (e) => {
            const trigger = e.target.closest('.post-menu-trigger');
            if (trigger) {
                const dropdown = trigger.nextElementSibling;
                document.querySelectorAll('.post-menu-dropdown.open').forEach((d) => {
                    if (d !== dropdown) d.classList.remove('open');
                });
                dropdown.classList.toggle('open');
                return;
            }

            if (!e.target.closest('.post-menu-wrap')) {
                document.querySelectorAll('.post-menu-dropdown.open').forEach((d) => d.classList.remove('open'));
            }
        });

        document.addEventListener('click', (e) => {
            const editBtn = e.target.closest('.post-menu-edit');
            if (editBtn) {
                const card = editBtn.closest('.post-card');
                const titleInput = document.getElementById('edit-post-title');
                const textarea = document.getElementById('edit-post-caption');
                const form = document.getElementById('form-edit-post');
                titleInput.value = editBtn.dataset.title || '';
                textarea.value = editBtn.dataset.description || '';
                textarea.dispatchEvent(new Event('input'));
                form.dataset.postId = card.dataset.postId;
                openModal('modal-edit-post');
                editBtn.closest('.post-menu-dropdown').classList.remove('open');
            }

            const deleteBtn = e.target.closest('.post-menu-delete');
            if (deleteBtn) {
                const card = deleteBtn.closest('.post-card');
                deleteBtn.closest('.post-menu-dropdown').classList.remove('open');
                askConfirm('Удалить эту публикацию? Это действие нельзя отменить.', () => {
                    apiRequest(`/posts/${card.dataset.postId}`, 'DELETE')
                        .then(() => {
                            card.style.transition = 'opacity .25s ease, transform .25s ease';
                            card.style.opacity = '0';
                            card.style.transform = 'scale(.96)';
                            setTimeout(() => card.remove(), 250);
                            showToast('Публикация удалена 🗑️');
                        })
                        .catch((err) => showToast(err.message));
                });
            }
        });

        const editForm = document.getElementById('form-edit-post');
        if (editForm) {
            editForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const postId = editForm.dataset.postId;
                const card = document.querySelector(`.post-card[data-post-id="${postId}"]`);
                const title = document.getElementById('edit-post-title').value.trim();
                const description = document.getElementById('edit-post-caption').value.trim();

                apiRequest(`/posts/${postId}`, 'PUT', { title, description })
                    .then(({ post }) => {
                        if (card) {
                            const titleEl = card.querySelector('.cap-title');
                            const captionEl = card.querySelector('.cap-text');
                            if (titleEl) titleEl.textContent = post.title;
                            if (captionEl) {
                                captionEl.lastChild.textContent = ' ' + post.description;
                            }
                            const editBtn = card.querySelector('.post-menu-edit');
                            if (editBtn) {
                                editBtn.dataset.title = post.title;
                                editBtn.dataset.description = post.description;
                            }
                        }
                        closeModal(document.getElementById('modal-edit-post'));
                        showToast('Публикация обновлена ✏️');
                    })
                    .catch((err) => showToast(err.message));
            });
        }
    }

    /* ---------- Likes ---------- */
    function initLikes() {
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('.like-btn');
            if (btn) toggleLike(btn);
        });

        document.addEventListener('dblclick', (e) => {
            const media = e.target.closest('.post-media');
            if (!media) return;
            const card = media.closest('.post-card');
            const likeBtn = card.querySelector('.like-btn');
            if (likeBtn && !likeBtn.classList.contains('liked')) {
                toggleLike(likeBtn);
            }
            burstHeart(media);
        });
    }

    function toggleLike(btn) {
        if (btn.disabled) return;
        const card = btn.closest('.post-card');
        const countEl = card.querySelector('.post-likes .like-count');

        btn.disabled = true;
        apiRequest(`/posts/${card.dataset.postId}/like`, 'POST')
            .then(({ active, count }) => {
                btn.classList.toggle('liked', active);
                btn.textContent = active ? '❤️' : '🤍';
                countEl.textContent = count;
            })
            .catch((err) => showToast(err.message))
            .finally(() => { btn.disabled = false; });
    }

    function burstHeart(media) {
        let heart = media.querySelector('.heart-burst');
        if (!heart) {
            heart = document.createElement('div');
            heart.className = 'heart-burst';
            heart.textContent = '❤️';
            media.appendChild(heart);
        }
        heart.classList.remove('animate');
        void heart.offsetWidth;
        heart.classList.add('animate');
    }

    /* ---------- Bookmarks (save) ---------- */
    function initBookmarks() {
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('.bookmark-btn');
            if (!btn || btn.disabled) return;
            const card = btn.closest('.post-card');

            btn.disabled = true;
            apiRequest(`/posts/${card.dataset.postId}/bookmark`, 'POST')
                .then(({ active }) => {
                    btn.classList.toggle('saved', active);
                    btn.textContent = active ? '📌' : '🔖';
                    showToast(active ? 'Публикация сохранена 🔖' : 'Убрано из сохранённого');
                })
                .catch((err) => showToast(err.message))
                .finally(() => { btn.disabled = false; });
        });
    }

    /* ---------- Reposts (share) ---------- */
    function initReposts() {
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('.repost-btn');
            if (!btn || btn.disabled) return;
            const card = btn.closest('.post-card');
            const countEl = card.querySelector('.post-likes .repost-count');

            btn.disabled = true;
            apiRequest(`/posts/${card.dataset.postId}/repost`, 'POST')
                .then(({ active, count }) => {
                    btn.classList.toggle('reposted', active);
                    if (countEl) countEl.textContent = count;
                    showToast(active ? 'Публикация репостнута 📤' : 'Репост отменён');
                })
                .catch((err) => showToast(err.message))
                .finally(() => { btn.disabled = false; });
        });
    }

    /* ---------- Comments: add / edit / delete ---------- */
    function initComments() {
        document.addEventListener('input', (e) => {
            if (e.target.matches('.add-comment-row input')) {
                const btn = e.target.closest('.add-comment-row').querySelector('.post-comment-btn');
                btn.disabled = e.target.value.trim().length === 0;
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.target.matches('.add-comment-row input') && e.key === 'Enter') {
                e.preventDefault();
                const row = e.target.closest('.add-comment-row');
                const btn = row.querySelector('.post-comment-btn');
                if (!btn.disabled) submitComment(row);
            }
        });

        document.addEventListener('click', (e) => {
            const postBtn = e.target.closest('.post-comment-btn');
            if (postBtn && !postBtn.disabled) {
                submitComment(postBtn.closest('.add-comment-row'));
            }

            const focusBtn = e.target.closest('.comment-focus-btn');
            if (focusBtn) {
                const input = focusBtn.closest('.post-card').querySelector('.add-comment-row input');
                if (input) {
                    input.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    input.focus();
                }
            }

            const emojiBtn = e.target.closest('.emoji-pick');
            if (emojiBtn) {
                const input = emojiBtn.closest('.add-comment-row').querySelector('input');
                input.value += (input.value ? ' ' : '') + '😊';
                input.dispatchEvent(new Event('input'));
                input.focus();
            }

            const editBtn = e.target.closest('.comment-edit-btn');
            if (editBtn) {
                const row = editBtn.closest('.comment-row');
                row.classList.add('editing');
                const input = row.querySelector('.comment-edit-form input');
                input.value = row.querySelector('.c-text').textContent.trim();
                input.focus();
            }

            const cancelBtn = e.target.closest('.comment-cancel-btn');
            if (cancelBtn) {
                cancelBtn.closest('.comment-row').classList.remove('editing');
            }

            const saveBtn = e.target.closest('.comment-save-btn');
            if (saveBtn) {
                const row = saveBtn.closest('.comment-row');
                const input = row.querySelector('.comment-edit-form input');
                const value = input.value.trim();
                if (value) {
                    apiRequest(`/comments/${row.dataset.commentId}`, 'PUT', { body: value })
                        .then(({ comment }) => {
                            row.querySelector('.c-text').textContent = comment.body;
                            row.classList.remove('editing');
                        })
                        .catch((err) => showToast(err.message));
                } else {
                    row.classList.remove('editing');
                }
            }

            const deleteBtn = e.target.closest('.comment-delete-btn');
            if (deleteBtn) {
                const row = deleteBtn.closest('.comment-row');
                const list = row.closest('.post-comments');
                askConfirm('Удалить этот комментарий?', () => {
                    apiRequest(`/comments/${row.dataset.commentId}`, 'DELETE')
                        .then(() => {
                            row.classList.add('removing');
                            setTimeout(() => {
                                row.remove();
                                refreshEmptyCommentHint(list);
                            }, 220);
                        })
                        .catch((err) => showToast(err.message));
                });
            }
        });

        document.addEventListener('submit', (e) => {
            if (e.target.classList.contains('comment-edit-form')) {
                e.preventDefault();
                e.target.querySelector('.comment-save-btn').click();
            }
        });
    }

    function submitComment(row) {
        const input = row.querySelector('input');
        const text = input.value.trim();
        if (!text) return;

        const card = row.closest('.post-card');
        const postBtn = row.querySelector('.post-comment-btn');
        input.disabled = true;
        postBtn.disabled = true;

        apiRequest(`/posts/${card.dataset.postId}/comments`, 'POST', { body: text })
            .then(({ comment }) => {
                const list = card.querySelector('.post-comments');
                const hint = list.querySelector('.no-comments-hint');
                if (hint) hint.remove();

                const row = document.createElement('div');
                row.className = 'comment-row';
                row.dataset.commentId = comment.id;
                row.innerHTML = `
                    <div class="avatar-circle">${escapeHtml(comment.initial)}</div>
                    <div class="comment-body">
                        <div class="comment-text-line">
                            <span class="c-username">${escapeHtml(comment.username)}</span><span class="c-text">${escapeHtml(comment.body)}</span>
                            <div class="comment-time">${escapeHtml(comment.created_at)}</div>
                        </div>
                        <form class="comment-edit-form">
                            <input type="text" value="${escapeHtml(comment.body)}" placeholder=" ">
                            <button type="button" class="btn btn-primary btn-sm comment-save-btn">Сохранить</button>
                            <button type="button" class="btn btn-ghost btn-sm comment-cancel-btn">Отмена</button>
                        </form>
                    </div>
                    <div class="comment-tools">
                        <button type="button" class="comment-edit-btn" title="Редактировать">✏️</button>
                        <button type="button" class="comment-delete-btn" title="Удалить">🗑️</button>
                    </div>
                `;
                list.appendChild(row);

                input.value = '';
            })
            .catch((err) => showToast(err.message))
            .finally(() => {
                input.disabled = false;
                postBtn.disabled = input.value.trim().length === 0;
            });
    }

    function refreshEmptyCommentHint(list) {
        if (!list.querySelector('.comment-row')) {
            const hint = document.createElement('p');
            hint.className = 'no-comments-hint';
            hint.textContent = 'Комментариев пока нет — будьте первым.';
            list.appendChild(hint);
        }
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    /* ---------- New post composer ---------- */
    function initComposer() {
        const fileInput = document.getElementById('composer-file');
        const dropzone = document.getElementById('composer-dropzone');
        const preview = document.getElementById('composer-preview');
        const form = document.getElementById('form-new-post');
        if (!form) return;

        let currentType = 'image';
        let currentUrl = null;
        let currentEmoji = MOODS[Math.floor(Math.random() * MOODS.length)];

        dropzone.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', () => {
            const file = fileInput.files[0];
            if (!file) return;
            currentType = file.type.startsWith('video') ? 'video' : 'image';
            currentUrl = URL.createObjectURL(file);
            renderPreview();
        });

        preview.addEventListener('click', (e) => {
            if (e.target.closest('.remove-preview')) {
                clearPreview();
            }
        });

        function renderPreview() {
            preview.innerHTML = '';
            const media = document.createElement(currentType === 'video' ? 'video' : 'img');
            media.src = currentUrl;
            if (currentType === 'video') {
                media.controls = true;
                media.muted = true;
            }
            preview.appendChild(media);
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'remove-preview';
            removeBtn.innerHTML = '✕';
            preview.appendChild(removeBtn);
            preview.classList.add('show');
            dropzone.style.display = 'none';
        }

        function clearPreview() {
            preview.classList.remove('show');
            preview.innerHTML = '';
            dropzone.style.display = '';
            fileInput.value = '';
            currentUrl = null;
        }

        // Form posts for real to Laravel (see action/method/@csrf on #form-new-post),
        // so the browser just submits normally and the page reloads with the new post.
    }

    /* ---------- Profile tabs ---------- */
    function initProfileTabs() {
        const tabs = document.querySelectorAll('.profile-tab');
        if (!tabs.length) return;
        tabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                tabs.forEach((t) => t.classList.remove('active'));
                tab.classList.add('active');
                document.querySelectorAll('.profile-tab-panel').forEach((p) => p.classList.remove('active'));
                const panel = document.getElementById(tab.dataset.target);
                if (panel) panel.classList.add('active');
            });
        });
    }

    /* ---------- Profile grid delete ---------- */
    function initProfileGrid() {
        document.addEventListener('click', (e) => {
            const del = e.target.closest('.grid-tile-delete');
            if (del) {
                e.stopPropagation();
                const tile = del.closest('.grid-tile');
                askConfirm('Удалить эту публикацию из профиля?', () => {
                    tile.style.transition = 'opacity .2s ease, transform .2s ease';
                    tile.style.opacity = '0';
                    tile.style.transform = 'scale(.9)';
                    setTimeout(() => {
                        tile.remove();
                        bumpStat('postsCount', -1);
                    }, 200);
                });
                return;
            }

            const edit = e.target.closest('.grid-tile-edit');
            if (edit) {
                e.stopPropagation();
                showToast('Редактирование подписи доступно из ленты публикаций ✏️');
            }
        });
    }

    function bumpStat(id, delta) {
        const el = document.getElementById(id);
        if (!el) return;
        const value = Math.max(0, (parseInt(el.textContent, 10) || 0) + delta);
        el.textContent = value;
    }

    /* ---------- Profile settings: avatar upload preview ---------- */
    function initAvatarPreview() {
        const input = document.getElementById('avatar-input');
        if (!input) return;

        input.addEventListener('change', () => {
            const file = input.files[0];
            if (!file) return;

            const img = document.getElementById('avatar-preview-img');
            const letter = document.getElementById('avatar-preview-letter');
            img.src = URL.createObjectURL(file);
            img.style.display = 'block';
            if (letter) letter.style.display = 'none';
        });
    }

    /* ---------- Verified badge easter egg ---------- */
    function initVerifiedBadge() {
        document.querySelectorAll('.verified-badge').forEach((badge) => {
            badge.addEventListener('click', () => {
                burstConfetti();
                showToast('Аккаунт подтверждён ✅ (ну, почти)');
            });
        });
    }

    function burstConfetti() {
        const colors = ['#405de6', '#833ab4', '#e1306c', '#fd1d1d', '#fcaf45'];
        for (let i = 0; i < 26; i++) {
            const piece = document.createElement('span');
            piece.className = 'confetti-piece';
            piece.style.left = Math.random() * 100 + 'vw';
            piece.style.background = colors[Math.floor(Math.random() * colors.length)];
            piece.style.animationDuration = (2 + Math.random() * 1.5) + 's';
            document.body.appendChild(piece);
            setTimeout(() => piece.remove(), 3600);
        }
    }

    /* ---------- Animated stat counters ---------- */
    function initStatCounters() {
        const counters = document.querySelectorAll('.stat-number[data-target]');
        if (!counters.length) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                const el = entry.target;
                const target = parseInt(el.dataset.target, 10) || 0;
                const duration = 1100;
                const start = performance.now();

                function step(now) {
                    const progress = Math.min((now - start) / duration, 1);
                    el.textContent = Math.floor(progress * target).toLocaleString('ru-RU');
                    if (progress < 1) {
                        requestAnimationFrame(step);
                    } else {
                        el.textContent = target.toLocaleString('ru-RU');
                    }
                }

                requestAnimationFrame(step);
                observer.unobserve(el);
            });
        }, { threshold: 0.4 });

        counters.forEach((c) => observer.observe(c));
    }

    /* ---------- Toast ---------- */
    let toastTimer = null;
    function showToast(message) {
        const toast = document.getElementById('app-toast');
        if (!toast) return;
        toast.textContent = message;
        toast.classList.add('show');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => toast.classList.remove('show'), 2800);
    }
})();
