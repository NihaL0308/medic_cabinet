<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('app.brand_name'))</title>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
            font-family: 'DM Sans', sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            padding: 20px;
        }

        /* BACKGROUND EFFECT */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: radial-gradient(circle at 20% 20%, rgba(255,255,255,0.15), transparent 40%),
                        radial-gradient(circle at 80% 80%, rgba(255,255,255,0.10), transparent 40%);
            pointer-events: none;
        }

        .auth-container {
            width: 100%;
            max-width: 420px;
            position: relative;
        }

        /* HEADER */
        .auth-brand {
            text-align: center;
            margin-bottom: 20px;
            color: white;
        }

        .auth-icon {
            width: 55px;
            height: 55px;
            background: white;
            color: #0d6efd;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: auto;
            border-radius: 50%;
            font-size: 22px;
            margin-bottom: 10px;
        }

        .auth-brand h1 {
            font-size: 22px;
            margin-bottom: 5px;
        }

        .auth-brand p {
            font-size: 13px;
            opacity: 0.85;
        }

        /* CARD */
        .auth-card {
            background: #fff;
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
        }

        .auth-card h2 {
            font-size: 18px;
            font-weight: 600;
            color: #0d6efd;
            margin-bottom: 20px;
            text-align: center;
        }

        /* FORM */
        .form-label {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 5px;
        }

        .form-control {
            border-radius: 10px;
            padding: 10px;
            border: 1px solid #e2e8f0;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13,110,253,0.15);
        }

        /* BUTTON */
        .btn-auth {
            width: 100%;
            padding: 11px;
            border: none;
            border-radius: 50px;
            background: #0d6efd;
            color: white;
            font-weight: 500;
            transition: 0.2s;
            margin-top: 10px;
        }

        .btn-auth:hover {
            background: #0b5ed7;
            transform: translateY(-1px);
        }

        /* FOOTER */
        .auth-footer {
            text-align: center;
            margin-top: 15px;
            font-size: 13px;
            color: white;
        }

        .auth-footer a {
            color: #fff;
            font-weight: 600;
            text-decoration: underline;
        }

        /* ALERT */
        .alert {
            border-radius: 10px;
            font-size: 13px;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        /* BACK LINK */
        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: rgba(255,255,255,0.8);
            font-size: 13px;
            text-decoration: none;
        }

        .back-link:hover {
            color: white;
        }
    </style>
</head>

<body>

<div class="auth-container">

    <div class="auth-brand">
        <div class="auth-icon">
            <i class="fas fa-hospital"></i>
        </div>
        <h1>{{ __('app.brand_name') }}</h1>
        <p>{{ __('app.brand_tagline') }}</p>
    </div>

    <div class="auth-card">

        @yield('content')

    </div>

    <a href="/" class="back-link">
        <i class="fas fa-arrow-left"></i> {{ __('app.back_home') }}
    </a>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
