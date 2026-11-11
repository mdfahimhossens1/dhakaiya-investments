<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title') | Dhakaiya Investment</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            color: #172033;
        }

        .auth-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 25px 15px;
        }

        .auth-right {
            width: 100%;
            max-width: 460px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .auth-card {
            width: 100%;
            background: white;
            padding: 38px;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, .06);
        }

        .auth-card h2 {
            font-size: 27px;
            margin-bottom: 8px;
            color: #172033;
        }

        .subtitle {
            color: #778094;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 17px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        input,
        select {
            width: 100%;
            padding: 13px;
            border: 1px solid #dce2ea;
            border-radius: 8px;
            outline: none;
            font-size: 14px;
            background: white;
        }

        input:focus,
        select:focus {
            border-color: #087f5b;
            box-shadow: 0 0 0 3px rgba(8, 127, 91, .1);
        }

        .auth-button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            background: #087f5b;
            color: white;
            font-weight: bold;
            font-size: 15px;
            cursor: pointer;
            transition: .2s;
        }

        .auth-button:hover {
            background: #066b4d;
        }

        .auth-footer {
            text-align: center;
            margin-top: 22px;
            font-size: 14px;
            color: #778094;
        }

        .auth-footer a {
            color: #087f5b;
            text-decoration: none;
            font-weight: bold;
        }

        .error-message {
            color: #dc2626;
            font-size: 12px;
            margin-top: 6px;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 18px;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 65px;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: none;
            color: #087f5b;
            cursor: pointer;
            font-size: 13px;
        }

        /* International Phone Input */

        .iti {
            width: 100%;
        }

        .iti input {
            width: 100%;
            padding-top: 13px;
            padding-bottom: 13px;
        }

        .iti__country-list {
            max-width: 300px;
            z-index: 1000;
        }

        @media(max-width: 480px) {
            .auth-container {
                padding: 15px;
            }

            .auth-card {
                padding: 25px 20px;
            }

            .auth-card h2 {
                font-size: 24px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    <div class="auth-container">

        <div class="auth-right">

            <div class="auth-card">
                @yield('content')
            </div>

        </div>

    </div>

    @stack('scripts')

</body>
</html>