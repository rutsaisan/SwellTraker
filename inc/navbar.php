<?php
/* ─── Sesión y usuario ───────────────────────────────────── */
if (session_status() === PHP_SESSION_NONE) session_start();
$navUsuario = $_SESSION['nombre']    ?? null;
$navUserId  = $_SESSION['id']        ?? null;

/* ─── Detección de la página activa ─────────────────────── */
$current = basename($_SERVER['PHP_SELF'] ?? 'inicio.php');

$links = [
    ['href' => 'inicio.php',       'label' => 'Inicio',           'id' => 'inicio.php',       'svg' => '<circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>'],
    ['href' => 'olas.php',         'label' => 'Olas &amp; Spots', 'id' => 'olas.php',         'svg' => '<path d="M2 6c.6.5 1.2 1 2.5 1C7 7 7 5 9.5 5s2.5 2 5 2 2.5-2 5-2 2.5 2 5 2"/><path d="M2 12c.6.5 1.2 1 2.5 1C7 13 7 11 9.5 11s2.5 2 5 2 2.5-2 5-2 2.5 2 5 2"/><path d="M2 18c.6.5 1.2 1 2.5 1C7 19 7 17 9.5 17s2.5 2 5 2 2.5-2 5-2 2.5 2 5 2"/>'],
    ['href' => 'tutoriales.php',   'label' => 'Tutoriales',       'id' => 'tutoriales.php',   'svg' => '<circle cx="12" cy="12" r="10"/><polygon points="10,8 16,12 10,16"/>'],
    ['href' => 'equipamiento.php', 'label' => 'Material',         'id' => 'equipamiento.php', 'svg' => '<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>'],
    ['href' => 'Juego.php',        'label' => 'WavePilot &#127918;', 'id' => 'Juego.php',    'svg' => '<rect x="2" y="7" width="20" height="12" rx="3"/><path d="M9 11v4M7 13h4"/><circle cx="15" cy="12" r="1.2" fill="currentColor"/><circle cx="17" cy="14" r="1.2" fill="currentColor"/>'],
];
?>
<style>
/* ── NAVBAR COMPARTIDA ────────────────────────────────────── */
.sw-nav {
    position: sticky;
    top: 0;
    z-index: 200;
    background: rgba(0, 0, 0, 0.65);
    backdrop-filter: blur(22px);
    -webkit-backdrop-filter: blur(22px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}
.sw-nav-inner {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 2rem;
    height: 72px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}
/* Logo */
.sw-nav-logo {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    flex-shrink: 0;
}
.sw-nav-logo-img {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    overflow: hidden;
    border: 1px solid rgba(250, 204, 21, 0.35);
    box-shadow: 0 0 16px rgba(250, 204, 21, 0.08);
    flex-shrink: 0;
}
.sw-nav-logo-img img {
    width: 100%; height: 100%;
    object-fit: cover; display: block;
}
.sw-nav-brand {
    font-size: 1.4rem;
    font-weight: 700;
    color: #fff;
    letter-spacing: -0.03em;
    white-space: nowrap;
}
.sw-nav-brand span { color: #facc15; }
/* Links escritorio */
.sw-nav-links {
    display: flex;
    align-items: center;
    gap: 4px;
    flex: 1;
    justify-content: center;
}
.sw-nav-link {
    display: flex;
    align-items: center;
    gap: 7px;
    padding: 8px 15px;
    border-radius: 999px;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    color: rgba(255, 255, 255, 0.55);
    border: 1px solid transparent;
    transition: background 0.2s, color 0.2s;
    white-space: nowrap;
}
.sw-nav-link:hover {
    color: #fff;
    background: rgba(255, 255, 255, 0.07);
}
.sw-nav-link.sw-active {
    color: #facc15;
    background: rgba(59, 130, 246, 0.15);
    border-color: rgba(59, 130, 246, 0.3);
}
/* Juego siempre tiene acento cyan */
.sw-nav-link.sw-juego {
    color: #63FCFF;
    background: rgba(99, 252, 255, 0.08);
    border-color: rgba(99, 252, 255, 0.2);
    font-weight: 600;
}
.sw-nav-link.sw-juego.sw-active {
    background: rgba(99, 252, 255, 0.18);
    border-color: rgba(99, 252, 255, 0.4);
    color: #63FCFF;
}
.sw-nav-link svg {
    width: 16px; height: 16px; flex-shrink: 0;
}
/* Usuario área */
.sw-nav-user {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}
.sw-nav-username {
    font-size: 0.82rem;
    font-weight: 600;
    color: rgba(255,255,255,0.7);
    white-space: nowrap;
    max-width: 120px;
    overflow: hidden;
    text-overflow: ellipsis;
}
.sw-nav-logout {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 0.8rem;
    font-weight: 600;
    text-decoration: none;
    color: rgba(255,255,255,0.5);
    border: 1px solid rgba(255,255,255,0.12);
    transition: all 0.2s;
    cursor: pointer;
    background: none;
    font-family: inherit;
}
.sw-nav-logout:hover {
    color: #ef4444;
    border-color: rgba(239,68,68,0.4);
    background: rgba(239,68,68,0.08);
}
.sw-nav-logout svg { width: 13px; height: 13px; }
/* Hamburguesa móvil */
.sw-nav-hamburger {
    display: none;
    background: none;
    border: none;
    cursor: pointer;
    padding: 8px;
    color: #facc15;
}
.sw-nav-hamburger svg { width: 24px; height: 24px; display: block; }
/* Menú móvil desplegable */
.sw-nav-mobile {
    display: none;
    flex-direction: column;
    gap: 4px;
    padding: 12px 16px 16px;
    background: rgba(0, 0, 0, 0.92);
    border-top: 1px solid rgba(255,255,255,0.07);
}
.sw-nav-mobile.open { display: flex; }
.sw-nav-mobile .sw-nav-link {
    border-radius: 12px;
    padding: 10px 16px;
    width: 100%;
}
.sw-nav-mobile-user {
    padding: 10px 16px;
    color: rgba(255,255,255,0.4);
    font-size: 0.8rem;
    border-top: 1px solid rgba(255,255,255,0.06);
    margin-top: 4px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
/* Responsive */
@media (max-width: 900px) {
    .sw-nav-links   { display: none; }
    .sw-nav-hamburger { display: block; }
    .sw-nav-username { display: none; }
}
@media (max-width: 768px) {
    .sw-nav-brand   { font-size: 1.15rem; }
}
@media (max-width: 420px) {
    .sw-nav-inner   { padding: 0 1rem; }
    .sw-nav-brand   { display: none; }
}
</style>

<nav class="sw-nav" role="navigation" aria-label="Navegación principal">
    <div class="sw-nav-inner">
        <!-- Logo -->
        <a href="inicio.php" class="sw-nav-logo">
            <div class="sw-nav-logo-img">
                <img src="Logo_Surf.png" alt="SwellTracker Global" />
            </div>
            <span class="sw-nav-brand">Swell<span>Tracker</span></span>
        </a>

        <!-- Links escritorio -->
        <div class="sw-nav-links">
            <?php foreach ($links as $link):
                $isActive  = ($current === $link['id']);
                $isJuego   = ($link['id'] === 'Juego.php');
                $classes   = 'sw-nav-link';
                if ($isJuego)  $classes .= ' sw-juego';
                if ($isActive) $classes .= ' sw-active';
            ?>
            <a href="<?= $link['href'] ?>" class="<?= $classes ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <?= $link['svg'] ?>
                </svg>
                <?= $link['label'] ?>
            </a>
            <?php endforeach; ?>
        </div>

        <!-- Usuario + logout (escritorio) -->
        <div class="sw-nav-user">
            <?php if ($navUsuario): ?>
                <span class="sw-nav-username">👋 <?= htmlspecialchars($navUsuario) ?></span>
                <form method="POST" action="php/logout.php" style="margin:0">
                    <button type="submit" class="sw-nav-logout" title="Cerrar sesión">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        Salir
                    </button>
                </form>
            <?php else: ?>
                <a href="index.php" class="sw-nav-logout" style="color:rgba(255,255,255,0.6);border-color:rgba(255,255,255,0.15);">Entrar</a>
            <?php endif; ?>
        </div>

        <!-- Botón hamburguesa (móvil) -->
        <button class="sw-nav-hamburger" id="sw-hamburger" aria-label="Abrir menú"
                onclick="document.getElementById('sw-mobile-menu').classList.toggle('open');
                         this.setAttribute('aria-expanded',
                             document.getElementById('sw-mobile-menu').classList.contains('open'));">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="4" y1="6"  x2="20" y2="6"/>
                <line x1="4" y1="12" x2="20" y2="12"/>
                <line x1="4" y1="18" x2="20" y2="18"/>
            </svg>
        </button>
    </div>

    <!-- Menú móvil -->
    <div class="sw-nav-mobile" id="sw-mobile-menu" role="menu">
        <?php foreach ($links as $link):
            $isActive  = ($current === $link['id']);
            $isJuego   = ($link['id'] === 'Juego.php');
            $classes   = 'sw-nav-link';
            if ($isJuego)  $classes .= ' sw-juego';
            if ($isActive) $classes .= ' sw-active';
        ?>
        <a href="<?= $link['href'] ?>" class="<?= $classes ?>" role="menuitem">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <?= $link['svg'] ?>
            </svg>
            <?= $link['label'] ?>
        </a>
        <?php endforeach; ?>

        <!-- Usuario en móvil -->
        <?php if ($navUsuario): ?>
        <div class="sw-nav-mobile-user">
            <span>👋 <?= htmlspecialchars($navUsuario) ?></span>
            <form method="POST" action="php/logout.php" style="margin:0">
                <button type="submit" class="sw-nav-logout">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    Cerrar sesión
                </button>
            </form>
        </div>
        <?php endif; ?>
    </div>
</nav>
