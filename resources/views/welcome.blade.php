<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Login | Hosting App</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background: linear-gradient(135deg, #667eea, #764ba2);
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .login-container {
                background-color: white;
                padding: 40px;
                border-radius: 10px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
                width: 100%;
                max-width: 400px;
                text-align: center;
            }
            h1 {
                font-size: 24px;
                margin-bottom: 10px;
                font-weight: 600;
                color: #333;
            }
            p {
                font-size: 14px;
                color: #666;
                margin-bottom: 30px;
            }
            input {
                width: 100%;
                padding: 12px 15px;
                margin: 10px 0;
                border: 1px solid #ddd;
                border-radius: 5px;
                font-size: 14px;
            }
            .btn-primary {
                background-color: #667eea;
                color: white;
                padding: 12px 20px;
                width: 100%;
                border: none;
                border-radius: 5px;
                font-size: 16px;
                cursor: pointer;
                transition: background 0.3s ease;
            }
            .btn-primary:hover {
                background-color: #5563c1;
            }
            .forgot-password {
                margin-top: 15px;
                display: block;
                color: #667eea;
                text-decoration: none;
                font-size: 14px;
            }
            .forgot-password:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>

        <div class="login-container">
            <h1>Benvenuto su Hosting App</h1>
            <p>Gestisci i tuoi server Cloudways, l'API di Fattura24 e la configurazione delle email da un'unica piattaforma semplice e intuitiva.</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <input type="email" name="email" placeholder="Email" required autofocus>
                <input type="password" name="password" placeholder="Password" required>

                <button type="submit" class="btn-primary">Login</button>
            </form>

            <a href="{{ route('password.request') }}" class="forgot-password">Hai dimenticato la password?</a>
        </div>

    </body>
</html>
