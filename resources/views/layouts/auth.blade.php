<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title') | Dhakaiya Investments</title>
<meta name="theme-color" content="#0b1428">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;700;800&display=swap" rel="stylesheet">

<style>
:root{
  --ink:#0b1428; --navy:#101c35; --gold:#d6ad62; --gold-2:#f0d49c; --gold-d:#a77a31;
  --text:#172033; --muted:#667085; --line:#e6e9f0;
}
*{margin:0;padding:0;box-sizing:border-box}
html{scroll-behavior:smooth}
body{font-family:'DM Sans',sans-serif;color:var(--text);background:#fff;min-height:100vh;display:flex;flex-direction:column;overflow-x:hidden;-webkit-font-smoothing:antialiased}
a{text-decoration:none;color:inherit}
button{font:inherit}
:focus-visible{outline:2px solid var(--gold);outline-offset:3px}
.container{width:min(1160px,calc(100% - 40px));margin:auto}
h1,h2,h3,h4{font-family:'Manrope',sans-serif}

/* HEADER (same as home) */
.site-header{position:sticky;top:0;z-index:1000;background:rgba(11,20,40,.94);backdrop-filter:blur(16px) saturate(140%);border-bottom:1px solid rgba(255,255,255,.07)}
.navbar{height:78px;display:flex;align-items:center;justify-content:space-between}
.brand img{height:50px;width:auto;object-fit:contain}
.nav-menu{display:flex;align-items:center;gap:32px}
.nav-menu a{font-size:14px;font-weight:500;color:#c3ccdb;transition:color .25s}
.nav-menu a:hover{color:var(--gold-2)}
.nav-actions{display:flex;align-items:center;gap:12px}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:9px;padding:13px 24px;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;border:1px solid transparent;transition:transform .25s,box-shadow .25s,border-color .25s,color .25s}
.btn-gold{color:var(--ink);background:linear-gradient(135deg,var(--gold-2),var(--gold));box-shadow:0 8px 24px rgba(214,173,98,.28)}
.btn-gold:hover{transform:translateY(-2px);box-shadow:0 14px 34px rgba(214,173,98,.42)}
.btn-ghost{color:#fff;border-color:rgba(255,255,255,.25);background:rgba(255,255,255,.04)}
.btn-ghost:hover,.btn-ghost.current{border-color:var(--gold);color:var(--gold-2)}
.menu-toggle{display:none;border:0;background:transparent;color:#fff;font-size:26px;cursor:pointer}

/* AUTH STAGE */
.auth-main{position:relative;flex:1;overflow:hidden;color:#fff;padding:70px 0 90px;
  background:radial-gradient(900px 500px at 85% 15%,rgba(214,173,98,.16),transparent 60%),radial-gradient(700px 500px at 0% 100%,rgba(60,100,170,.22),transparent 60%),linear-gradient(160deg,#0b1428 0%,#122240 60%,#17304f 100%)}
.auth-grid-bg{position:absolute;inset:0;opacity:.5;background-image:linear-gradient(rgba(255,255,255,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.04) 1px,transparent 1px);background-size:56px 56px;-webkit-mask-image:radial-gradient(ellipse at 70% 40%,#000 10%,transparent 70%);mask-image:radial-gradient(ellipse at 70% 40%,#000 10%,transparent 70%)}
.auth-wrap{position:relative;z-index:2;display:grid;grid-template-columns:1fr 480px;gap:70px;align-items:center}

/* Left intro */
.auth-intro h1{font-size:clamp(34px,4vw,50px);line-height:1.12;font-weight:800;letter-spacing:-1.5px;max-width:520px}
.auth-intro h1 .grad{background:linear-gradient(100deg,var(--gold-2),var(--gold) 60%,#b98a3f);-webkit-background-clip:text;background-clip:text;color:transparent}
.auth-intro p{color:#b7c2d4;font-size:16px;line-height:1.8;max-width:460px;margin-top:20px}
.auth-points{list-style:none;margin-top:30px;display:grid;gap:14px}
.auth-points li{display:flex;align-items:center;gap:12px;font-size:15px;font-weight:500;color:#dde4ef}
.auth-points li span{width:26px;height:26px;flex:none;display:grid;place-items:center;border-radius:50%;background:rgba(214,173,98,.15);color:var(--gold);font-size:13px}

/* 3D floating decoration */
.deco{position:absolute;inset:0;perspective:1000px;pointer-events:none;z-index:1}
.deco .ring{position:absolute;left:-120px;bottom:-160px;width:460px;height:460px;border:1px solid rgba(214,173,98,.25);border-radius:50%;transform:rotateX(68deg);animation:spin 22s linear infinite}
.deco .ring.r2{left:-60px;bottom:-110px;width:340px;height:340px;border-style:dashed;animation-direction:reverse;animation-duration:30s}
@keyframes spin{to{transform:rotateX(68deg) rotateZ(360deg)}}
.cube{position:absolute;right:6%;top:90px;width:56px;height:56px;transform-style:preserve-3d;animation:cube 16s linear infinite}
.cube i{position:absolute;inset:0;display:grid;place-items:center;font-style:normal;font-size:22px;color:var(--ink);background:linear-gradient(135deg,var(--gold-2),var(--gold));border:1px solid rgba(255,255,255,.4)}
.cube i:nth-child(1){transform:translateZ(28px)}
.cube i:nth-child(2){transform:rotateY(180deg) translateZ(28px)}
.cube i:nth-child(3){transform:rotateY(90deg) translateZ(28px)}
.cube i:nth-child(4){transform:rotateY(-90deg) translateZ(28px)}
.cube i:nth-child(5){transform:rotateX(90deg) translateZ(28px)}
.cube i:nth-child(6){transform:rotateX(-90deg) translateZ(28px)}
@keyframes cube{to{transform:rotateX(360deg) rotateY(360deg)}}

/* Auth card */
.auth-card{background:#fff;color:var(--text);border-radius:22px;padding:40px 38px;box-shadow:0 40px 90px rgba(0,0,0,.4);animation:cardIn .8s cubic-bezier(.2,.8,.2,1) both}
@keyframes cardIn{from{opacity:0;transform:translateY(30px) rotateX(8deg)}}
.auth-card h2{font-size:28px;color:var(--navy);letter-spacing:-.5px;margin-bottom:6px}
.subtitle{color:var(--muted);font-size:15px;margin-bottom:24px}
.auth-card h2:not(:has(+ .subtitle)){margin-bottom:22px}

.alert-error{background:#fef2f2;border:1px solid #fecaca;color:#b42318;border-radius:10px;padding:12px 14px;font-size:14px;line-height:1.6;margin-bottom:20px}

.form-group{margin-bottom:18px}
.form-group label{display:block;font-size:13px;font-weight:600;color:var(--navy);margin-bottom:8px}
.form-group input{width:100%;padding:13px 15px;font:inherit;font-size:15px;color:var(--text);background:#f7f8fb;border:1px solid var(--line);border-radius:10px;transition:border-color .2s,box-shadow .2s,background .2s}
.form-group input:focus{outline:none;background:#fff;border-color:var(--gold);box-shadow:0 0 0 4px rgba(214,173,98,.18)}
.form-group input::placeholder{color:#9aa3b2}
.password-wrapper{position:relative}
.password-wrapper input{padding-right:70px}
.password-toggle{position:absolute;right:8px;top:50%;transform:translateY(-50%);border:0;background:transparent;color:var(--gold-d);font-size:13px;font-weight:700;padding:8px 10px;cursor:pointer;border-radius:8px}
.password-toggle:hover{background:#f8f0df}

.auth-button{width:100%;margin-top:8px;padding:15px;border:0;border-radius:10px;font-size:15px;font-weight:700;color:var(--ink);cursor:pointer;background:linear-gradient(135deg,var(--gold-2),var(--gold));box-shadow:0 10px 26px rgba(214,173,98,.35);transition:transform .25s,box-shadow .25s,opacity .25s}
.auth-button:hover{transform:translateY(-2px);box-shadow:0 16px 36px rgba(214,173,98,.5)}
.auth-button:disabled{opacity:.7;cursor:not-allowed;transform:none}
.auth-footer{margin-top:24px;padding-top:22px;border-top:1px solid var(--line);text-align:center;font-size:14px;color:var(--muted)}
.auth-footer a{color:var(--gold-d);font-weight:700;margin-left:4px}
.auth-footer a:hover{text-decoration:underline}

/* intl-tel-input */
.iti{width:100%}
.iti input{width:100%}

/* FOOTER (same as home) */
.footer{background:#08101f;color:#fff;padding:70px 0 25px}
.footer-grid{display:grid;grid-template-columns:1.5fr 1fr 1fr 1fr;gap:45px;padding-bottom:50px}
.footer-brand img{height:56px;width:auto;object-fit:contain}
.footer-brand p{max-width:290px;margin-top:18px;color:#9fadc2;font-size:14px;line-height:1.85}
.footer-column h4{font-size:15px;margin-bottom:20px}
.footer-column a{display:block;color:#9fadc2;font-size:14px;margin-bottom:13px;transition:color .25s}
.footer-column a:hover{color:var(--gold)}
.footer-bottom{padding-top:24px;border-top:1px solid rgba(255,255,255,.09);display:flex;justify-content:space-between;gap:20px;color:#7f8ca1;font-size:13px}
.footer-legal{display:flex;gap:20px}
.footer-legal a:hover{color:var(--gold)}

/* RESPONSIVE */
@media(max-width:960px){
  .auth-wrap{grid-template-columns:minmax(0,520px);justify-content:center;gap:0}
  .auth-intro{display:none}
  .cube{display:none}
  .footer-grid{grid-template-columns:1fr 1fr}
}
@media(max-width:768px){
  .navbar{height:70px}
  .menu-toggle{display:block}
  .nav-menu{display:none;position:absolute;top:70px;left:0;right:0;background:#0b1428;padding:16px 22px;border-bottom:1px solid rgba(255,255,255,.08);flex-direction:column;align-items:stretch;gap:0}
  .nav-menu.active{display:flex}
  .nav-menu a{padding:14px 4px;font-size:15px}
  .nav-actions .btn{padding:10px 14px;font-size:13px}
  .auth-main{padding:40px 0 60px}
}
@media(max-width:520px){
  .container{width:min(100% - 30px,1160px)}
  .nav-actions .btn-ghost{display:none}
  .auth-card{padding:30px 22px;border-radius:18px}
  .footer-grid{grid-template-columns:1fr 1fr;gap:35px 20px}
  .footer-brand{grid-column:1/-1}
  .footer-bottom{flex-direction:column}
}
@media(prefers-reduced-motion:reduce){
  *,*::before,*::after{animation:none!important;transition-duration:.01ms!important}
}
</style>

@stack('styles')
</head>

<body>

{{-- HEADER --}}
<header class="site-header">
  <div class="container navbar">
    <a href="{{ route('home') }}" class="brand">
      <img src="{{ asset('images/dhakaiya-logo.png') }}" alt="Dhakaiya Investments">
    </a>

    <nav class="nav-menu" id="navMenu" aria-label="Main">
      <a href="{{ route('home') }}#home">Home</a>
      <a href="{{ route('home') }}#about">About us</a>
      <a href="{{ route('home') }}#platform">Platform</a>
      <a href="{{ route('home') }}#how-it-works">How it works</a>
      <a href="{{ route('home') }}#faq">FAQ</a>
    </nav>

    <div class="nav-actions">
      @auth
        <a href="{{ route('dashboard') }}" class="btn btn-gold">Dashboard</a>
      @else
        <a href="{{ route('login') }}" class="btn btn-ghost {{ request()->routeIs('login') ? 'current' : '' }}">Login</a>
        @if(Route::has('register'))
          <a href="{{ route('register') }}" class="btn btn-gold">Register</a>
        @endif
      @endauth

      <button class="menu-toggle" id="menuToggle" aria-label="Toggle navigation" aria-expanded="false">☰</button>
    </div>
  </div>
</header>

{{-- MAIN --}}
<main class="auth-main">
  <div class="auth-grid-bg"></div>

  <div class="deco" aria-hidden="true">
    <div class="ring"></div>
    <div class="ring r2"></div>
    <div class="cube"><i>✦</i><i>✦</i><i>✦</i><i>✦</i><i>✦</i><i>✦</i></div>
  </div>

  <div class="container auth-wrap">

    <div class="auth-intro">
      <h1>Your account, <span class="grad">one secure place.</span></h1>
      <p>Sign in to manage your account information, notifications and support from a single organized platform.</p>

      <ul class="auth-points">
        <li><span>✓</span> Password-protected account access</li>
        <li><span>✓</span> Works on desktop, tablet and mobile</li>
        <li><span>✓</span> Support available inside the platform</li>
      </ul>
    </div>

    <div class="auth-card">
      @yield('content')
    </div>

  </div>
</main>

{{-- FOOTER --}}
<footer class="footer">
  <div class="container">
    <div class="footer-grid">

      <div class="footer-brand">
        <a href="{{ route('home') }}">
          <img src="{{ asset('images/dhakaiya-logo.png') }}" alt="Dhakaiya Investments">
        </a>
        <p>A centralized digital platform for account information, platform services and user support.</p>
      </div>

      <div class="footer-column">
        <h4>Quick links</h4>
        <a href="{{ route('home') }}#home">Home</a>
        <a href="{{ route('home') }}#about">About us</a>
        <a href="{{ route('home') }}#platform">Platform</a>
        <a href="{{ route('home') }}#faq">FAQ</a>
      </div>

      <div class="footer-column">
        <h4>Account</h4>
        @guest
          <a href="{{ route('login') }}">Login</a>
          <a href="{{ route('register') }}">Register</a>
        @else
          <a href="{{ route('dashboard') }}">Dashboard</a>
        @endguest
        <a href="{{ route('home') }}#how-it-works">Getting started</a>
      </div>

      <div class="footer-column">
        <h4>Information</h4>
        <a href="{{ route('home') }}#about">About platform</a>
        <a href="{{ route('home') }}#faq">Help &amp; FAQ</a>
        <a href="{{ route('home') }}#platform">Services</a>
      </div>

    </div>

    <div class="footer-bottom">
      <p>© <span id="currentYear"></span> Dhakaiya Investments. All rights reserved.</p>
      <div class="footer-legal">
        <a href="{{ route('home') }}#about">About</a>
        <a href="{{ route('home') }}#faq">FAQ</a>
      </div>
    </div>
  </div>
</footer>

<script>
  (function () {
    var toggle = document.getElementById('menuToggle');
    var menu = document.getElementById('navMenu');

    function closeMenu() {
      menu.classList.remove('active');
      toggle.textContent = '☰';
      toggle.setAttribute('aria-expanded', 'false');
    }

    toggle.addEventListener('click', function () {
      var open = menu.classList.toggle('active');
      toggle.textContent = open ? '✕' : '☰';
      toggle.setAttribute('aria-expanded', open);
    });

    menu.querySelectorAll('a').forEach(function (a) {
      a.addEventListener('click', closeMenu);
    });

    document.getElementById('currentYear').textContent = new Date().getFullYear();
  })();
</script>

@stack('scripts')
</body>
</html>