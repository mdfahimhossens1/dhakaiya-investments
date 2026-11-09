<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | Dhakaiya Investment</title>

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

        .navbar {
            background: #087f5b;
            color: white;
            padding: 18px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .welcome-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, .05);
        }

        .welcome-card h2 {
            margin-bottom: 12px;
        }

        .welcome-card p {
            color: #667085;
            line-height: 1.7;
        }

        .logout-button {
            background: white;
            color: #087f5b;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        @media(max-width: 600px) {
            .navbar {
                padding: 15px 20px;
            }

            .welcome-card {
                padding: 22px;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar">

        <h3>Dhakaiya Investment</h3>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="logout-button">
                Logout
            </button>
        </form>

    </nav>

    <main class="container">

        <div class="welcome-card">

            <h2>
                Welcome, {{ auth()->user()->name }}!
            </h2>

            <p>
                Your account has been authenticated successfully.
            </p>

            <p style="margin-top: 12px;">
                Phone: {{ auth()->user()->phone }}
            </p>

            <p>
                Country: {{ auth()->user()->country }}
            </p>

            <p>
                Role: {{ auth()->user()->role }}
            </p>

        </div>

    </main>

</body>
</html>