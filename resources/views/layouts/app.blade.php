<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="icon"
        type="image/png"
        href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon"
        type="image/png"
        href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon"
        href="{{ asset('images/logo.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Jeunesse en Jésus')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/j2-theme.css') }}">
    @stack('styles')
</head>
<body>

<!-- TOPBAR sombre -->
<div class="j2-topbar">
    <div class="j2-topbar-inner">
        <a href="https://www.youtube.com/channel/UC9EJNZo1McDNuKlbdIuZRXg" target="_blank">
            ▶ Youtube
        </a>
        <span>
            📞 +228 93745959 / +228 99323206 
        </span>
    </div>
</div>

<!-- HEADER principal -->
<header class="j2-header">
    <div class="j2-header-inner">
        <!-- Logo -->
        <a href="https://jeunesseenjesus.org" class="j2-logo">
            <img
                src="{{ asset('images/logo.png') }}"
                alt="J²"
                class="j2-logo-img"
                onerror="this.style.display='none'">
            <span class="j2-logo-text">
                Jeunesse en Jésus
            </span>
        </a>

        <!-- Navigation -->
        <nav class="j2-nav">
            <ul class="j2-nav-list">
                <li>
                    <a href="https://jeunesseenjesus.org" class="j2-nav-link">
                        Accueil
                    </a>
                </li>
                <li>
                    <a href="{{ url('/camp') }}" class="j2-nav-link {{ request()->is('camp*') ? 'active' : '' }}">
                        Inscription CIVA
                    </a>
                </li>
                 <li>
                    <a href="{{ url('/inscrits') }}" class="j2-nav-link {{ request()->is('inscrits*') ? 'active' : '' }}">
                        Liste des inscrits
                    </a>
                </li>
                  <li>
                    <a href="{{ route('sponsoring.index') }}" class="j2-nav-link {{ request()->is('sponsoring*') ? 'active' : '' }}">
                        Sponsoring
                    </a>
                </li>
                <li>
                    <a href="{{ url('/projets') }}" class="j2-nav-link {{ request()->is('projets*') ? 'active' : '' }}">
                        Projets
                    </a>
                </li>
                <li>
                    <a href="{{ url('/partenaires') }}" class="j2-nav-link {{ request()->is('partenaires*') ? 'active' : '' }}">
                        Partenaires
                    </a>
                </li>
              
               
            </ul>

            <!-- Bouton hamburger mobile -->
            <button class="j2-hamburger" id="hamburger" aria-label="Menu">
                ☰
            </button>
        </nav>
    </div>
</header>

<!-- CONTENU PRINCIPAL -->
<main class="j2-main">
    @yield('content')
</main>

<!-- FOOTER -->
<footer class="j2-footer">
    <div class="j2-footer-inner">
        <div class="j2-footer-col">
            <h3 class="j2-footer-title">Jeunesse en Jésus</h3>
            <p>Doulassame, côté sud<br>Clôture université de Lomé</p>
            <p>📞 +228 93745959  /<br>+228 99323206 </p>
        </div>
        <div class="j2-footer-col">
            <h3 class="j2-footer-title">Liens rapides</h3>
            <ul class="j2-footer-links">
                <li>
                    <a href="{{ url('/camp') }}">Inscription Evénement</a>
                </li>
                <li>
                    <a href="{{ url('/projets') }}">Projets à financer</a>
                </li>
                <li>
                    <a href="{{ url('/partenaires') }}">Partenaires</a>
                </li>
                <li>
                    <a href="{{ url('/inscrits') }}">Liste des inscrits</a>
                </li>
                <li>
                    <a href="{{ route('sponsoring.index') }}">Sponsoring</a>
                </li>
            </ul>
        </div>
        <div class="j2-footer-col">
            <h3 class="j2-footer-title">Site officiel</h3>
            <p>Pour plus d'informations sur nos activités :</p>
            <a href="https://jeunesseenjesus.org" target="_blank" class="j2-btn-orange">
               Accueil
            </a>
        </div>
    </div>
    <div class="j2-footer-bottom">
        <p>© Copyright <strong>Jeunesse en Jésus 2026</strong>. All Rights Reserved</p>
    </div>
</footer>

<!-- CSS intégré -->
<style>
/* === RESET & BASE === */
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    color: #333333;
    background: #fff;
}

a {
    text-decoration: none;
    color: inherit;
}

/* === TOPBAR === */
.j2-topbar {
    background: #3D2B1F;
    color: #fff;
    font-size: 0.8rem;
    padding: 6px 0;
}

.j2-topbar-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}

.j2-topbar a {
    color: #fff;
    opacity: 0.9;
}

.j2-topbar a:hover {
    opacity: 1;
    color: #E8490F;
}

/* === HEADER === */
.j2-header {
    background: #fff;
    border-bottom: 2px solid #f0f0f0;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.j2-header-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 14px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.j2-logo {
    display: flex;
    align-items: center;
    gap: 10px;
}

.j2-logo-img {
    height: 45px;
    width: auto;
}

.j2-logo-text {
    font-size: 1.4rem;
    font-weight: 700;
    color: #333333;
    font-family: -apple-system,
        BlinkMacSystemFont,
        'Segoe UI', sans-serif;
}

/* === NAV === */
.j2-nav-list {
    display: flex;
    list-style: none;
    gap: 8px;
    align-items: center;
}

