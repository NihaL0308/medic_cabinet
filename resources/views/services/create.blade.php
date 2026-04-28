@extends('layouts.app')

@section('title', 'Nouveau service')

@section('content')

<div class="card border-0 shadow-sm" style="max-width:600px;margin:auto;border-radius:16px">

    <div class="card-body p-4">

        <h5 class="fw-bold mb-4 text-primary">
            Nouveau service
        </h5>

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('services.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label text-primary">Nom</label>
                <input type="text" name="name" class="form-control focus-blue"
                       value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-primary">Description</label>
                <textarea name="description" class="form-control focus-blue" rows="3">
                    {{ old('description') }}
                </textarea>
            </div>

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label text-primary">Durée (min)</label>
                    <input type="number" name="duree_minutes"
                           class="form-control focus-blue"
                           value="{{ old('duree_minutes', 30) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-primary">Prix (DH)</label>
                    <input type="number" name="prix"
                           class="form-control focus-blue"
                           value="{{ old('prix') }}" step="0.01">
                </div>

            </div>

            <div class="d-flex gap-2 mt-4">

                <button class="btn btn-primary">
                    Sauvegarder
                </button>

                <a href="{{ route('services.index') }}" class="btn btn-outline-secondary">
                    Annuler
                </a>

            </div>

        </form>

    </div>

</div>

<style>
    .form-control {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 10px;
        transition: 0.2s;
    }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13,110,253,0.15);
    }

    .form-label {
        font-size: 13px;
        font-weight: 500;
    }

    .btn-primary {
        background: #0d6efd;
        border: none;
        border-radius: 10px;
        padding: 8px 18px;
    }

    .btn-primary:hover {
        background: #0b5ed7;
    }

    .btn-outline-secondary {
        border-radius: 10px;
    }
</style>

@endsection