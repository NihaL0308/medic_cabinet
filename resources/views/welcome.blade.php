<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>medic_cabinet</title>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'DM Sans', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
        }

        .container {
            background: #fff;
            padding: 40px;
            border-radius: 16px;
            width: 360px;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0,0,0,0.2);
        }

        h1 {
            color: #0d6efd;
            margin-bottom: 10px;
            font-size: 24px;
        }

        p {
            color: #6c757d;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 500;
            transition: 0.2s;
        }

        .btn-login {
            background: #0d6efd;
            color: white;
        }

        .btn-login:hover {
            background: #0b5ed7;
        }

        .btn-register {
            border: 2px solid #0d6efd;
            color: #0d6efd;
        }

        .btn-register:hover {
            background: #0d6efd;
            color: white;
        }

        .icon {
            font-size: 40px;
            color: #0d6efd;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="icon">🏥</div>

    <h1>medic_cabinet</h1>

    <a href="{{ route('login') }}" class="btn btn-login">
        Se connecter
    </a>

    <a href="{{ route('register') }}" class="btn btn-register">
       inscription
    </a>

</div>

</body>
</html>