.j2-nav-link {
    padding: 8px 14px;
    font-size: 0.9rem;
    color: #555;
    border-radius: 4px;
    transition: all 0.2s;
    font-weight: 500;
}

.j2-nav-link:hover,
.j2-nav-link.active {
    color: #E8490F;
}

.j2-nav-link.active {
    border-bottom: 2px solid #E8490F;
}

/* === HAMBURGER === */
.j2-hamburger {
    display: none;
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #333;
}

/* === MAIN === */
.j2-main {
    min-height: 60vh;
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px;
}

/* === BOUTON ORANGE === */
.j2-btn-orange {
    display: inline-block;
    background: #E8490F;
    color: #fff !important;
    padding: 10px 22px;
    border-radius: 4px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: background 0.2s;
    border: none;
    cursor: pointer;
}

.j2-btn-orange:hover {
    background: #C73D0A;
}

/* === FOOTER === */
.j2-footer {
    background: #F9F3F0;
    padding: 50px 0 0;
    margin-top: 60px;
    border-top: 3px solid #E8490F;
}

.j2-footer-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 40px;
}

.j2-footer-title {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 16px;
    color: #333;
}

.j2-footer p {
    color: #666;
    line-height: 1.7;
    font-size: 0.9rem;
    margin-bottom: 8px;
}

.j2-footer-links {
    list-style: none;
}

.j2-footer-links li {
    margin-bottom: 8px;
}

.j2-footer-links a {
    color: #666;
    font-size: 0.9rem;
    transition: color 0.2s;
}

.j2-footer-links a::before {
    content: '›  ';
    color: #E8490F;
}

.j2-footer-links a:hover {
    color: #E8490F;
}

.j2-footer-bottom {
    margin-top: 40px;
    padding: 20px;
    border-top: 1px solid #E5E5E5;
    text-align: center;
    color: #888;
    font-size: 0.85rem;
}

/* === MOBILE === */
@media (max-width: 768px) {
    .j2-nav-list {
        display: none !important;
        flex-direction: column !important;
        position: absolute !important;
        top: 100% !important;
        left: 0 !important;
        right: 0 !important;
        background: #ffffff !important;
        padding: 16px 20px !important;
        border-top: 2px solid #E8490F !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.12) !important;
        z-index: 999 !important;
        gap: 4px !important;
    }

    .j2-nav-list.open {
        display: flex !important;
    }

    .j2-nav-link {
        padding: 10px 16px !important;
        border-radius: 4px !important;
        width: 100% !important;
        display: block !important;
    }

    .j2-nav-link:hover,
    .j2-nav-link.active {
        background: #FFF5F2 !important;
        color: #E8490F !important;
    }

    .j2-hamburger {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 40px !important;
        height: 40px !important;
        background: none !important;
        border: 1px solid #ddd !important;
        border-radius: 4px !important;
        font-size: 1.3rem !important;
        cursor: pointer !important;
        color: #E8490F !important;
    }

    .j2-header {
        position: sticky !important;
        top: 0 !important;
        z-index: 1000 !important;
        background: #fff !important;
        width: 100% !important;
    }

    .j2-footer-inner {
        grid-template-columns: 1fr !important;
    }

    .j2-topbar-inner {
        justify-content: center !important;
        text-align: center !important;
    }

    .j2-topbar {
        display: none !important;
    }

    .j2-main {
        padding: 24px 16px !important;
    }
}

/* === TABLET (same behavior as mobile) === */
@media (min-width: 769px) and (max-width: 1024px) {
    .j2-nav-list {
        display: none !important;
        flex-direction: column !important;
        position: absolute !important;
        top: 100% !important;
        left: 0 !important;
        right: 0 !important;
        background: #ffffff !important;
        padding: 16px 20px !important;
        border-top: 2px solid #E8490F !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.12) !important;
        z-index: 999 !important;
        gap: 4px !important;
    }

    .j2-nav-list.open {
        display: flex !important;
    }

    .j2-nav-link {
        padding: 10px 16px !important;
        border-radius: 4px !important;
        width: 100% !important;
        display: block !important;
    }

    .j2-nav-link:hover,
    .j2-nav-link.active {
        background: #FFF5F2 !important;
        color: #E8490F !important;
    }

    .j2-hamburger {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 40px !important;
        height: 40px !important;
        background: none !important;
        border: 1px solid #E8490F  !important;
        border-radius: 4px !important;
        font-size: 1.3rem !important;
        cursor: pointer !important;
        color: #333 !important;
    }

    .j2-header {
        position: sticky !important;
        top: 0 !important;
        z-index: 1000 !important;
        background: #fff !important;
        width: 100% !important;
    }

    .j2-footer-inner {
        grid-template-columns: 1fr !important;
    }

    .j2-topbar-inner {
        justify-content: center !important;
        text-align: center !important;
    }

    .j2-topbar {
        display: none !important;
    }

    .j2-main {
        padding: 24px 16px !important;
    }
}

/* === DESKTOP OVERRIDES === */
@media (min-width: 1025px) {
    .j2-nav-list {
        display: flex !important;
    }

    .j2-hamburger {
        display: none !important;
    }
}
</style>

<script>
document.getElementById('hamburger')
    ?.addEventListener('click', function() {
        document
            .querySelector('.j2-nav-list')
            ?.classList.toggle('open');
    });
</script>

@stack('scripts')
</body>
</html>
