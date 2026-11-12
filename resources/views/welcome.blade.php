<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dhakaiya Investments | Welcome</title>
<meta name="description" content="Welcome to Dhakaiya Investments. Explore our platform, account services and user support.">
<meta name="theme-color" content="#0b1428">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;700;800&display=swap" rel="stylesheet">

<style>
:root{
  --ink:#0b1428; --navy:#101c35; --navy-2:#172844; --gold:#d6ad62; --gold-2:#f0d49c; --gold-d:#a77a31;
  --text:#172033; --muted:#667085; --bg:#f6f7fb; --line:#e6e9f0; --white:#fff;
  --shadow:0 20px 60px rgba(16,28,53,.10);
}
*{margin:0;padding:0;box-sizing:border-box}
html{scroll-behavior:smooth;scroll-padding-top:90px}
body{font-family:'DM Sans',sans-serif;color:var(--text);background:#fff;overflow-x:hidden;-webkit-font-smoothing:antialiased}
a{text-decoration:none;color:inherit}
button{font:inherit}
:focus-visible{outline:2px solid var(--gold);outline-offset:3px}
.container{width:min(1160px,calc(100% - 40px));margin:auto}
h1,h2,h3,h4{font-family:'Manrope',sans-serif}

/* HEADER */
.site-header{position:sticky;top:0;z-index:1000;background:rgb(22 33 54);backdrop-filter:blur(16px) saturate(140%);border-bottom:1px solid rgba(255,255,255,.07);transition:background .3s}
.site-header.scrolled{background:rgba(11,20,40,.94)}
.navbar{height:78px;display:flex;align-items:center;justify-content:space-between}
.brand img{height:50px;width:auto;object-fit:contain}
.nav-menu{display:flex;align-items:center;gap:32px}
.nav-menu a{font-size:14px;font-weight:500;color:#c3ccdb;transition:color .25s}
.nav-menu a:hover{color:var(--gold-2)}
.nav-actions{display:flex;align-items:center;gap:12px}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:9px;padding:13px 24px;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;border:1px solid transparent;transition:transform .25s,box-shadow .25s,background .25s,border-color .25s,color .25s}
.btn-gold{color:var(--ink);background:linear-gradient(135deg,var(--gold-2),var(--gold));box-shadow:0 8px 24px rgba(214,173,98,.28)}
.btn-gold:hover{transform:translateY(-2px);box-shadow:0 14px 34px rgba(214,173,98,.42)}
.btn-ghost{color:#fff;border-color:rgba(255,255,255,.25);background:rgba(255,255,255,.04)}
.btn-ghost:hover{border-color:var(--gold);color:var(--gold-2)}
.menu-toggle{display:none;border:0;background:transparent;color:#fff;font-size:26px;cursor:pointer}

/* HERO */
.hero{position:relative;overflow:hidden;color:#fff;padding:90px 0 110px;
  background:radial-gradient(900px 500px at 85% 20%,rgba(214,173,98,.16),transparent 60%),radial-gradient(700px 500px at 0% 100%,rgba(60,100,170,.22),transparent 60%),linear-gradient(160deg,#0b1428 0%,#122240 60%,#17304f 100%)}
.hero-grid-bg{position:absolute;inset:0;opacity:.5;background-image:linear-gradient(rgba(255,255,255,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.04) 1px,transparent 1px);background-size:56px 56px;-webkit-mask-image:radial-gradient(ellipse at 70% 40%,#000 10%,transparent 70%);mask-image:radial-gradient(ellipse at 70% 40%,#000 10%,transparent 70%)}
.hero-grid{position:relative;z-index:2;display:grid;grid-template-columns:1fr 1.02fr;align-items:center;gap:50px}
.hero-badge{display:inline-flex;align-items:center;gap:9px;padding:8px 15px;border:1px solid rgba(214,173,98,.35);border-radius:50px;background:rgba(214,173,98,.08);color:var(--gold-2);font-size:13px;font-weight:600;margin-bottom:26px}
.hero-badge i{width:7px;height:7px;background:var(--gold);border-radius:50%;box-shadow:0 0 0 0 rgba(214,173,98,.7);animation:pulse 2s infinite}
@keyframes pulse{70%{box-shadow:0 0 0 10px rgba(214,173,98,0)}100%{box-shadow:0 0 0 0 rgba(214,173,98,0)}}
.hero h1{font-size:clamp(38px,5vw,60px);line-height:1.1;font-weight:800;letter-spacing:-1.8px;max-width:620px}
.hero h1 .grad{background:linear-gradient(100deg,var(--gold-2),var(--gold) 60%,#b98a3f);-webkit-background-clip:text;background-clip:text;color:transparent}
.hero-description{color:#b7c2d4;font-size:17px;line-height:1.8;max-width:500px;margin-top:24px}
.hero-buttons{display:flex;flex-wrap:wrap;gap:14px;margin-top:34px}
.hero-trust{display:flex;flex-wrap:wrap;gap:22px;margin-top:38px;color:#98a6bb;font-size:13px}
.hero-trust span{display:flex;align-items:center;gap:8px}
.hero-trust b{color:var(--gold);font-weight:700}

/* 3D STAGE */
.stage{position:relative;height:480px;perspective:1400px;display:flex;align-items:center;justify-content:center}
.scene{position:relative;width:360px;height:420px;transform-style:preserve-3d;transform:rotateX(var(--rx,8deg)) rotateY(var(--ry,-16deg));transition:transform .15s ease-out;animation:sway 9s ease-in-out infinite}
.scene.is-active{animation:none}
@keyframes sway{50%{transform:rotateX(2deg) rotateY(-4deg)}}
.layer{position:absolute;transform-style:preserve-3d}
.ring{inset:20px -30px;border:1px solid rgba(214,173,98,.35);border-radius:50%;transform:translateZ(-80px) rotateX(70deg);animation:spin 18s linear infinite}
.ring.r2{inset:60px 0;border-style:dashed;border-color:rgba(214,173,98,.22);transform:translateZ(-80px) rotateX(70deg);animation-duration:26s;animation-direction:reverse}
@keyframes spin{to{transform:translateZ(-80px) rotateX(70deg) rotateZ(360deg)}}
.glass{background:rgba(255,255,255,.96);color:var(--navy);border-radius:20px;box-shadow:0 40px 80px rgba(0,0,0,.35),0 0 0 1px rgba(255,255,255,.5) inset}
.main-card{left:0;top:30px;width:340px;padding:26px;transform:translateZ(0)}
.mc-head{display:flex;justify-content:space-between;align-items:center;padding-bottom:18px;border-bottom:1px solid var(--line)}
.mc-head strong{font-size:14px}
.tag{padding:5px 10px;border-radius:20px;background:#eaf5ec;color:#3f7a4c;font-size:11px;font-weight:700}
.mc-hello{margin-top:22px;font-size:12px;color:var(--muted)}
.main-card h3{font-size:24px;margin-top:4px}
.bars{display:flex;align-items:flex-end;gap:8px;height:70px;margin:22px 0 4px}
.bars span{flex:1;border-radius:6px 6px 2px 2px;background:linear-gradient(180deg,var(--gold),#ecd4a2);height:var(--h);transform-origin:bottom;animation:grow 1.4s cubic-bezier(.2,.8,.2,1) both}
.bars span:nth-child(even){background:linear-gradient(180deg,#27406a,#3b5a8c)}
@keyframes grow{from{transform:scaleY(.1)}}
.mc-cap{font-size:11px;color:var(--muted)}
.opts{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:18px}
.opt{background:#f3f5f9;border-radius:10px;padding:12px;font-size:12px;font-weight:700}
.opt small{display:block;font-weight:500;color:var(--muted);font-size:10.5px;margin-top:3px}
.float-a{right:-70px;top:0;padding:14px 18px;font-size:13px;font-weight:700;transform:translateZ(90px);animation:bob 5s ease-in-out infinite}
.float-b{left:-60px;bottom:30px;padding:14px 18px;font-size:13px;font-weight:700;transform:translateZ(130px);animation:bob 6s ease-in-out -2s infinite}
.float-a span,.float-b span{color:var(--gold-d);margin-right:6px}
@keyframes bob{50%{translate:0 -12px}}
.cube{right:-20px;bottom:-10px;width:70px;height:70px;transform:translateZ(150px);animation:cube 14s linear infinite}
.cube i{position:absolute;inset:0;display:grid;place-items:center;font-style:normal;font-size:28px;color:var(--ink);background:linear-gradient(135deg,var(--gold-2),var(--gold));border:1px solid rgba(255,255,255,.5);backface-visibility:visible}
.cube i:nth-child(1){transform:translateZ(35px)}
.cube i:nth-child(2){transform:rotateY(180deg) translateZ(35px)}
.cube i:nth-child(3){transform:rotateY(90deg) translateZ(35px)}
.cube i:nth-child(4){transform:rotateY(-90deg) translateZ(35px)}
.cube i:nth-child(5){transform:rotateX(90deg) translateZ(35px)}
.cube i:nth-child(6){transform:rotateX(-90deg) translateZ(35px)}
@keyframes cube{to{transform:translateZ(150px) rotateX(360deg) rotateY(360deg)}}

/* SECTIONS */
.section{padding:100px 0}
.section-heading{max-width:660px;margin:0 auto 56px;text-align:center}
.section-label{display:inline-block;color:var(--gold-d);font-size:13px;font-weight:700;margin-bottom:12px}
.section-heading h2{font-size:clamp(28px,4vw,40px);line-height:1.2;color:var(--navy);font-weight:800;letter-spacing:-.8px}
.section-heading p{color:var(--muted);font-size:16px;line-height:1.75;margin-top:16px}

/* ABOUT */
.about-grid{display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:center}
.about-visual{min-height:400px;border-radius:24px;background:linear-gradient(140deg,#0f1d38,#26416a);position:relative;overflow:hidden;display:grid;place-items:center;perspective:900px}
.about-visual::before{content:"";position:absolute;width:340px;height:340px;border:1px solid rgba(214,173,98,.25);border-radius:50%}
.about-visual::after{content:"";position:absolute;width:250px;height:250px;border:1px dashed rgba(214,173,98,.25);border-radius:50%}
.emblem{position:relative;z-index:2;text-align:center;color:var(--gold);transform-style:preserve-3d;animation:flip 10s ease-in-out infinite}
@keyframes flip{50%{transform:rotateY(180deg)}}
.emblem .sym{font-size:80px;line-height:1;backface-visibility:hidden;display:block}
.emblem-text{position:absolute;z-index:3;bottom:34px;left:0;right:0;text-align:center;color:#fff}
.emblem-text h3{font-size:21px}
.emblem-text p{color:#aebbd0;font-size:12px;margin-top:6px}
.about-content h2{color:var(--navy);font-size:clamp(28px,3.4vw,36px);line-height:1.25;margin-bottom:20px;letter-spacing:-.6px}
.about-content>p{color:var(--muted);font-size:16px;line-height:1.85;margin-bottom:16px}
.about-points{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:28px}
.about-point{display:flex;align-items:center;gap:10px;font-size:14px;font-weight:600;color:var(--navy)}
.about-point span{width:26px;height:26px;flex:none;display:grid;place-items:center;background:#f8f0df;color:var(--gold-d);border-radius:50%;font-size:13px}

/* PLATFORM */
.platform{background:var(--bg)}
.feature-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;perspective:1200px}
.feature-card{position:relative;padding:32px 28px;background:#fff;border:1px solid var(--line);border-radius:18px;transform-style:preserve-3d;transition:transform .2s ease-out,box-shadow .3s,border-color .3s;will-change:transform}
.feature-card:hover{box-shadow:var(--shadow);border-color:rgba(214,173,98,.55)}
.feature-icon{width:56px;height:56px;display:grid;place-items:center;background:linear-gradient(135deg,#fbf3e3,#f3e2bd);border-radius:14px;font-size:25px;margin-bottom:22px;transform:translateZ(30px)}
.feature-card h3{color:var(--navy);font-size:18px;margin-bottom:10px;transform:translateZ(20px)}
.feature-card p{color:var(--muted);font-size:14px;line-height:1.75;transform:translateZ(10px)}

/* STEPS */
.step-grid{position:relative;display:grid;grid-template-columns:repeat(3,1fr);gap:36px}
.step-grid::before{content:"";position:absolute;top:57px;left:17%;right:17%;border-top:2px dashed #e1d3b3}
.step-card{position:relative;text-align:center;padding:25px}
.step-number{width:66px;height:66px;display:grid;place-items:center;margin:0 auto 24px;border-radius:50%;background:var(--navy);color:var(--gold);font-family:'Manrope',sans-serif;font-size:22px;font-weight:800;box-shadow:0 0 0 8px #fff,0 14px 30px rgba(16,28,53,.25)}
.step-card h3{color:var(--navy);font-size:19px;margin-bottom:10px}
.step-card p{color:var(--muted);font-size:14px;line-height:1.75;max-width:290px;margin:auto}

/* CTA */
.cta-section{padding:20px 0 100px}
.cta-box{position:relative;overflow:hidden;display:flex;align-items:center;justify-content:space-between;gap:30px;padding:60px;border-radius:26px;color:#fff;background:radial-gradient(500px 300px at 90% 0%,rgba(214,173,98,.25),transparent 60%),linear-gradient(115deg,#0b1428,#1b3254)}
.cta-box::after{content:"";position:absolute;width:300px;height:300px;border:1px solid rgba(214,173,98,.2);border-radius:50%;right:18%;top:-170px}
.cta-content{position:relative;z-index:2;max-width:600px}
.cta-content h2{font-size:clamp(26px,3vw,34px);line-height:1.25;letter-spacing:-.6px}
.cta-content p{color:#bfcadb;font-size:15px;line-height:1.8;margin-top:12px}
.cta-box .btn{position:relative;z-index:2;flex-shrink:0}

/* FAQ */
.faq{background:var(--bg)}
.faq-list{max-width:820px;margin:0 auto}
.faq-item{background:#fff;border:1px solid var(--line);border-radius:14px;margin-bottom:12px;transition:border-color .25s,box-shadow .25s}
.faq-item.active{border-color:rgba(214,173,98,.6);box-shadow:var(--shadow)}
.faq-question{width:100%;display:flex;align-items:center;justify-content:space-between;gap:20px;padding:22px 24px;background:transparent;border:0;color:var(--navy);font-size:16px;font-weight:600;text-align:left;cursor:pointer}
.faq-icon{width:30px;height:30px;flex:none;display:grid;place-items:center;border-radius:50%;background:#f3f4f7;color:var(--gold-d);font-size:20px;transition:transform .3s,background .3s,color .3s}
.faq-item.active .faq-icon{background:var(--gold);color:#fff;transform:rotate(45deg)}
.faq-answer{display:grid;grid-template-rows:0fr;transition:grid-template-rows .35s ease}
.faq-answer>div{overflow:hidden;padding:0 24px;color:var(--muted);font-size:15px;line-height:1.8}
.faq-item.active .faq-answer{grid-template-rows:1fr}
.faq-item.active .faq-answer>div{padding-bottom:22px}

/* FOOTER */
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

/* SCROLL REVEAL */
.js .reveal{opacity:0;transform:translateY(26px);transition:opacity .7s ease,transform .7s ease}
.js .reveal.in{opacity:1;transform:none}

/* RESPONSIVE */
@media(max-width:1000px){
  .hero-grid{grid-template-columns:1fr;gap:20px}
  .stage{height:460px}
  .footer-grid{grid-template-columns:1fr 1fr}
  .feature-grid{grid-template-columns:repeat(2,1fr)}
}
@media(max-width:768px){
  .navbar{height:70px}
  .menu-toggle{display:block}
  .nav-menu{display:none;position:absolute;top:70px;left:0;right:0;background:#0b1428;padding:16px 22px;border-bottom:1px solid rgba(255,255,255,.08);flex-direction:column;align-items:stretch;gap:0}
  .nav-menu.active{display:flex}
  .nav-menu a{padding:14px 4px;font-size:15px}
  .nav-actions .btn{padding:10px 14px;font-size:13px}
  .hero{padding:60px 0 70px}
  .about-grid{grid-template-columns:1fr;gap:40px}
  .step-grid{grid-template-columns:1fr;gap:10px}
  .step-grid::before{display:none}
  .cta-box{padding:40px 28px;flex-direction:column;align-items:flex-start}
  .section{padding:75px 0}
}
@media(max-width:520px){
  .container{width:min(100% - 30px,1160px)}
  .nav-actions .btn-ghost{display:none}
  .hero h1{letter-spacing:-1.1px}
  .hero-description{font-size:15px}
  .stage{height:430px}
  .scene{transform:scale(.8) rotateX(var(--rx,8deg)) rotateY(var(--ry,-16deg))}
  .float-a{right:-20px}.float-b{left:-20px}
  .feature-grid{grid-template-columns:1fr}
  .about-visual{min-height:320px}
  .about-points{grid-template-columns:1fr}
  .footer-grid{grid-template-columns:1fr 1fr;gap:35px 20px}
  .footer-brand{grid-column:1/-1}
  .footer-bottom{flex-direction:column}
}
@media(prefers-reduced-motion:reduce){
  *,*::before,*::after{animation:none!important;transition-duration:.01ms!important;scroll-behavior:auto!important}
  .js .reveal{opacity:1;transform:none}
}
</style>
</head>

<body>
<script>document.documentElement.classList.add('js');</script>

{{-- HEADER --}}
<header class="site-header" id="siteHeader">
  <div class="container navbar">
    <a href="{{ route('home') }}" class="brand">
      <img src="{{ asset('images/dhakaiya-logo.png') }}" alt="Dhakaiya Investments">
    </a>

    <nav class="nav-menu" id="navMenu" aria-label="Main">
      <a href="#home">Home</a>
      <a href="#about">About us</a>
      <a href="#platform">Platform</a>
      <a href="#how-it-works">How it works</a>
      <a href="#faq">FAQ</a>
    </nav>

    <div class="nav-actions">
      @auth
        <a href="{{ route('dashboard') }}" class="btn btn-gold">Dashboard</a>
      @else
        <a href="{{ route('login') }}" class="btn btn-ghost">Login</a>
        @if(Route::has('register'))
          <a href="{{ route('register') }}" class="btn btn-gold">Register</a>
        @endif
      @endauth

      <button class="menu-toggle" id="menuToggle" aria-label="Toggle navigation" aria-expanded="false">☰</button>
    </div>
  </div>
</header>

{{-- HERO --}}
<section class="hero" id="home">
  <div class="hero-grid-bg"></div>
  <div class="container hero-grid">

    <div class="hero-content">
      <div class="hero-badge"><i></i> Welcome to Dhakaiya Investments</div>

      <h1>One secure place for your <span class="grad">account and services.</span></h1>

      <p class="hero-description">
        Access your account information, personal settings and support from a
        single, organized platform built for clarity on every device.
      </p>

      <div class="hero-buttons">
        @guest
          <a href="{{ route('register') }}" class="btn btn-gold">Create your account</a>
          <a href="{{ route('login') }}" class="btn btn-ghost">Sign in</a>
        @else
          <a href="{{ route('dashboard') }}" class="btn btn-gold">Go to dashboard</a>
        @endguest
      </div>

      <div class="hero-trust">
        <span><b>✓</b> Password-protected access</span>
        <span><b>✓</b> Desktop, tablet &amp; mobile</span>
        <span><b>✓</b> In-platform support</span>
      </div>
    </div>

    {{-- 3D VISUAL --}}
    <div class="stage" id="stage" aria-hidden="true">
      <div class="scene" id="scene">
        <div class="layer ring"></div>
        <div class="layer ring r2"></div>

        <div class="layer glass main-card">
          <div class="mc-head">
            <strong>Dhakaiya Investments</strong>
            <span class="tag">Platform overview</span>
          </div>
          <div class="mc-hello">Your digital space</div>
          <h3>Welcome back!</h3>
          <div class="bars">
            <span style="--h:40%"></span><span style="--h:62%"></span><span style="--h:48%"></span>
            <span style="--h:78%"></span><span style="--h:58%"></span><span style="--h:92%"></span>
          </div>
          <div class="mc-cap">Account activity overview</div>
          <div class="opts">
            <div class="opt">👤 My account<small>Personal information</small></div>
            <div class="opt">🌍 Country<small>Regional settings</small></div>
            <div class="opt">🔔 Notifications<small>Important updates</small></div>
            <div class="opt">💬 Support<small>Help and assistance</small></div>
          </div>
        </div>

        <div class="layer glass float-a"><span>🛡️</span>Account security</div>
        <div class="layer glass float-b"><span>🔔</span>New notification</div>

        <div class="layer cube">
          <i>✦</i><i>✦</i><i>✦</i><i>✦</i><i>✦</i><i>✦</i>
        </div>
      </div>
    </div>

  </div>
</section>

{{-- ABOUT --}}
<section class="section about" id="about">
  <div class="container about-grid">

    <div class="about-visual reveal">
      <div class="emblem"><span class="sym">✦</span></div>
      <div class="emblem-text">
        <h3>Dhakaiya Investments</h3>
        <p>Information &amp; account platform</p>
      </div>
    </div>

    <div class="about-content reveal">
      <span class="section-label">About our platform</span>
      <h2>A central place for your digital account experience</h2>
      <p>
        Dhakaiya Investments provides a centralized digital environment where
        registered users can access their account information and platform services.
      </p>
      <p>
        The interface is designed to keep navigation straightforward, organize
        essential information and give convenient access to account-related services.
      </p>

      <div class="about-points">
        <div class="about-point"><span>✓</span>Organized account access</div>
        <div class="about-point"><span>✓</span>Responsive interface</div>
        <div class="about-point"><span>✓</span>User support</div>
        <div class="about-point"><span>✓</span>Account information</div>
      </div>
    </div>

  </div>
</section>

{{-- PLATFORM --}}
<section class="section platform" id="platform">
  <div class="container">

    <div class="section-heading reveal">
      <span class="section-label">Platform overview</span>
      <h2>Everything organized in one place</h2>
      <p>Explore the main areas of the Dhakaiya Investments digital platform.</p>
    </div>

    <div class="feature-grid">
      <div class="feature-card reveal" data-tilt>
        <div class="feature-icon">👤</div>
        <h3>Account management</h3>
        <p>Access your profile and manage your personal information through a centralized interface.</p>
      </div>
      <div class="feature-card reveal" data-tilt>
        <div class="feature-icon">🌍</div>
        <h3>Country information</h3>
        <p>View your registered country and related regional account preferences.</p>
      </div>
      <div class="feature-card reveal" data-tilt>
        <div class="feature-icon">🔔</div>
        <h3>Notifications</h3>
        <p>Keep track of platform announcements, account updates and important notices.</p>
      </div>
      <div class="feature-card reveal" data-tilt>
        <div class="feature-icon">🛡️</div>
        <h3>Account security</h3>
        <p>Access available account settings and password management options.</p>
      </div>
      <div class="feature-card reveal" data-tilt>
        <div class="feature-icon">📊</div>
        <h3>Information dashboard</h3>
        <p>Navigate your available account sections through a structured dashboard.</p>
      </div>
      <div class="feature-card reveal" data-tilt>
        <div class="feature-icon">💬</div>
        <h3>Support services</h3>
        <p>Find platform assistance and access available support information.</p>
      </div>
    </div>

  </div>
</section>

{{-- HOW IT WORKS --}}
<section class="section steps" id="how-it-works">
  <div class="container">

    <div class="section-heading reveal">
      <span class="section-label">Getting started</span>
      <h2>Get started in three steps</h2>
      <p>Follow these steps to access your Dhakaiya Investments account.</p>
    </div>

    <div class="step-grid">
      <div class="step-card reveal">
        <div class="step-number">1</div>
        <h3>Create your account</h3>
        <p>Complete the registration form with your required account information.</p>
      </div>
      <div class="step-card reveal">
        <div class="step-number">2</div>
        <h3>Sign in securely</h3>
        <p>Use your registered phone number and password to access your account.</p>
      </div>
      <div class="step-card reveal">
        <div class="step-number">3</div>
        <h3>Explore your dashboard</h3>
        <p>Open your account sections, information and support services.</p>
      </div>
    </div>

  </div>
</section>

{{-- CTA --}}
<section class="cta-section">
  <div class="container">
    <div class="cta-box reveal">
      <div class="cta-content">
        <h2>Your account, your digital space.</h2>
        <p>Access the Dhakaiya Investments platform and explore its available account services.</p>
      </div>

      @guest
        <a href="{{ route('register') }}" class="btn btn-gold">Create your account</a>
      @else
        <a href="{{ route('dashboard') }}" class="btn btn-gold">Visit dashboard</a>
      @endguest
    </div>
  </div>
</section>

{{-- FAQ --}}
<section class="section faq" id="faq">
  <div class="container">

    <div class="section-heading reveal">
      <span class="section-label">FAQ</span>
      <h2>Common questions</h2>
      <p>Answers to some common questions about accessing the platform.</p>
    </div>

    <div class="faq-list">
      <div class="faq-item">
        <button class="faq-question" aria-expanded="false">
          <span>What is Dhakaiya Investments?</span><span class="faq-icon">+</span>
        </button>
        <div class="faq-answer"><div>Dhakaiya Investments is a digital platform that gives registered users access to account information and available platform services.</div></div>
      </div>

      <div class="faq-item">
        <button class="faq-question" aria-expanded="false">
          <span>How can I create an account?</span><span class="faq-icon">+</span>
        </button>
        <div class="faq-answer"><div>Select Register and complete the registration form with the required information.</div></div>
      </div>

      <div class="faq-item">
        <button class="faq-question" aria-expanded="false">
          <span>How can I access my account?</span><span class="faq-icon">+</span>
        </button>
        <div class="faq-answer"><div>Go to the Login page and enter your registered phone number and password.</div></div>
      </div>

      <div class="faq-item">
        <button class="faq-question" aria-expanded="false">
          <span>Can I access the platform from mobile?</span><span class="faq-icon">+</span>
        </button>
        <div class="faq-answer"><div>Yes. The website is responsive and works across supported desktop, tablet and mobile browsers.</div></div>
      </div>

      <div class="faq-item">
        <button class="faq-question" aria-expanded="false">
          <span>Where can I get assistance?</span><span class="faq-icon">+</span>
        </button>
        <div class="faq-answer"><div>Registered users can open the support section after signing in.</div></div>
      </div>
    </div>

  </div>
</section>

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
        <a href="#home">Home</a>
        <a href="#about">About us</a>
        <a href="#platform">Platform</a>
        <a href="#faq">FAQ</a>
      </div>

      <div class="footer-column">
        <h4>Account</h4>
        @guest
          <a href="{{ route('login') }}">Login</a>
          <a href="{{ route('register') }}">Register</a>
        @else
          <a href="{{ route('dashboard') }}">Dashboard</a>
        @endguest
        <a href="#how-it-works">Getting started</a>
      </div>

      <div class="footer-column">
        <h4>Information</h4>
        <a href="#about">About platform</a>
        <a href="#faq">Help &amp; FAQ</a>
        <a href="#platform">Services</a>
      </div>

    </div>

    <div class="footer-bottom">
      <p>© <span id="currentYear"></span> Dhakaiya Investments. All rights reserved.</p>
      <div class="footer-legal">
        <a href="#about">About</a>
        <a href="#faq">FAQ</a>
      </div>
    </div>
  </div>
</footer>

<script>
(function () {
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // Header background on scroll
  var header = document.getElementById('siteHeader');
  function onScroll(){ header.classList.toggle('scrolled', window.scrollY > 20); }
  onScroll(); window.addEventListener('scroll', onScroll, { passive: true });

  // Mobile menu
  var toggle = document.getElementById('menuToggle');
  var menu = document.getElementById('navMenu');
  function closeMenu(){ menu.classList.remove('active'); toggle.textContent = '☰'; toggle.setAttribute('aria-expanded','false'); }
  toggle.addEventListener('click', function () {
    var open = menu.classList.toggle('active');
    toggle.textContent = open ? '✕' : '☰';
    toggle.setAttribute('aria-expanded', open);
  });
  menu.querySelectorAll('a').forEach(function (a) { a.addEventListener('click', closeMenu); });

  // FAQ accordion
  document.querySelectorAll('.faq-question').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var item = btn.parentElement, willOpen = !item.classList.contains('active');
      document.querySelectorAll('.faq-item').forEach(function (i) {
        i.classList.remove('active');
        i.querySelector('.faq-question').setAttribute('aria-expanded','false');
      });
      if (willOpen) { item.classList.add('active'); btn.setAttribute('aria-expanded','true'); }
    });
  });

  // Scroll reveal
  var items = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window && !reduce) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); }
      });
    }, { threshold: .15 });
    items.forEach(function (el, i) { el.style.transitionDelay = (i % 3) * 90 + 'ms'; io.observe(el); });
  } else { items.forEach(function (el) { el.classList.add('in'); }); }

  if (reduce) { document.getElementById('currentYear').textContent = new Date().getFullYear(); return; }

  // Hero 3D parallax (follows mouse)
  var stage = document.getElementById('stage'), scene = document.getElementById('scene'), raf;
  stage.parentElement.addEventListener('mousemove', function (e) {
    var r = stage.getBoundingClientRect();
    var x = (e.clientX - (r.left + r.width / 2)) / r.width;
    var y = (e.clientY - (r.top + r.height / 2)) / r.height;
    cancelAnimationFrame(raf);
    raf = requestAnimationFrame(function () {
      scene.classList.add('is-active');
      scene.style.setProperty('--ry', (-16 + x * 26) + 'deg');
      scene.style.setProperty('--rx', (8 - y * 20) + 'deg');
    });
  });
  stage.parentElement.addEventListener('mouseleave', function () {
    scene.classList.remove('is-active');
    scene.style.removeProperty('--ry'); scene.style.removeProperty('--rx');
  });

  // Feature card 3D tilt (desktop pointers only)
  if (window.matchMedia('(hover: hover)').matches) {
    document.querySelectorAll('[data-tilt]').forEach(function (card) {
      card.addEventListener('mousemove', function (e) {
        var r = card.getBoundingClientRect();
        var x = (e.clientX - r.left) / r.width - .5, y = (e.clientY - r.top) / r.height - .5;
        card.style.transform = 'perspective(900px) rotateX(' + (-y * 10) + 'deg) rotateY(' + (x * 12) + 'deg) translateY(-6px)';
      });
      card.addEventListener('mouseleave', function () { card.style.transform = ''; });
    });
  }

  document.getElementById('currentYear').textContent = new Date().getFullYear();
})();
</script>

</body>
</html>