@extends('layouts.app')
@section('title', __('app.edit_user'))


@section('content')
<div class="card border-0 shadow-sm" style="max-width:600px;margin:auto">
    <div class="card-body p-4">

        <h5 class="fw-bold mb-4">
            {{ __('app.edit_user') }}: {{ $user->name }}
        </h5>

        @if($errors->any())
            <div >{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf @method('PUT')

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">{{ __('app.full_name') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('app.email') }}</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('app.role') }}</label>
                    <select name="role" class="form-select" id="roleSelect" required>
                        <option value="patient" {{ $user->role=='patient' ? 'selected':'' }}>{{ __('app.role_patient') }}</option>
                        <option value="medecin" {{ $user->role=='medecin' ? 'selected':'' }}>{{ __('app.role_medecin') }}</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('app.phone') }}</label>
                    <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                </div>

                <div class="col-12" id="specialiteField"
                    style="{{ $user->role=='medecin' ? '' : 'display:none' }}">
                    <label class="form-label">{{ __('app.specialite') }}</label>
                    <input type="text" name="specialite" class="form-control" value="{{ $user->specialite }}">
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">

                <button class="btn btn-primary">
                    {{ __('app.save') }}
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    {{ __('app.cancel') }}
                </a>

            </div>
        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('roleSelect').addEventListener('change', function() {
    document.getElementById('specialiteField').style.display =
        this.value === 'medecin' ? 'block' : 'none';
});
</script>
@endpush
