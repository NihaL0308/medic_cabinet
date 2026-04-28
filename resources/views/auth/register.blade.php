@extends('layouts.guest')
@section('title', 'Inscription')

@section('content')

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
        width: 380px;
        text-align: center;
        box-shadow: 0 20px 50px rgba(0,0,0,0.2);
    }

    h2 {
        color: #0d6efd;
        margin-bottom: 10px;
        font-size: 24px;
    }

    .alert {
        background: #f8d7da;
        color: #842029;
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 15px;
        font-size: 14px;
    }

    .form-label {
        display: block;
        text-align: left;
        margin: 10px 0 5px;
        color: #6c757d;
        font-size: 14px;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border-radius: 10px;
        border: 1px solid #dee2e6;
        outline: none;
        transition: 0.2s;
    }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13,110,253,0.15);
    }

    .btn-auth {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 10px;
        background: #0d6efd;
        color: white;
        font-weight: 500;
        cursor: pointer;
        transition: 0.2s;
        margin-top: 10px;
    }

    .btn-auth:hover {
        background: #0b5ed7;
    }

    .auth-footer {
        margin-top: 15px;
        font-size: 13px;
        color: #6c757d;
    }

    .auth-footer a {
        color: #0d6efd;
        text-decoration: none;
        font-weight: 500;
    }

    .auth-footer a:hover {
        text-decoration: underline;
    }

    .icon {
        font-size: 40px;
        color: #0d6efd;
        margin-bottom: 10px;
    }
</style>

<div class="container">

    <div class="icon">📝</div>

    <h2>Créer un compte</h2>

    @if($errors->any())
        <div class="alert">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <label class="form-label">Nom </label>
        <input type="text" name="name" class="form-control"
            value="{{ old('name') }}" required>

        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control"
            value="{{ old('email') }}" required>

        <label class="form-label">Tél</label>
        <input type="text" name="phone" class="form-control"
            value="{{ old('phone') }}">

        <label class="form-label">Mot de passe</label>
        <input type="password" name="password" class="form-control" required>

        <label class="form-label">Confirmer le mot de passe</label>
        <input type="password" name="password_confirmation" class="form-control" required>

        <button type="submit" class="btn-auth">
            S'inscrire
        </button>
    </form>

    <div class="auth-footer">
        Déjà un compte ?
        <a href="{{ route('login') }}">Se connecter</a>
    </div>

</div>

@endsection