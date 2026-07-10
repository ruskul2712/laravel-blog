@auth
    <div class="notif-wrap">
        <button type="button" class="icon-btn notif-bell-btn" id="notif-bell-btn" title="Уведомления">
            🔔
            <span class="notif-badge" id="notif-badge" hidden>0</span>
        </button>
        <div class="notif-dropdown" id="notif-dropdown">
            <div class="notif-dropdown-head">
                <span>Уведомления</span>
                <button type="button" id="notif-mark-all-btn">Прочитать всё</button>
            </div>
            <div class="notif-list" id="notif-list">
                <div class="notif-empty">Загрузка…</div>
            </div>
        </div>
    </div>
@endauth
