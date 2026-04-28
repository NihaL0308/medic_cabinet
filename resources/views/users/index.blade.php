@extends('layouts.app')

@section('title', __('app.users'))
@section('page-title', __('app.users'))

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h2 class="h4 mb-1">{{ __('app.users') }}</h2>
        <p class="text-muted mb-0">{{ __('app.patients_and_doctors') }}</p>
    </div>

    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
        {{ __('app.new_user') }}
    </button>
</div>

<form method="GET" action="{{ route('users.index') }}" class="card mb-4">
    <div class="card-body">
        <div class="input-group">
            <span class="input-group-text bg-white">
                <i class="fas fa-search text-primary"></i>
            </span>
            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="{{ __('app.search_users') }}">
            <button class="btn btn-outline-secondary" type="submit">{{ __('app.search') }}</button>
        </div>
    </div>
</form>

<div class="row g-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white border-0 pt-4">
                <h3 class="h6 mb-0">{{ __('app.patients') }} ({{ $patients->count() }})</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('app.full_name') }}</th>
                                <th>{{ __('app.email') }}</th>
                                <th>{{ __('app.phone') }}</th>
                                <th class="text-end">{{ __('app.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @include('users.partials.patients-rows', ['patients' => $patients])
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white border-0 pt-4">
                <h3 class="h6 mb-0">{{ __('app.doctors_list') }} ({{ $medecins->count() }})</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('app.full_name') }}</th>
                                <th>{{ __('app.email') }}</th>
                                <th>{{ __('app.specialite') }}</th>
                                <th>{{ __('app.phone') }}</th>
                                <th class="text-end">{{ __('app.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @include('users.partials.medecins-rows', ['medecins' => $medecins])
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">{{ __('app.new_user') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                <div class="modal-body">
                    @if($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('app.full_name') }}</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('app.email') }}</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('app.role') }}</label>
                            <select name="role" id="roleSelect" class="form-select" required>
                                <option value="patient" @selected(old('role') === 'patient')>{{ __('app.role_patient') }}</option>
                                <option value="medecin" @selected(old('role') === 'medecin')>{{ __('app.role_medecin') }}</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('app.phone') }}</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                        </div>

                        <div class="col-md-6" id="specialiteField" style="{{ old('role') === 'medecin' ? '' : 'display:none;' }}">
                            <label class="form-label">{{ __('app.specialite') }}</label>
                            <input type="text" name="specialite" class="form-control" value="{{ old('specialite') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('app.password') }}</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('app.confirm_password') }}</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('app.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('app.create_user') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">{{ __('app.confirm_delete_user') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">{{ __('app.delete_user_warning') }}</p>
                <strong id="deleteUserName"></strong>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('app.cancel') }}</button>
                <form id="deleteUserForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">{{ __('app.delete') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const roleSelect = document.getElementById('roleSelect');
const specialiteField = document.getElementById('specialiteField');

if (roleSelect) {
    roleSelect.addEventListener('change', function () {
        specialiteField.style.display = this.value === 'medecin' ? '' : 'none';
    });
}

document.querySelectorAll('.btn-delete-user').forEach((button) => {
    button.addEventListener('click', function () {
        document.getElementById('deleteUserName').textContent = this.dataset.name;
        document.getElementById('deleteUserForm').action = this.dataset.url;
        new bootstrap.Modal(document.getElementById('deleteUserModal')).show();
    });
});

@if($errors->any())
new bootstrap.Modal(document.getElementById('createUserModal')).show();
@endif
</script>
@endpush
