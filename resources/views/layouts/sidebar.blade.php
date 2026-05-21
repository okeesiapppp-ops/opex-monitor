<aside class="main-sidebar elevation-0" style="
    background: #1A202C;
    border-right: 1px solid #2D3748;
    font-family: 'Plus Jakarta Sans', sans-serif;
">

  <!-- Brand Logo -->
  <a href="{{ route('dashboard') }}" class="brand-link" style="
      background: #1A202C;
      border-bottom: 1px solid #2D3748;
      padding: 14px 18px;
      display: flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
  ">
    <div style="
        width: 32px; height: 32px;
        background: #FFD700;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(255,215,0,.35);
    ">
        <i class="fas fa-chart-line" style="color:#1A202C; font-size:14px;"></i>
    </div>
    <div class="brand-text" style="line-height:1.2;">
        <div style="color:#FFD700; font-weight:800; font-size:14px; letter-spacing:.5px;">UTA BASIS</div>
        <div style="color:#718096; font-size:10px; font-weight:500; letter-spacing:.3px;">Backlog Monitoring</div>
    </div>
  </a>

  <div class="sidebar" style="padding-top: 8px;">

    <!-- User Panel -->
    <div style="
        margin: 10px 12px 8px;
        background: #2D3748;
        border-radius: 10px;
        padding: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
    ">
      <div style="flex-shrink:0;">
        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=FFD700&color=1A202C&bold=true&size=80"
             style="width:38px; height:38px; border-radius:50%; border: 2px solid #FFD700; object-fit:cover;"
             alt="User">
      </div>
      <div style="min-width:0;">
        <a href="{{ route('profile') }}" style="
            color:#F7FAFC; font-weight:700; font-size:13px;
            text-decoration:none; display:block;
            white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
        ">{{ Auth::user()->name }}</a>
        <div style="
            display:inline-block;
            background: rgba(255,215,0,.15);
            color: #FFD700;
            font-size: 10px;
            font-weight: 700;
            padding: 1px 8px;
            border-radius: 20px;
            letter-spacing: .5px;
            margin-top: 2px;
            text-transform: uppercase;
        ">{{ ucfirst(Auth::user()->role ?? 'User') }}</div>
      </div>
    </div>

    <!-- Navigation -->
    <nav class="mt-1" style="padding: 0 8px;">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <!-- Dashboard -->
        <li class="nav-item" style="margin-bottom:2px;">
          <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard*') ? 'active' : '' }}" style="
              border-radius: 8px; font-size: 13px; font-weight: 600;
              padding: 9px 14px; color: #A0AEC0; transition: all .18s ease;
              display: flex; align-items: center; gap: 10px;
              {{ request()->routeIs('dashboard*') ? 'background: #FFD700 !important; color: #1A202C !important; box-shadow: 0 2px 8px rgba(255,215,0,.35);' : '' }}
          ">
            <i class="fas fa-tachometer-alt" style="width:16px; text-align:center; font-size:13px;"></i>
            <span>Dashboard</span>
          </a>
        </li>

        @if(Auth::user()->role == 'admin')

        <!-- BACKLOG MANAGEMENT -->
        <li style="padding: 12px 14px 4px; font-size: 10px; font-weight: 800; letter-spacing: 1.2px; color: #4A5568; text-transform: uppercase;">Backlog Management</li>

        <li class="nav-item" style="margin-bottom:2px;">
          <a href="{{ route('backlog.index') }}" class="nav-link {{ request()->routeIs('backlog.index') ? 'active' : '' }}" style="
              border-radius: 8px; font-size: 13px; font-weight: 600;
              padding: 9px 14px; color: #A0AEC0; transition: all .18s ease;
              display: flex; align-items: center; gap: 10px;
              {{ request()->routeIs('backlog.index') ? 'background: #FFD700 !important; color: #1A202C !important; box-shadow: 0 2px 8px rgba(255,215,0,.35);' : '' }}
          ">
            <i class="fas fa-database" style="width:16px; text-align:center; font-size:13px;"></i>
            <span>Data Backlog</span>
          </a>
        </li>

        <li class="nav-item" style="margin-bottom:2px;">
          <a href="{{ route('backlog-daily.index') }}" class="nav-link {{ request()->routeIs('backlog-daily*') && !request()->is('backlog-daily*status=outstanding') ? 'active' : '' }}" style="
              border-radius: 8px; font-size: 13px; font-weight: 600;
              padding: 9px 14px; color: #A0AEC0; transition: all .18s ease;
              display: flex; align-items: center; gap: 10px;
              {{ request()->routeIs('backlog-daily*') && request('status') != 'outstanding' ? 'background: #FFD700 !important; color: #1A202C !important; box-shadow: 0 2px 8px rgba(255,215,0,.35);' : '' }}
          ">
            <i class="fas fa-calendar-day" style="width:16px; text-align:center; font-size:13px;"></i>
            <span>Backlog Daily (DPS)</span>
          </a>
        </li>

        {{-- ⭐ MENU OUTSTANDING --}}
        <li class="nav-item" style="margin-bottom:2px;">
          <a href="{{ route('backlog-daily.index') }}?status=outstanding" class="nav-link {{ request()->routeIs('backlog-daily*') && request('status') == 'outstanding' ? 'active' : '' }}" style="
              border-radius: 8px; font-size: 13px; font-weight: 600;
              padding: 9px 14px; color: #A0AEC0; transition: all .18s ease;
              display: flex; align-items: center; justify-content: space-between;
              {{ request()->routeIs('backlog-daily*') && request('status') == 'outstanding' ? 'background: #E53E3E !important; color: #fff !important; box-shadow: 0 2px 8px rgba(229,62,62,.35);' : '' }}
          ">
            <div style="display:flex; align-items:center; gap:10px;">
                <i class="fas fa-exclamation-circle" style="width:16px; text-align:center; font-size:13px; color: {{ request('status') == 'outstanding' ? '#fff' : '#FC8181' }};"></i>
                <span style="color: {{ request('status') == 'outstanding' ? '#fff' : '#FC8181' }}; font-weight:700;">Outstanding</span>
            </div>
            @if(isset($totalOverdue) && $totalOverdue > 0)
            <span style="
                background: #E53E3E; color: #fff;
                font-size: 10px; font-weight: 800;
                padding: 1px 7px; border-radius: 20px;
                min-width: 20px; text-align: center;
            ">{{ $totalOverdue }}</span>
            @else
            <span style="
                background: rgba(252,129,129,.2); color: #FC8181;
                font-size: 10px; font-weight: 800;
                padding: 1px 7px; border-radius: 20px;
            ">!</span>
            @endif
          </a>
        </li>

        <li class="nav-item" style="margin-bottom:2px;">
          <a href="{{ route('archive.index') }}" class="nav-link {{ request()->routeIs('archive*') ? 'active' : '' }}" style="
              border-radius: 8px; font-size: 13px; font-weight: 600;
              padding: 9px 14px; color: #A0AEC0; transition: all .18s ease;
              display: flex; align-items: center; gap: 10px;
              {{ request()->routeIs('archive*') ? 'background: #FFD700 !important; color: #1A202C !important; box-shadow: 0 2px 8px rgba(255,215,0,.35);' : '' }}
          ">
            <i class="fas fa-archive" style="width:16px; text-align:center; font-size:13px;"></i>
            <span>Archive</span>
          </a>
        </li>

        <!-- REPORTS -->
        <li style="padding: 12px 14px 4px; font-size: 10px; font-weight: 800; letter-spacing: 1.2px; color: #4A5568; text-transform: uppercase;">Reports</li>

        <li class="nav-item" style="margin-bottom:2px;">
          <a href="{{ route('report.backlog-achievement') }}" class="nav-link {{ request()->routeIs('report.backlog-achievement') ? 'active' : '' }}" style="
              border-radius: 8px; font-size: 13px; font-weight: 600;
              padding: 9px 14px; color: #A0AEC0; transition: all .18s ease;
              display: flex; align-items: center; gap: 10px;
              {{ request()->routeIs('report.backlog-achievement') ? 'background: #FFD700 !important; color: #1A202C !important; box-shadow: 0 2px 8px rgba(255,215,0,.35);' : '' }}
          ">
            <i class="fas fa-chart-bar" style="width:16px; text-align:center; font-size:13px;"></i>
            <span>Backlog Achievement</span>
          </a>
        </li>

        <li class="nav-item" style="margin-bottom:2px;">
          <a href="{{ route('report.vendor-performance') }}" class="nav-link {{ request()->routeIs('report.vendor-performance') ? 'active' : '' }}" style="
              border-radius: 8px; font-size: 13px; font-weight: 600;
              padding: 9px 14px; color: #A0AEC0; transition: all .18s ease;
              display: flex; align-items: center; gap: 10px;
              {{ request()->routeIs('report.vendor-performance') ? 'background: #FFD700 !important; color: #1A202C !important; box-shadow: 0 2px 8px rgba(255,215,0,.35);' : '' }}
          ">
            <i class="fas fa-trophy" style="width:16px; text-align:center; font-size:13px;"></i>
            <span>Vendor Performance</span>
          </a>
        </li>

        <li class="nav-item" style="margin-bottom:2px;">
          <a href="{{ route('report.monthly-trend') }}" class="nav-link {{ request()->routeIs('report.monthly-trend') ? 'active' : '' }}" style="
              border-radius: 8px; font-size: 13px; font-weight: 600;
              padding: 9px 14px; color: #A0AEC0; transition: all .18s ease;
              display: flex; align-items: center; gap: 10px;
              {{ request()->routeIs('report.monthly-trend') ? 'background: #FFD700 !important; color: #1A202C !important; box-shadow: 0 2px 8px rgba(255,215,0,.35);' : '' }}
          ">
            <i class="fas fa-chart-line" style="width:16px; text-align:center; font-size:13px;"></i>
            <span>Monthly Trend</span>
          </a>
        </li>

        <!-- ADMINISTRATION -->
        <li style="padding: 12px 14px 4px; font-size: 10px; font-weight: 800; letter-spacing: 1.2px; color: #4A5568; text-transform: uppercase;">Administration</li>

        <li class="nav-item" style="margin-bottom:2px;">
          <a href="{{ route('vendor.index') }}" class="nav-link {{ request()->routeIs('vendor*') ? 'active' : '' }}" style="
              border-radius: 8px; font-size: 13px; font-weight: 600;
              padding: 9px 14px; color: #A0AEC0; transition: all .18s ease;
              display: flex; align-items: center; gap: 10px;
              {{ request()->routeIs('vendor*') ? 'background: #FFD700 !important; color: #1A202C !important; box-shadow: 0 2px 8px rgba(255,215,0,.35);' : '' }}
          ">
            <i class="fas fa-truck" style="width:16px; text-align:center; font-size:13px;"></i>
            <span>Vendor Management</span>
          </a>
        </li>

        <li class="nav-item" style="margin-bottom:2px;">
          <a href="{{ route('planner.index') }}" class="nav-link {{ request()->routeIs('planner*') ? 'active' : '' }}" style="
              border-radius: 8px; font-size: 13px; font-weight: 600;
              padding: 9px 14px; color: #A0AEC0; transition: all .18s ease;
              display: flex; align-items: center; gap: 10px;
              {{ request()->routeIs('planner*') ? 'background: #FFD700 !important; color: #1A202C !important; box-shadow: 0 2px 8px rgba(255,215,0,.35);' : '' }}
          ">
            <i class="fas fa-users" style="width:16px; text-align:center; font-size:13px;"></i>
            <span>Planner Management</span>
          </a>
        </li>

        <li class="nav-item" style="margin-bottom:2px;">
          <a href="{{ route('user.index') }}" class="nav-link {{ request()->routeIs('user*') && !request()->routeIs('profile') ? 'active' : '' }}" style="
              border-radius: 8px; font-size: 13px; font-weight: 600;
              padding: 9px 14px; color: #A0AEC0; transition: all .18s ease;
              display: flex; align-items: center; gap: 10px;
              {{ request()->routeIs('user*') && !request()->routeIs('profile') ? 'background: #FFD700 !important; color: #1A202C !important; box-shadow: 0 2px 8px rgba(255,215,0,.35);' : '' }}
          ">
            <i class="fas fa-user-shield" style="width:16px; text-align:center; font-size:13px;"></i>
            <span>User Management</span>
          </a>
        </li>

        @elseif(Auth::user()->role == 'vendor')

        <li style="padding: 12px 14px 4px; font-size: 10px; font-weight: 800; letter-spacing: 1.2px; color: #4A5568; text-transform: uppercase;">Main Menu</li>

        <li class="nav-item" style="margin-bottom:2px;">
          <a href="{{ route('backlog-daily.index') }}" class="nav-link {{ request()->routeIs('backlog-daily*') && request('status') != 'outstanding' ? 'active' : '' }}" style="
              border-radius: 8px; font-size: 13px; font-weight: 600;
              padding: 9px 14px; color: #A0AEC0; transition: all .18s ease;
              display: flex; align-items: center; gap: 10px;
              {{ request()->routeIs('backlog-daily*') && request('status') != 'outstanding' ? 'background: #FFD700 !important; color: #1A202C !important; box-shadow: 0 2px 8px rgba(255,215,0,.35);' : '' }}
          ">
            <i class="fas fa-calendar-day" style="width:16px; text-align:center; font-size:13px;"></i>
            <span>Backlog For Daily</span>
          </a>
        </li>

        {{-- ⭐ OUTSTANDING untuk vendor --}}
        <li class="nav-item" style="margin-bottom:2px;">
          <a href="{{ route('backlog-daily.index') }}?status=outstanding" class="nav-link {{ request()->routeIs('backlog-daily*') && request('status') == 'outstanding' ? 'active' : '' }}" style="
              border-radius: 8px; font-size: 13px; font-weight: 600;
              padding: 9px 14px; color: #A0AEC0; transition: all .18s ease;
              display: flex; align-items: center; justify-content: space-between;
              {{ request()->routeIs('backlog-daily*') && request('status') == 'outstanding' ? 'background: #E53E3E !important; color: #fff !important; box-shadow: 0 2px 8px rgba(229,62,62,.35);' : '' }}
          ">
            <div style="display:flex; align-items:center; gap:10px;">
                <i class="fas fa-exclamation-circle" style="width:16px; text-align:center; font-size:13px; color: {{ request('status') == 'outstanding' ? '#fff' : '#FC8181' }};"></i>
                <span style="color: {{ request('status') == 'outstanding' ? '#fff' : '#FC8181' }}; font-weight:700;">Outstanding</span>
            </div>
            <span style="
                background: rgba(252,129,129,.2); color: #FC8181;
                font-size: 10px; font-weight: 800;
                padding: 1px 7px; border-radius: 20px;
            ">!</span>
          </a>
        </li>

        <li class="nav-item" style="margin-bottom:2px;">
          <a href="{{ route('archive.index') }}" class="nav-link {{ request()->routeIs('archive*') ? 'active' : '' }}" style="
              border-radius: 8px; font-size: 13px; font-weight: 600;
              padding: 9px 14px; color: #A0AEC0; transition: all .18s ease;
              display: flex; align-items: center; gap: 10px;
              {{ request()->routeIs('archive*') ? 'background: #FFD700 !important; color: #1A202C !important; box-shadow: 0 2px 8px rgba(255,215,0,.35);' : '' }}
          ">
            <i class="fas fa-archive" style="width:16px; text-align:center; font-size:13px;"></i>
            <span>Archive Data</span>
          </a>
        </li>

        @elseif(Auth::user()->role == 'planner')

        <li style="padding: 12px 14px 4px; font-size: 10px; font-weight: 800; letter-spacing: 1.2px; color: #4A5568; text-transform: uppercase;">Main Menu</li>

        <li class="nav-item" style="margin-bottom:2px;">
          <a href="{{ route('backlog-daily.index') }}" class="nav-link {{ request()->routeIs('backlog-daily*') && request('status') != 'outstanding' ? 'active' : '' }}" style="
              border-radius: 8px; font-size: 13px; font-weight: 600;
              padding: 9px 14px; color: #A0AEC0; transition: all .18s ease;
              display: flex; align-items: center; gap: 10px;
              {{ request()->routeIs('backlog-daily*') && request('status') != 'outstanding' ? 'background: #FFD700 !important; color: #1A202C !important; box-shadow: 0 2px 8px rgba(255,215,0,.35);' : '' }}
          ">
            <i class="fas fa-calendar-day" style="width:16px; text-align:center; font-size:13px;"></i>
            <span>Backlog For Daily</span>
          </a>
        </li>

        {{-- ⭐ OUTSTANDING untuk planner --}}
        <li class="nav-item" style="margin-bottom:2px;">
          <a href="{{ route('backlog-daily.index') }}?status=outstanding" class="nav-link {{ request()->routeIs('backlog-daily*') && request('status') == 'outstanding' ? 'active' : '' }}" style="
              border-radius: 8px; font-size: 13px; font-weight: 600;
              padding: 9px 14px; color: #A0AEC0; transition: all .18s ease;
              display: flex; align-items: center; justify-content: space-between;
              {{ request()->routeIs('backlog-daily*') && request('status') == 'outstanding' ? 'background: #E53E3E !important; color: #fff !important; box-shadow: 0 2px 8px rgba(229,62,62,.35);' : '' }}
          ">
            <div style="display:flex; align-items:center; gap:10px;">
                <i class="fas fa-exclamation-circle" style="width:16px; text-align:center; font-size:13px; color: {{ request('status') == 'outstanding' ? '#fff' : '#FC8181' }};"></i>
                <span style="color: {{ request('status') == 'outstanding' ? '#fff' : '#FC8181' }}; font-weight:700;">Outstanding</span>
            </div>
            <span style="
                background: rgba(252,129,129,.2); color: #FC8181;
                font-size: 10px; font-weight: 800;
                padding: 1px 7px; border-radius: 20px;
            ">!</span>
          </a>
        </li>

        <li class="nav-item" style="margin-bottom:2px;">
          <a href="{{ route('archive.index') }}" class="nav-link {{ request()->routeIs('archive*') ? 'active' : '' }}" style="
              border-radius: 8px; font-size: 13px; font-weight: 600;
              padding: 9px 14px; color: #A0AEC0; transition: all .18s ease;
              display: flex; align-items: center; gap: 10px;
              {{ request()->routeIs('archive*') ? 'background: #FFD700 !important; color: #1A202C !important; box-shadow: 0 2px 8px rgba(255,215,0,.35);' : '' }}
          ">
            <i class="fas fa-archive" style="width:16px; text-align:center; font-size:13px;"></i>
            <span>Archive</span>
          </a>
        </li>

        @endif

        <!-- ACCOUNT -->
        <li style="padding: 12px 14px 4px; font-size: 10px; font-weight: 800; letter-spacing: 1.2px; color: #4A5568; text-transform: uppercase;">Account</li>

        <li class="nav-item" style="margin-bottom:2px;">
          <a href="{{ route('profile') }}" class="nav-link {{ request()->routeIs('profile*') ? 'active' : '' }}" style="
              border-radius: 8px; font-size: 13px; font-weight: 600;
              padding: 9px 14px; color: #A0AEC0; transition: all .18s ease;
              display: flex; align-items: center; gap: 10px;
              {{ request()->routeIs('profile*') ? 'background: #FFD700 !important; color: #1A202C !important; box-shadow: 0 2px 8px rgba(255,215,0,.35);' : '' }}
          ">
            <i class="fas fa-user" style="width:16px; text-align:center; font-size:13px;"></i>
            <span>Profile</span>
          </a>
        </li>

        <li class="nav-item" style="margin-bottom:8px;">
          <a href="{{ route('logout') }}" class="nav-link" style="
              border-radius: 8px; font-size: 13px; font-weight: 600;
              padding: 9px 14px; color: #FC8181; transition: all .18s ease;
              display: flex; align-items: center; gap: 10px;
          "
          onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
            <i class="fas fa-sign-out-alt" style="width:16px; text-align:center; font-size:13px;"></i>
            <span>Logout</span>
          </a>
          <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
        </li>

      </ul>
    </nav>
  </div>
</aside>

<style>
.main-sidebar .nav-link:not(.active):hover {
    background: rgba(255,215,0,.12) !important;
    color: #FFD700 !important;
}
.main-sidebar .sidebar {
    scrollbar-width: thin;
    scrollbar-color: #2D3748 #1A202C;
}
.main-sidebar .sidebar::-webkit-scrollbar { width: 4px; }
.main-sidebar .sidebar::-webkit-scrollbar-track { background: #1A202C; }
.main-sidebar .sidebar::-webkit-scrollbar-thumb { background: #2D3748; border-radius: 4px; }
</style>