<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - OPEX Monitor | UTA BASIS</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/css/bootstrap.min.css">

  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #1A202C;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }

    /* Animated background pattern */
    body::before {
        content: '';
        position: fixed; inset: 0;
        background:
            radial-gradient(ellipse at 20% 50%, rgba(255,215,0,.08) 0%, transparent 50%),
            radial-gradient(ellipse at 80% 20%, rgba(255,215,0,.05) 0%, transparent 40%),
            radial-gradient(ellipse at 60% 80%, rgba(45,55,72,.8) 0%, transparent 60%);
        pointer-events: none;
        z-index: 0;
    }

    /* Decorative grid */
    body::after {
        content: '';
        position: fixed; inset: 0;
        background-image:
            linear-gradient(rgba(255,215,0,.04) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,215,0,.04) 1px, transparent 1px);
        background-size: 50px 50px;
        pointer-events: none;
        z-index: 0;
    }

    /* Floating decorative circles */
    .deco-circle {
        position: fixed;
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
    }
    .deco-1 {
        width: 400px; height: 400px;
        background: radial-gradient(circle, rgba(255,215,0,.06) 0%, transparent 70%);
        top: -100px; right: -100px;
    }
    .deco-2 {
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(255,215,0,.04) 0%, transparent 70%);
        bottom: -80px; left: -80px;
    }

    /* Login Container */
    .login-container {
        position: relative; z-index: 1;
        display: flex;
        width: 100%;
        max-width: 900px;
        min-height: 560px;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 25px 60px rgba(0,0,0,.5), 0 0 0 1px rgba(255,215,0,.1);
        margin: 20px;
    }

    /* Left Panel - Brand */
    .login-brand {
        flex: 1;
        background: linear-gradient(145deg, #FFD700 0%, #E6C200 60%, #C9A800 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 48px 40px;
        position: relative;
        overflow: hidden;
    }

    .login-brand::before {
        content: '';
        position: absolute; inset: 0;
        background:
            radial-gradient(ellipse at 30% 30%, rgba(255,255,255,.25) 0%, transparent 50%),
            radial-gradient(ellipse at 70% 70%, rgba(0,0,0,.08) 0%, transparent 50%);
    }

    .login-brand::after {
        content: '';
        position: absolute;
        width: 300px; height: 300px;
        border: 2px solid rgba(26,32,44,.08);
        border-radius: 50%;
        bottom: -100px; right: -100px;
    }

    .brand-logo-wrap {
        position: relative; z-index: 1;
        text-align: center;
    }

    .brand-icon-big {
        width: 80px; height: 80px;
        background: #1A202C;
        border-radius: 20px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 20px;
        box-shadow: 0 8px 24px rgba(26,32,44,.3);
    }
    .brand-icon-big i { color: #FFD700; font-size: 32px; }

    .brand-title {
        font-size: 2rem;
        font-weight: 800;
        color: #1A202C;
        letter-spacing: -1px;
        line-height: 1;
    }
    .brand-subtitle {
        font-size: .9rem;
        font-weight: 600;
        color: rgba(26,32,44,.6);
        letter-spacing: .5px;
        margin-top: 6px;
    }

    .brand-divider {
        width: 40px; height: 3px;
        background: rgba(26,32,44,.2);
        border-radius: 2px;
        margin: 24px auto;
    }

    .brand-features {
        list-style: none;
        padding: 0;
        width: 100%;
    }
    .brand-features li {
        display: flex; align-items: center; gap: 10px;
        font-size: 13px; font-weight: 600;
        color: rgba(26,32,44,.7);
        padding: 6px 0;
    }
    .brand-features li i {
        width: 24px; height: 24px;
        background: rgba(26,32,44,.1);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 10px; color: #1A202C;
        flex-shrink: 0;
    }

    /* Right Panel - Form */
    .login-form-panel {
        width: 380px;
        flex-shrink: 0;
        background: #FFFFFF;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 48px 40px;
    }

    .form-heading {
        margin-bottom: 32px;
    }
    .form-heading .welcome {
        font-size: .8rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #A0AEC0;
        margin-bottom: 8px;
    }
    .form-heading h2 {
        font-size: 1.6rem;
        font-weight: 800;
        color: #1A202C;
        letter-spacing: -0.5px;
        line-height: 1.2;
    }
    .form-heading p {
        color: #718096;
        font-size: 13.5px;
        margin-top: 6px;
        font-weight: 500;
    }

    /* Form fields */
    .field-group {
        margin-bottom: 18px;
    }
    .field-label {
        display: block;
        font-size: 12.5px;
        font-weight: 700;
        color: #1A202C;
        letter-spacing: .3px;
        margin-bottom: 7px;
    }
    .field-wrap {
        position: relative;
    }
    .field-wrap i {
        position: absolute;
        left: 14px; top: 50%;
        transform: translateY(-50%);
        color: #A0AEC0;
        font-size: 14px;
        pointer-events: none;
        transition: color .2s;
    }
    .field-input {
        width: 100%;
        height: 46px;
        padding: 0 14px 0 42px;
        border: 1.5px solid #E2E8F0;
        border-radius: 10px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 14px;
        font-weight: 500;
        color: #1A202C;
        background: #F7FAFC;
        outline: none;
        transition: all .2s ease;
    }
    .field-input:focus {
        border-color: #E6C200;
        background: #FFFFFF;
        box-shadow: 0 0 0 4px rgba(255,215,0,.15);
    }
    .field-input:focus + i,
    .field-wrap:focus-within i {
        color: #E6C200;
    }
    .field-input.is-invalid {
        border-color: #E53E3E;
        background: #FFF5F5;
    }
    .invalid-msg {
        font-size: 12px;
        color: #E53E3E;
        font-weight: 600;
        margin-top: 5px;
        display: flex; align-items: center; gap: 4px;
    }

    /* Remember checkbox */
    .remember-row {
        display: flex; align-items: center; gap: 8px;
        margin-bottom: 22px;
    }
    .remember-row input[type="checkbox"] {
        width: 16px; height: 16px;
        accent-color: #1A202C;
        cursor: pointer;
    }
    .remember-row label {
        font-size: 13px; font-weight: 600; color: #4A5568;
        cursor: pointer; margin: 0;
    }

    /* Submit button */
    .btn-login {
        width: 100%;
        height: 48px;
        background: linear-gradient(135deg, #1A202C 0%, #2D3748 100%);
        color: #FFD700;
        border: none;
        border-radius: 10px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 14.5px;
        font-weight: 800;
        letter-spacing: .5px;
        cursor: pointer;
        transition: all .2s ease;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        box-shadow: 0 4px 12px rgba(26,32,44,.3);
    }
    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(26,32,44,.4);
        background: linear-gradient(135deg, #2D3748 0%, #4A5568 100%);
    }
    .btn-login:active { transform: translateY(0); }

    /* Alert */
    .custom-alert {
        border-radius: 10px;
        padding: 12px 14px;
        margin-bottom: 18px;
        font-size: 13px;
        font-weight: 600;
        display: flex; align-items: flex-start; gap: 8px;
        border: none;
    }
    .custom-alert.error {
        background: #FED7D7; color: #9B2C2C;
    }
    .custom-alert.success {
        background: #C6F6D5; color: #276749;
    }

    /* Demo accounts */
    .demo-section {
        margin-top: 24px;
        padding: 14px 16px;
        background: #F7FAFC;
        border-radius: 10px;
        border: 1px dashed #E2E8F0;
    }
    .demo-section .demo-title {
        font-size: 10.5px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #A0AEC0;
        margin-bottom: 8px;
    }
    .demo-section .demo-item {
        display: flex; align-items: center; justify-content: space-between;
        padding: 4px 0;
        font-size: 12px;
        color: #4A5568;
        border-bottom: 1px solid #EDF2F7;
    }
    .demo-section .demo-item:last-child { border-bottom: none; padding-bottom: 0; }
    .demo-section .demo-role {
        font-weight: 800;
        font-size: 10px;
        background: #1A202C;
        color: #FFD700;
        padding: 1px 7px;
        border-radius: 20px;
    }

    /* Responsive */
    @media (max-width: 640px) {
        .login-brand { display: none; }
        .login-form-panel { width: 100%; }
        .login-container { border-radius: 16px; }
    }
  </style>
</head>
<body>
  <div class="deco-circle deco-1"></div>
  <div class="deco-circle deco-2"></div>

  <div class="login-container">

    <!-- Left: Brand Panel -->
    <div class="login-brand">
      <div class="brand-logo-wrap">
        <div class="brand-icon-big">
          <i class="fas fa-chart-line"></i>
        </div>
        <div class="brand-title">UTA BASIS</div>
        <div class="brand-subtitle">BACKLOG EXCELLENT SYSTEM</div>
        <div class="brand-divider"></div>
        <ul class="brand-features">
          <li><i class="fas fa-tachometer-alt"></i> Real-time Backlog Monitoring</li>
          <li><i class="fas fa-trophy"></i> Vendor Performance Tracking</li>
          <li><i class="fas fa-chart-bar"></i> Comprehensive Reports</li>
          <li><i class="fas fa-shield-alt"></i> Role-based Access Control</li>
        </ul>
      </div>
    </div>

    <!-- Right: Form Panel -->
    <div class="login-form-panel">
      <div class="form-heading">
        <div class="welcome">Welcome back</div>
        <h2>Sign in to<br>your account</h2>
        <p>OPEX Monitor &mdash; United Tractors ARIA</p>
      </div>

      {{-- Alerts --}}
      @if(session('error'))
        <div class="custom-alert error">
          <i class="fas fa-exclamation-circle" style="margin-top:1px; flex-shrink:0;"></i>
          <span>{{ session('error') }}</span>
        </div>
      @endif
      @if(session('success'))
        <div class="custom-alert success">
          <i class="fas fa-check-circle" style="margin-top:1px; flex-shrink:0;"></i>
          <span>{{ session('success') }}</span>
        </div>
      @endif
      @if($errors->any())
        <div class="custom-alert error">
          <i class="fas fa-exclamation-circle" style="margin-top:1px; flex-shrink:0;"></i>
          <ul style="margin:0; padding-left:14px;">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('login.process') }}" method="POST">
        @csrf

        <!-- Email -->
        <div class="field-group">
          <label class="field-label" for="email">Email Address</label>
          <div class="field-wrap">
            <input
              type="email"
              id="email"
              name="email"
              class="field-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
              placeholder="you@example.com"
              value="{{ old('email') }}"
              required autofocus
            >
            <i class="fas fa-envelope"></i>
          </div>
          @error('email')
            <div class="invalid-msg"><i class="fas fa-times-circle"></i> {{ $message }}</div>
          @enderror
        </div>

        <!-- Password -->
        <div class="field-group">
          <label class="field-label" for="password">Password</label>
          <div class="field-wrap">
            <input
              type="password"
              id="password"
              name="password"
              class="field-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
              placeholder="••••••••"
              required
            >
            <i class="fas fa-lock"></i>
          </div>
          @error('password')
            <div class="invalid-msg"><i class="fas fa-times-circle"></i> {{ $message }}</div>
          @enderror
        </div>

        <!-- Remember -->
        <div class="remember-row">
          <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
          <label for="remember">Remember me</label>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-login">
          <i class="fas fa-sign-in-alt"></i>
          Sign In
        </button>
      </form>

     

  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>