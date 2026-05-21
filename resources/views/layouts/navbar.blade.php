<nav class="main-header navbar navbar-expand navbar-white navbar-light" style="
    background: #FFFFFF !important;
    border-bottom: 1px solid #E2E8F0 !important;
    box-shadow: 0 1px 3px rgba(0,0,0,.06) !important;
    font-family: 'Plus Jakarta Sans', sans-serif;
    min-height: 57px;
    padding: 0 16px;
">
    <!-- Left: Toggle + Home -->
    <ul class="navbar-nav" style="gap: 4px;">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button" style="
                color: #4A5568;
                width: 38px; height: 38px;
                display: flex; align-items: center; justify-content: center;
                border-radius: 8px;
                transition: all .18s ease;
            ">
                <i class="fas fa-bars" style="font-size: 15px;"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('dashboard') }}" class="nav-link" style="
                color: #718096; font-size: 13px; font-weight: 600;
                padding: 0 10px; height: 38px;
                display: flex; align-items: center; gap: 6px;
                border-radius: 8px; transition: all .18s ease;
            ">
                <i class="fas fa-home" style="font-size:12px;"></i>
                <span>Home</span>
            </a>
        </li>
    </ul>

    <!-- Center: System Title -->
    <div class="navbar-nav mx-auto d-none d-md-flex">
        <div style="
            display: flex; align-items: center; gap: 8px;
            background: #1A202C;
            padding: 7px 18px;
            border-radius: 30px;
        ">
            <div style="
                width: 20px; height: 20px;
                background: #FFD700;
                border-radius: 4px;
                display: flex; align-items: center; justify-content: center;
            ">
                <i class="fas fa-chart-line" style="color:#1A202C; font-size:10px;"></i>
            </div>
            <span style="
                color: #F7FAFC; font-weight: 700; font-size: 13px;
                letter-spacing: .3px;
            ">Backlog Monitoring System</span>
        </div>
    </div>

    <!-- Right: Notifications + User -->
    <ul class="navbar-nav ml-auto" style="gap: 4px; align-items: center;">

        <!-- Notifications -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" style="
                position: relative;
                width: 38px; height: 38px;
                display: flex; align-items: center; justify-content: center;
                border-radius: 8px; color: #4A5568;
                transition: all .18s ease;
            ">
                <i class="far fa-bell" style="font-size: 16px;"></i>
                @if(($totalOverdue ?? 0) > 0)
                    <span style="
                        position: absolute; top: 6px; right: 6px;
                        background: #E53E3E; color: white;
                        font-size: 9px; font-weight: 800;
                        min-width: 16px; height: 16px;
                        border-radius: 20px; padding: 0 4px;
                        display: flex; align-items: center; justify-content: center;
                        border: 2px solid white;
                    ">{{ $totalOverdue }}</span>
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="
                border: 1px solid #E2E8F0;
                border-radius: 12px;
                box-shadow: 0 10px 25px rgba(0,0,0,.12);
                padding: 0; overflow: hidden;
                min-width: 300px;
            ">
                <div style="
                    background: #1A202C;
                    padding: 14px 18px;
                    display: flex; align-items: center; justify-content: space-between;
                ">
                    <span style="color:#F7FAFC; font-weight:700; font-size:13px;">
                        <i class="far fa-bell mr-2" style="color:#FFD700;"></i>Notifikasi
                    </span>
                    @if(($totalOverdue ?? 0) > 0)
                        <span style="
                            background: #E53E3E; color: white;
                            font-size: 10px; font-weight: 800;
                            padding: 2px 8px; border-radius: 20px;
                        ">{{ $totalOverdue }} Overdue</span>
                    @endif
                </div>

                @if(($totalOverdue ?? 0) > 0)
                    <div style="max-height: 280px; overflow-y: auto;">
                    @foreach($overdueItems ?? [] as $item)
                    <a href="{{ route('backlog-daily.index') }}" class="dropdown-item" style="
                        padding: 12px 18px;
                        border-bottom: 1px solid #F7FAFC;
                        font-size: 12.5px;
                        color: #2D3748;
                        transition: background .15s;
                    ">
                        <div style="display:flex; align-items:flex-start; gap:10px;">
                            <div style="
                                width: 28px; height: 28px; border-radius: 50%;
                                background: #FED7D7; flex-shrink:0;
                                display: flex; align-items: center; justify-content: center;
                            ">
                                <i class="fas fa-exclamation-triangle" style="color:#E53E3E; font-size:11px;"></i>
                            </div>
                            <div>
                                <div style="font-weight:700;">{{ $item->reservation_number ?? '-' }}</div>
                                <div style="color:#718096; font-size:11.5px;">{{ $item->mo_description ?? '-' }}</div>
                                <div style="color:#A0AEC0; font-size:10.5px; margin-top:2px;">
                                    {{ $item->plan_date ? \Carbon\Carbon::parse($item->plan_date)->diffForHumans() : '-' }}
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                    </div>
                    <a href="{{ route('backlog-daily.index') }}" style="
                        display:block; padding: 12px 18px;
                        text-align: center; font-size: 12.5px; font-weight: 700;
                        color: #1A202C; text-decoration: none;
                        border-top: 1px solid #E2E8F0;
                        background: #F7FAFC;
                    ">
                        Lihat semua overdue →
                    </a>
                @else
                    <div style="padding: 24px 18px; text-align: center;">
                        <i class="fas fa-check-circle" style="color: #38A169; font-size: 28px; margin-bottom: 8px; display:block;"></i>
                        <div style="color: #718096; font-size: 13px; font-weight: 600;">Tidak ada backlog overdue</div>
                    </div>
                @endif
            </div>
        </li>

        <!-- Divider -->
        <div style="width:1px; height:24px; background:#E2E8F0; margin: 0 4px;"></div>

        <!-- User Dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" style="
                display: flex; align-items: center; gap: 8px;
                padding: 4px 10px;
                border-radius: 8px;
                transition: all .18s ease;
                text-decoration: none;
            ">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=FFD700&color=1A202C&bold=true&size=80"
                     style="width:32px; height:32px; border-radius:50%; border:2px solid #FFD700;"
                     alt="{{ Auth::user()->name }}">
                <span class="d-none d-md-inline" style="color:#2D3748; font-weight:700; font-size:13px;">
                    {{ Auth::user()->name }}
                </span>
                <i class="fas fa-chevron-down d-none d-md-inline" style="color:#A0AEC0; font-size:10px;"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right" style="
                border: 1px solid #E2E8F0;
                border-radius: 12px;
                box-shadow: 0 10px 25px rgba(0,0,0,.12);
                padding: 8px;
                min-width: 190px;
                overflow: hidden;
            ">
                <div style="padding: 10px 12px 8px; border-bottom: 1px solid #F7FAFC; margin-bottom: 4px;">
                    <div style="font-weight:700; font-size:13px; color:#1A202C;">{{ Auth::user()->name }}</div>
                    <div style="
                        display:inline-block;
                        background: rgba(255,215,0,.2);
                        color: #7B5E00;
                        font-size: 10px; font-weight: 800;
                        padding: 1px 8px; border-radius: 20px;
                        margin-top: 3px; letter-spacing: .5px; text-transform: uppercase;
                    ">{{ ucfirst(Auth::user()->role ?? 'User') }}</div>
                </div>
                <a href="{{ route('profile') }}" class="dropdown-item" style="
                    border-radius: 8px; font-size: 13px; font-weight: 600;
                    padding: 9px 12px; color: #4A5568;
                    display: flex; align-items: center; gap: 8px;
                    transition: all .15s;
                ">
                    <i class="fas fa-user-circle" style="color:#A0AEC0; font-size:14px;"></i> Profile
                </a>
                <div style="height:1px; background:#F7FAFC; margin: 4px 0;"></div>
                <a href="#" class="dropdown-item" style="
                    border-radius: 8px; font-size: 13px; font-weight: 600;
                    padding: 9px 12px; color: #E53E3E;
                    display: flex; align-items: center; gap: 8px;
                    transition: all .15s;
                " onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt" style="font-size:14px;"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </div>
        </li>
    </ul>
</nav>

<style>
.main-header .nav-link:hover { background: #F7FAFC !important; }
.dropdown-item:hover { background: #FFF3B0 !important; color: #1A202C !important; }
</style>