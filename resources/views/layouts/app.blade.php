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
              
                <!-- Dropdown Compte Investisseur (UNIQUEMENT si connecté) -->
                @auth('investor')
                <li class="investor-dropdown">
                    <button class="investor-trigger" onclick="toggleInvestorMenu()" aria-label="Menu compte investisseur">
                        <div class="investor-avatar">
                            {{ strtoupper(substr(auth('investor')->user()->name ?? auth('investor')->user()->organization_name ?? 'I', 0, 1)) }}
                        </div>
                        <span class="investor-name">
                            {{ Str::limit(auth('investor')->user()->name ?? auth('investor')->user()->organization_name, 18) }}
                        </span>
                        <svg class="investor-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>

                    <div class="investor-menu" id="investorMenu">
                        <a href="{{ route('investor.dashboard') }}" class="investor-menu-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                            Mon tableau de bord
                        </a>

                        <form method="POST" action="{{ route('investor.logout') }}" class="investor-logout-form">
                            @csrf
                            <button type="submit" class="investor-menu-item investor-logout">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M10 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h4m10-4l4-4m0 0l-4-4m4 4H9"></path>
                                </svg>
                                Se déconnecter
                            </button>
                        </form>
                    </div>
                </li>
                @endauth
               
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

/* === INVESTOR DROPDOWN === */
.investor-dropdown {
    position: relative;
    margin-left: 1rem;
}
.investor-trigger {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: #fdf6f3;
    border: 1.5px solid #f0e8e4;
    padding: 0.4rem 0.9rem 0.4rem 0.4rem;
    border-radius: 25px;
    cursor: pointer;
    transition: border-color 0.2s, background 0.2s;
}
.investor-trigger:hover {
    border-color: #E8490F;
    background: #fff3ef;
}
.investor-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #E8490F;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 0.85rem;
    flex-shrink: 0;
}
.investor-name {
    font-size: 0.85rem;
    font-weight: 600;
    color: #3D2B1F;
}
.investor-chevron {
    width: 14px;
    height: 14px;
    color: #888;
    transition: transform 0.2s;
}
.investor-trigger.is-open .investor-chevron {
    transform: rotate(180deg);
}
.investor-menu {
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 8px 28px rgba(0,0,0,0.14);
    min-width: 200px;
    padding: 0.5rem;
    display: none;
    z-index: 100;
    border: 1px solid #f0e8e4;
}
.investor-menu.is-open {
    display: block;
}
.investor-menu-item {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    width: 100%;
    padding: 0.6rem 0.75rem;
    border-radius: 8px;
    font-size: 0.85rem;
    color: #3D2B1F;
    text-decoration: none;
    background: none;
    border: none;
    cursor: pointer;
    text-align: left;
    font-family: inherit;
    transition: background 0.15s;
}
.investor-menu-item:hover { background: #fdf6f3; }
.investor-menu-item svg { width: 17px; height: 17px; flex-shrink: 0; }
.investor-logout-form { margin: 0; }
.investor-logout { color: #e53e3e; }
.investor-logout:hover { background: #fff5f5; }

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

    .investor-dropdown { 
        margin-left: 0 !important; 
        margin-top: 0.75rem !important; 
        width: 100% !important; 
    }
    .investor-trigger { 
        width: 100% !important; 
        justify-content: center !important; 
    }
    .investor-name { 
        max-width: 140px !important; 
        overflow: hidden !important; 
        text-overflow: ellipsis !important; 
        white-space: nowrap !important; 
    }
    .investor-menu { 
        position: static !important; 
        margin-top: 0.5rem !important; 
        box-shadow: none !important; 
        border: 1px solid #f0e8e4 !important; 
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

// Investor dropdown toggle
function toggleInvestorMenu() {
    const trigger = document.querySelector('.investor-trigger');
    const menu = document.getElementById('investorMenu');
    if (!trigger || !menu) return;
    trigger.classList.toggle('is-open');
    menu.classList.toggle('is-open');
}
document.addEventListener('click', function (e) {
    const dropdown = document.querySelector('.investor-dropdown');
    if (dropdown && !dropdown.contains(e.target)) {
        document.querySelector('.investor-trigger')?.classList.remove('is-open');
        document.getElementById('investorMenu')?.classList.remove('is-open');
    }
});
</script>

@if(config('services.recaptcha.site_key'))
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('form[data-recaptcha]');
    forms.forEach(function (form) {
        form.addEventListener('submit', function (e) {
            const tokenField = form.querySelector('#g-recaptcha-response');
            if (tokenField && !tokenField.value) {
                e.preventDefault();
                grecaptcha.ready(function () {
                    grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', { action: 'submit' })
                        .then(function (token) {
                            tokenField.value = token;
                            form.submit();
                        });
                });
            }
        });
    });
});
</script>
@endif

@stack('scripts')
</body>
</html>
