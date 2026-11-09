<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard') | Dhakaiya Investment</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
      
    <style>
        :root {
            --primary: #087f5b;
            --primary-dark: #065f46;
            --sidebar: #102b26;
            --background: #f5f7f8;
            --white: #ffffff;
            --text: #1e293b;
            --muted: #84919f;
            --border: #e8edf0;
            --sidebar-width: 260px;
            --header-height: 72px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--background);
            color: var(--text);
        }

        a {
            text-decoration: none;
        }

        /* Sidebar ar content ek row e (sidebar ekhon normal flow e) */
        .app-wrapper {
            display: flex;
            align-items: stretch;
            min-height: 100vh;
            width: 100%;
        }

        .main-content {
            flex: 1 1 auto;
            min-width: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .page-content {
            padding: 28px;
            flex: 1;
        }
        /* Content kokhono parent er bairer jabe na */
        .main-content,
        .page-content {
            min-width: 0;
            max-width: 100%;
        }

        /* Grid/flex child gulo shrink korte parbe */
        .page-content * {
            min-width: 0;
        }

        /* Chart wrapper: canvas ke nijer container er moddhe rakhe */
        .page-content canvas {
            max-width: 100% !important;
        }

        .page-content [class*="chart"] {
            position: relative;
            max-width: 100%;
            overflow: hidden;
        }

        /* Table er scroll thik rakhar jonno (upore * { min-width:0 } er override) */
        .page-content .dashboard-table {
            min-width: 550px;
        }
        @media(max-width: 991px) {
            .page-content {
                padding: 20px;
            }
        }

        @media(max-width: 576px) {
            .page-content {
                padding: 15px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    <div class="app-wrapper">

        @include('partials.sidebar')

        <div class="main-content">

            @include('partials.header')

            <main class="page-content">
                @yield('content')
            </main>

            @include('partials.footer')

        </div>

    </div>

    @stack('scripts')

</body>
</html>