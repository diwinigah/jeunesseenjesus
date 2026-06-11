@php
    $investor = auth('investor')->user();
@endphp

<nav style="
    background: #172033;
    color: #fff;
    padding: 12px 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.9rem;
">
    <div style="font-weight: 700; letter-spacing: 0.05em;">
        Investisseur
    </div>

    <div style="display: flex; gap: 16px; align-items: center;">
        @if ($investor)
            <span>{{ $investor->name }}</span>
            <a href="{{ route('investor.dashboard') }}" style="color: #fff; text-decoration: none; font-weight: 600;">
                Mon espace
            </a>
            <form method="POST" action="{{ route('investor.logout') }}" style="display: inline;">
                @csrf
                <button type="submit" style="
                    background: none;
                    border: none;
                    color: #fff;
                    cursor: pointer;
                    font-weight: 600;
                    padding: 0;
                    font-size: 0.9rem;
                ">
                    Déconnexion
                </button>
            </form>
        @else
            <a href="{{ route('investor.register') }}" style="
                color: #fff;
                text-decoration: none;
                font-weight: 600;
                padding: 6px 12px;
                border: 1px solid #fff;
                border-radius: 4px;
            ">
                S'inscrire
            </a>
            <a href="{{ route('investor.login') }}" style="
                color: #172033;
                background: #047857;
                text-decoration: none;
                font-weight: 600;
                padding: 6px 12px;
                border-radius: 4px;
            ">
                Se connecter
            </a>
        @endif
    </div>
</nav>
