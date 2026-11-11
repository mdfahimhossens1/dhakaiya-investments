<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dhakaiya Investments | Welcome</title>

    <meta name="description"
          content="Welcome to Dhakaiya Investments. Explore our platform, account services and user support.">

    <meta name="theme-color" content="#101c35">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">

    <style>
        :root {
            --navy: #101c35;
            --navy-light: #172844;
            --gold: #d6ad62;
            --gold-light: #f0d49c;
            --white: #ffffff;
            --text: #172033;
            --muted: #6b7280;
            --background: #f8f9fc;
            --border: #e8ebf1;
            --shadow: 0 15px 45px rgba(16, 28, 53, .08);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 90px;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            color: var(--text);
            background: var(--white);
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button {
            font: inherit;
        }

        .container {
            width: min(1160px, calc(100% - 40px));
            margin: auto;
        }

        /* HEADER */

        .site-header {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(255,255,255,.96);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(232,235,241,.8);
        }

        .navbar {
            height: 82px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand img {
            width: 58px;
            height: 58px;
            object-fit: contain;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 32px;
        }

        .nav-menu a {
            font-size: 14px;
            font-weight: 600;
            color: #465066;
            transition: .25s;
        }

        .nav-menu a:hover {
            color: var(--gold);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 13px 23px;
            border-radius: 7px;
            font-size: 14px;
            font-weight: 700;
            transition: .25s;
            cursor: pointer;
            border: 1px solid transparent;
        }

        .btn-outline {
            color: var(--navy);
            border-color: #dce1e9;
            background: white;
        }

        .btn-outline:hover {
            border-color: var(--gold);
            color: #a77a31;
        }

        .btn-gold {
            color: var(--navy);
            background: var(--gold);
        }

        .btn-gold:hover {
            background: var(--gold-light);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(214,173,98,.2);
        }

        .menu-toggle {
            display: none;
            border: 0;
            background: transparent;
            color: var(--navy);
            font-size: 27px;
            cursor: pointer;
        }

        /* HERO */

        .hero {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at 80% 25%, rgba(214,173,98,.15), transparent 28%),
                linear-gradient(125deg, #101c35 0%, #172844 65%, #203653 100%);
            color: white;
            padding: 105px 0 115px;
        }

        .hero::before {
            content: "";
            position: absolute;
            width: 500px;
            height: 500px;
            border: 1px solid rgba(214,173,98,.13);
            border-radius: 50%;
            right: -150px;
            top: -180px;
        }

        .hero::after {
            content: "";
            position: absolute;
            width: 350px;
            height: 350px;
            border: 1px solid rgba(214,173,98,.1);
            border-radius: 50%;
            right: -70px;
            top: -100px;
        }

        .hero-grid {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns: 1.05fr .95fr;
            align-items: center;
            gap: 70px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 9px 15px;
            border: 1px solid rgba(214,173,98,.35);
            border-radius: 50px;
            background: rgba(214,173,98,.08);
            color: var(--gold-light);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            margin-bottom: 27px;
        }

        .hero-badge span {
            width: 7px;
            height: 7px;
            background: var(--gold);
            border-radius: 50%;
        }

        .hero h1 {
            font-family: 'Manrope', sans-serif;
            font-size: clamp(40px, 5vw, 62px);
            line-height: 1.13;
            font-weight: 800;
            letter-spacing: -2px;
            max-width: 650px;
        }

        .hero h1 span {
            color: var(--gold);
        }

        .hero-description {
            color: #c3ccdb;
            font-size: 16px;
            line-height: 1.9;
            max-width: 520px;
            margin-top: 25px;
        }

        .hero-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 34px;
        }

        .btn-white {
            background: white;
            color: var(--navy);
        }

        .btn-white:hover {
            background: var(--gold-light);
            transform: translateY(-2px);
        }

        .btn-transparent {
            border-color: rgba(255,255,255,.3);
            color: white;
        }

        .btn-transparent:hover {
            border-color: var(--gold);
            color: var(--gold-light);
        }

        .hero-note {
            margin-top: 28px;
            color: #9daabd;
            font-size: 12px;
        }

        /* HERO VISUAL */

        .hero-visual {
            position: relative;
            min-height: 380px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .visual-orbit {
            position: absolute;
            width: 330px;
            height: 330px;
            border: 1px solid rgba(214,173,98,.25);
            border-radius: 50%;
        }

        .visual-orbit::before {
            content: "";
            position: absolute;
            inset: 30px;
            border: 1px dashed rgba(214,173,98,.25);
            border-radius: 50%;
        }

        .visual-card {
            position: relative;
            z-index: 2;
            width: 340px;
            padding: 28px;
            background: rgba(255,255,255,.97);
            color: var(--navy);
            border-radius: 18px;
            box-shadow: 0 30px 80px rgba(0,0,0,.25);
            transform: rotate(-3deg);
        }

        .visual-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border);
        }

        .visual-card-header strong {
            font-size: 15px;
        }

        .visual-status {
            padding: 6px 10px;
            border-radius: 20px;
            background: #eef5ef;
            color: #4b8056;
            font-size: 10px;
            font-weight: 700;
        }

        .visual-welcome {
            margin-top: 25px;
            color: var(--muted);
            font-size: 12px;
        }

        .visual-card h3 {
            font-family: 'Manrope', sans-serif;
            font-size: 25px;
            margin-top: 5px;
        }

        .visual-card p {
            font-size: 12px;
            color: var(--muted);
            margin-top: 6px;
        }

        .visual-divider {
            margin: 24px 0;
            border: 0;
            border-top: 1px solid var(--border);
        }

        .visual-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .visual-option {
            background: #f5f7fa;
            padding: 15px;
            border-radius: 10px;
        }

        .visual-option span {
            display: block;
            font-size: 22px;
            margin-bottom: 8px;
        }

        .visual-option strong {
            font-size: 12px;
        }

        .visual-option small {
            display: block;
            margin-top: 4px;
            color: var(--muted);
            font-size: 10px;
        }

        .floating-card {
            position: absolute;
            z-index: 3;
            right: -10px;
            bottom: 35px;
            background: white;
            color: var(--navy);
            padding: 14px 18px;
            border-radius: 10px;
            box-shadow: var(--shadow);
            font-size: 12px;
            font-weight: 700;
        }

        .floating-card span {
            color: #a77a31;
            margin-right: 7px;
        }

        /* SECTION COMMON */

        .section {
            padding: 95px 0;
        }

        .section-heading {
            max-width: 650px;
            margin: 0 auto 55px;
            text-align: center;
        }

        .section-label {
            display: inline-block;
            color: #a77a31;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .section-heading h2 {
            font-family: 'Manrope', sans-serif;
            font-size: clamp(29px, 4vw, 40px);
            line-height: 1.25;
            color: var(--navy);
            font-weight: 800;
        }

        .section-heading p {
            color: var(--muted);
            font-size: 15px;
            line-height: 1.8;
            margin-top: 17px;
        }

        /* ABOUT */

        .about {
            background: white;
        }

        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 75px;
            align-items: center;
        }

        .about-visual {
            min-height: 370px;
            position: relative;
            border-radius: 18px;
            background: linear-gradient(140deg, #172844, #263f60);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .about-visual::before,
        .about-visual::after {
            content: "";
            position: absolute;
            border: 1px solid rgba(214,173,98,.25);
            border-radius: 50%;
        }

        .about-visual::before {
            width: 320px;
            height: 320px;
        }

        .about-visual::after {
            width: 240px;
            height: 240px;
        }

        .about-emblem {
            position: relative;
            z-index: 2;
            text-align: center;
            color: var(--gold);
        }

        .about-emblem .symbol {
            font-size: 75px;
            line-height: 1;
        }

        .about-emblem h3 {
            color: white;
            font-family: 'Manrope', sans-serif;
            font-size: 23px;
            margin-top: 22px;
        }

        .about-emblem p {
            color: #c4cfde;
            font-size: 12px;
            margin-top: 8px;
            letter-spacing: 2px;
        }

        .about-content .section-label {
            margin-bottom: 15px;
        }

        .about-content h2 {
            font-family: 'Manrope', sans-serif;
            color: var(--navy);
            font-size: 36px;
            line-height: 1.3;
            margin-bottom: 20px;
        }

        .about-content > p {
            color: var(--muted);
            font-size: 15px;
            line-height: 1.9;
            margin-bottom: 18px;
        }

        .about-points {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-top: 28px;
        }

        .about-point {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            font-weight: 700;
            color: var(--navy);
        }

        .about-point span {
            width: 25px;
            height: 25px;
            display: grid;
            place-items: center;
            background: #f8f0df;
            color: #a77a31;
            border-radius: 50%;
        }

        /* PLATFORM */

        .platform {
            background: var(--background);
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 23px;
        }

        .feature-card {
            padding: 32px 27px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 13px;
            transition: .3s;
        }

        .feature-card:hover {
            transform: translateY(-7px);
            box-shadow: var(--shadow);
            border-color: rgba(214,173,98,.5);
        }

        .feature-icon {
            width: 55px;
            height: 55px;
            display: grid;
            place-items: center;
            background: #f8f1e5;
            border-radius: 12px;
            font-size: 24px;
            margin-bottom: 23px;
        }

        .feature-card h3 {
            font-family: 'Manrope', sans-serif;
            color: var(--navy);
            font-size: 18px;
            margin-bottom: 12px;
        }

        .feature-card p {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.8;
        }

        /* HOW IT WORKS */

        .steps {
            background: white;
        }

        .step-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 35px;
        }

        .step-card {
            text-align: center;
            padding: 25px;
            position: relative;
        }

        .step-number {
            width: 66px;
            height: 66px;
            display: grid;
            place-items: center;
            margin: 0 auto 24px;
            border-radius: 50%;
            background: var(--navy);
            color: var(--gold);
            font-family: 'Manrope', sans-serif;
            font-size: 22px;
            font-weight: 800;
        }

        .step-card h3 {
            font-family: 'Manrope', sans-serif;
            color: var(--navy);
            font-size: 18px;
            margin-bottom: 12px;
        }

        .step-card p {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.8;
        }

        /* CTA */

        .cta-section {
            padding: 75px 0;
            background: var(--background);
        }

        .cta-box {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 30px;
            padding: 55px 60px;
            border-radius: 17px;
            background: linear-gradient(115deg, #101c35, #203653);
            color: white;
        }

        .cta-box::after {
            content: "";
            position: absolute;
            width: 250px;
            height: 250px;
            border: 1px solid rgba(214,173,98,.2);
            border-radius: 50%;
            right: 20%;
            top: -150px;
        }

        .cta-content {
            position: relative;
            z-index: 2;
            max-width: 600px;
        }

        .cta-content h2 {
            font-family: 'Manrope', sans-serif;
            font-size: 32px;
            line-height: 1.3;
        }

        .cta-content p {
            color: #c2ccdb;
            font-size: 14px;
            line-height: 1.8;
            margin-top: 12px;
        }

        .cta-box .btn {
            position: relative;
            z-index: 2;
            flex-shrink: 0;
        }

        /* FAQ Section */
.faq {
    background: #ffffff;
    padding: 100px 24px;
}

.faq-list {
    max-width: 850px;
    margin: 45px auto 0;
    border: none !important;
    box-shadow: none !important;
}

.faq-item {
    border: none !important;
    border-bottom: 1px solid #e9edf3 !important;
    border-radius: 0 !important;
    background: transparent;
    box-shadow: none !important;
    overflow: hidden;
}

.faq-item:first-child {
    border-top: none !important;
}

.faq-question {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 24px 8px;
    background: transparent;
    border: none !important;
    outline: none;
    box-shadow: none !important;
    color: #172033;
    font-size: 17px;
    font-weight: 600;
    text-align: left;
    cursor: pointer;
    transition: color 0.25s ease;
}

.faq-question:hover {
    color: #c49a45;
}

.faq-icon {
    min-width: 32px;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #f5f6f8;
    color: #c49a45;
    font-size: 20px;
    transition: all 0.3s ease;
}

.faq-answer {
    display: none;
    padding: 0 50px 24px 8px;
    color: #687386;
    font-size: 15px;
    line-height: 1.8;
}

.faq-item.active .faq-answer {
    display: block;
}

.faq-item.active .faq-icon {
    background: #c49a45;
    color: #ffffff;
    transform: rotate(45deg);
}

@media (max-width: 768px) {
    .faq {
        padding: 70px 20px;
    }

    .faq-question {
        font-size: 15px;
        padding: 20px 5px;
    }

    .faq-answer {
        padding: 0 35px 20px 5px;
        font-size: 14px;
    }
}

        /* FOOTER */

        .footer {
            background: #0c172b;
            color: white;
            padding: 70px 0 25px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr 1fr;
            gap: 45px;
            padding-bottom: 50px;
        }

        .footer-brand img {
            width: 190px;
            height: 65px;
            object-fit: contain;
            object-position: left center;
            filter: brightness(0) invert(1);
        }

        .footer-brand p {
            max-width: 280px;
            margin-top: 18px;
            color: #aab5c6;
            font-size: 13px;
            line-height: 1.9;
        }

        .footer-column h4 {
            font-family: 'Manrope', sans-serif;
            font-size: 15px;
            margin-bottom: 22px;
        }

        .footer-column a {
            display: block;
            color: #aab5c6;
            font-size: 13px;
            margin-bottom: 14px;
            transition: .25s;
        }

        .footer-column a:hover {
            color: var(--gold);
        }

        .footer-bottom {
            padding-top: 24px;
            border-top: 1px solid rgba(255,255,255,.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            color: #8793a6;
            font-size: 12px;
        }

        .footer-legal {
            display: flex;
            gap: 20px;
        }

        .footer-legal a:hover {
            color: var(--gold);
        }

        .disclaimer {
            margin-top: 22px;
            color: #8793a6;
            font-size: 11px;
            line-height: 1.8;
        }

        /* RESPONSIVE */

        @media (max-width: 1000px) {
            .nav-menu {
                gap: 18px;
            }

            .hero-grid {
                gap: 30px;
            }

            .hero h1 {
                font-size: 48px;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 768px) {
            .navbar {
                height: 72px;
            }

            .brand img {
                width: 155px;
                height: 50px;
            }

            .menu-toggle {
                display: block;
            }

            .nav-menu {
                display: none;
                position: absolute;
                top: 72px;
                left: 0;
                right: 0;
                background: white;
                padding: 22px;
                border-bottom: 1px solid var(--border);
                box-shadow: var(--shadow);
                flex-direction: column;
                align-items: stretch;
                gap: 0;
            }

            .nav-menu.active {
                display: flex;
            }

            .nav-menu a {
                padding: 13px 5px;
            }

            .nav-actions {
                gap: 7px;
            }

            .nav-actions .btn {
                padding: 10px 12px;
                font-size: 12px;
            }

            .hero {
                padding: 75px 0 80px;
            }

            .hero-grid,
            .about-grid {
                grid-template-columns: 1fr;
            }

            .hero h1 {
                font-size: 43px;
            }

            .hero-visual {
                min-height: 350px;
                margin-top: 25px;
            }

            .about-grid {
                gap: 40px;
            }

            .feature-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .step-grid {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .cta-box {
                padding: 40px 30px;
                flex-direction: column;
                align-items: flex-start;
            }

            .section {
                padding: 75px 0;
            }
        }

        @media (max-width: 520px) {
            .container {
                width: min(100% - 30px, 1160px);
            }

            .nav-actions .btn-outline {
                display: none;
            }

            .hero h1 {
                font-size: 36px;
                letter-spacing: -1.2px;
            }

            .hero-description {
                font-size: 14px;
            }

            .hero-visual {
                min-height: 310px;
            }

            .visual-card {
                width: min(310px, 95%);
                padding: 22px;
            }

            .visual-orbit {
                width: 280px;
                height: 280px;
            }

            .floating-card {
                right: 0;
                bottom: 8px;
            }

            .feature-grid {
                grid-template-columns: 1fr;
            }

            .about-visual {
                min-height: 290px;
            }

            .about-content h2 {
                font-size: 29px;
            }

            .about-points {
                grid-template-columns: 1fr;
            }

            .section-heading h2 {
                font-size: 30px;
            }

            .cta-content h2 {
                font-size: 26px;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 35px 20px;
            }

            .footer-brand {
                grid-column: 1 / -1;
            }

            .footer-bottom {
                flex-direction: column;
                align-items: flex-start;
            }

            .footer-legal {
                flex-wrap: wrap;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <header class="site-header">
        <div class="container navbar">

            <a href="{{ route('home') }}" class="brand">
                <img src="{{ asset('images/dhakaiya-logo.png') }}"
                     alt="Dhakaiya Investments">
            </a>

            <nav class="nav-menu" id="navMenu">
                <a href="#home">Home</a>
                <a href="#about">About Us</a>
                <a href="#platform">Platform</a>
                <a href="#how-it-works">How It Works</a>
                <a href="#faq">FAQ</a>
            </nav>

            <div class="nav-actions">

                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-gold">
                        Dashboard →
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline">
                        Login
                    </a>

                    @if(Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-gold">
                            Register
                        </a>
                    @endif
                @endauth

                <button class="menu-toggle"
                        id="menuToggle"
                        aria-label="Toggle navigation"
                        aria-expanded="false">
                    ☰
                </button>

            </div>

        </div>
    </header>


    {{-- HERO --}}
    <section class="hero" id="home">
        <div class="container hero-grid">

            <div class="hero-content">

                <div class="hero-badge">
                    <span></span>
                    Welcome to Dhakaiya Investments
                </div>

                <h1>
                    A Smarter Way to
                    <span>Manage Your</span>
                    Platform Experience.
                </h1>

                <p class="hero-description">
                    Discover a structured digital platform designed
                    to bring account information, personal settings
                    and essential services together in one convenient place.
                </p>

                <div class="hero-buttons">

                    @guest
                        <a href="{{ route('register') }}" class="btn btn-gold">
                            Create Account →
                        </a>

                        <a href="{{ route('login') }}" class="btn btn-transparent">
                            Sign In
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn btn-gold">
                            Go to Dashboard →
                        </a>
                    @endguest

                </div>

                <p class="hero-note">
                    Accessible across desktop, tablet and mobile devices.
                </p>

            </div>


            {{-- DASHBOARD VISUAL --}}
            <div class="hero-visual">

                <div class="visual-orbit"></div>

                <div class="visual-card">

                    <div class="visual-card-header">
                        <strong>Dhakaiya Investments</strong>
                        <span class="visual-status">Platform Overview</span>
                    </div>

                    <div class="visual-welcome">
                        YOUR DIGITAL SPACE
                    </div>

                    <h3>Welcome Back!</h3>

                    <p>Manage your account information in one place.</p>

                    <hr class="visual-divider">

                    <div class="visual-options">

                        <div class="visual-option">
                            <span>👤</span>
                            <strong>My Account</strong>
                            <small>Personal information</small>
                        </div>

                        <div class="visual-option">
                            <span>🌍</span>
                            <strong>Country</strong>
                            <small>Regional settings</small>
                        </div>

                        <div class="visual-option">
                            <span>🔔</span>
                            <strong>Notifications</strong>
                            <small>Important updates</small>
                        </div>

                        <div class="visual-option">
                            <span>💬</span>
                            <strong>Support</strong>
                            <small>Help and assistance</small>
                        </div>

                    </div>

                </div>

                <div class="floating-card">
                    <span>✦</span>
                    Your Digital Account Space
                </div>

            </div>

        </div>
    </section>


    {{-- ABOUT --}}
    <section class="section about" id="about">

        <div class="container about-grid">

            <div class="about-visual">

                <div class="about-emblem">
                    <div class="symbol">✦</div>

                    <h3>Dhakaiya Investments</h3>

                    <p>INFORMATION & ACCOUNT PLATFORM</p>
                </div>

            </div>

            <div class="about-content">

                <span class="section-label">About Our Platform</span>

                <h2>
                    A Central Place for Your Digital Account Experience
                </h2>

                <p>
                    Dhakaiya Investments provides a centralized digital
                    environment where registered users can access their
                    account information and platform services.
                </p>

                <p>
                    Our platform interface is designed to make navigation
                    straightforward, organize essential information and
                    provide convenient access to account-related services.
                </p>

                <div class="about-points">

                    <div class="about-point">
                        <span>✓</span>
                        Organized Account Access
                    </div>

                    <div class="about-point">
                        <span>✓</span>
                        Responsive Interface
                    </div>

                    <div class="about-point">
                        <span>✓</span>
                        User Support
                    </div>

                    <div class="about-point">
                        <span>✓</span>
                        Account Information
                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- PLATFORM FEATURES --}}
    <section class="section platform" id="platform">

        <div class="container">

            <div class="section-heading">

                <span class="section-label">Platform Overview</span>

                <h2>
                    Everything Organized in One Place
                </h2>

                <p>
                    Explore the main areas of the Dhakaiya Investments
                    digital platform.
                </p>

            </div>


            <div class="feature-grid">

                <div class="feature-card">
                    <div class="feature-icon">👤</div>

                    <h3>Account Management</h3>

                    <p>
                        Access your account profile and manage
                        your personal information through a
                        centralized interface.
                    </p>
                </div>


                <div class="feature-card">
                    <div class="feature-icon">🌍</div>

                    <h3>Country Information</h3>

                    <p>
                        View your registered country information
                        and related regional account preferences.
                    </p>
                </div>


                <div class="feature-card">
                    <div class="feature-icon">🔔</div>

                    <h3>Notifications</h3>

                    <p>
                        Keep track of platform announcements,
                        account updates and important notices.
                    </p>
                </div>


                <div class="feature-card">
                    <div class="feature-icon">🛡️</div>

                    <h3>Account Security</h3>

                    <p>
                        Access available account settings and
                        password management options.
                    </p>
                </div>


                <div class="feature-card">
                    <div class="feature-icon">📊</div>

                    <h3>Information Dashboard</h3>

                    <p>
                        Navigate your available account sections
                        through a structured dashboard interface.
                    </p>
                </div>


                <div class="feature-card">
                    <div class="feature-icon">💬</div>

                    <h3>Support Services</h3>

                    <p>
                        Find platform assistance and access
                        available support information.
                    </p>
                </div>

            </div>

        </div>

    </section>


    {{-- HOW IT WORKS --}}
    <section class="section steps" id="how-it-works">

        <div class="container">

            <div class="section-heading">

                <span class="section-label">Getting Started</span>

                <h2>
                    Getting Started Is Simple
                </h2>

                <p>
                    Follow these basic steps to access your
                    Dhakaiya Investments account.
                </p>

            </div>


            <div class="step-grid">

                <div class="step-card">

                    <div class="step-number">01</div>

                    <h3>Create Your Account</h3>

                    <p>
                        Complete the registration process using
                        your required account information.
                    </p>

                </div>


                <div class="step-card">

                    <div class="step-number">02</div>

                    <h3>Sign In Securely</h3>

                    <p>
                        Use your registered phone number and
                        password to access your account.
                    </p>

                </div>


                <div class="step-card">

                    <div class="step-number">03</div>

                    <h3>Explore Your Dashboard</h3>

                    <p>
                        Navigate your available account sections,
                        information and support services.
                    </p>

                </div>

            </div>

        </div>

    </section>


    {{-- CTA --}}
    <section class="cta-section">

        <div class="container">

            <div class="cta-box">

                <div class="cta-content">

                    <h2>
                        Your Account, Your Digital Space.
                    </h2>

                    <p>
                        Access the Dhakaiya Investments platform
                        and explore its available account services.
                    </p>

                </div>

                @guest
                    <a href="{{ route('register') }}" class="btn btn-gold">
                        Create Your Account →
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-gold">
                        Visit Dashboard →
                    </a>
                @endguest

            </div>

        </div>

    </section>


    {{-- FAQ --}}
    <section class="section faq" id="faq">

        <div class="container">

            <div class="section-heading">

                <span class="section-label">Frequently Asked Questions</span>

                <h2>
                    Common Questions
                </h2>

                <p>
                    Find answers to some common questions
                    about accessing the platform.
                </p>

            </div>


            <div class="faq-list">

                <div class="faq-item">

                    <button class="faq-question">
                        <span>What is Dhakaiya Investments?</span>
                        <span>+</span>
                    </button>

                    <div class="faq-answer">
                        Dhakaiya Investments is a digital platform
                        that provides registered users with access
                        to account information and available platform services.
                    </div>

                </div>


                <div class="faq-item">

                    <button class="faq-question">
                        <span>How can I create an account?</span>
                        <span>+</span>
                    </button>

                    <div class="faq-answer">
                        Select the Register option and complete
                        the registration form with the required
                        information.
                    </div>

                </div>


                <div class="faq-item">

                    <button class="faq-question">
                        <span>How can I access my account?</span>
                        <span>+</span>
                    </button>

                    <div class="faq-answer">
                        Visit the Login page and enter your
                        registered phone number and password.
                    </div>

                </div>


                <div class="faq-item">

                    <button class="faq-question">
                        <span>Can I access the platform from mobile?</span>
                        <span>+</span>
                    </button>

                    <div class="faq-answer">
                        The website interface is responsive and
                        designed to work across supported desktop,
                        tablet and mobile browsers.
                    </div>

                </div>


                <div class="faq-item">

                    <button class="faq-question">
                        <span>Where can I get assistance?</span>
                        <span>+</span>
                    </button>

                    <div class="faq-answer">
                        Registered users can check the available
                        support section after signing in.
                    </div>

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
                        <img src="{{ asset('images/dhakaiya-logo.png') }}"
                             alt="Dhakaiya Investments">
                    </a>

                    <p>
                        A centralized digital platform for
                        account information, platform services
                        and user support.
                    </p>

                </div>


                <div class="footer-column">

                    <h4>Quick Links</h4>

                    <a href="#home">Home</a>
                    <a href="#about">About Us</a>
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

                    <a href="#how-it-works">Getting Started</a>

                </div>


                <div class="footer-column">

                    <h4>Information</h4>

                    <a href="#about">About Platform</a>
                    <a href="#faq">Help & FAQ</a>
                    <a href="#platform">Services</a>

                </div>

            </div>


            <div class="footer-bottom">

                <p>
                    © <span id="currentYear"></span>
                    Dhakaiya Investments. All Rights Reserved.
                </p>

                <div class="footer-legal">
                    <a href="#about">About</a>
                    <a href="#faq">FAQ</a>
                </div>

            </div>

        </div>

    </footer>


    <script>
        // Mobile Navigation
        const menuToggle = document.getElementById('menuToggle');
        const navMenu = document.getElementById('navMenu');

        menuToggle.addEventListener('click', function () {

            navMenu.classList.toggle('active');

            const isOpen = navMenu.classList.contains('active');

            menuToggle.textContent = isOpen ? '✕' : '☰';

            menuToggle.setAttribute('aria-expanded', isOpen);

        });


        // Close mobile menu after clicking a link
        document.querySelectorAll('.nav-menu a').forEach(function (link) {

            link.addEventListener('click', function () {

                navMenu.classList.remove('active');

                menuToggle.textContent = '☰';

                menuToggle.setAttribute('aria-expanded', 'false');

            });

        });


        // FAQ Accordion
        document.querySelectorAll('.faq-question').forEach(function (button) {

            button.addEventListener('click', function () {

                const currentItem = button.parentElement;

                document.querySelectorAll('.faq-item').forEach(function (item) {

                    if (item !== currentItem) {
                        item.classList.remove('active');
                    }

                });

                currentItem.classList.toggle('active');

            });

        });


        // Dynamic Copyright Year
        document.getElementById('currentYear').textContent =
            new Date().getFullYear();

    </script>

</body>
</html>