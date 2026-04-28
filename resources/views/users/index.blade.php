@extends('layouts.app')
@section('title', __('app.users'))
@section('content')

<div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
        {{ __('app.new_user') }}
    </button>
</div>

<!-- SEARCH -->
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        <div class="input-group">
            <span class="input-group-text"></span>
            <input type="text" id="searchInput" class="form-control"
                   placeholder="">
            <span id="searchSpinner" class="input-group-text d-none">
                <div class="spinner-border spinner-border-sm"></div>
            </span>
        </div>
    </div>
</div>

<!-- PATIENTS -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-0">
        
            <h6 class="fw-bold mb-0 text-primary">
                {{ __('app.users_list') }}

            </h6>
        
        <div id="patientsSection">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>{{ __('app.full_name') }}</th>
                    <th>{{ __('app.email') }}</th>
                    <th>{{ __('app.phone') }}</th>
                    <th>{{ __('app.actions') }}</th>
                </tr>
                </thead>

                <tbody id="patientsTbody">
                @forelse($patients as $u)
                    <tr>
                        <td class="fw-semibold">{{ $u->name }}</td>
                        <td class="text-muted">{{ $u->email }}</td>
                        <td>{{ $u->phone ?? '-' }}</td>
                        <td>
                            <a href="{{ route('users.edit', $u) }}" class="btn btn-sm btn-outline-primary">
                                modifier
                            </a>

                            <button class="btn btn-sm btn-outline-danger btn-delete-user"
                                    data-url="{{ route('users.destroy', $u) }}"
                                    data-name="{{ $u->name }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>



<!-- MODAL CREATE -->
<div class="modal fade" id="createUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">

            <div >
                <h5 class="modal-title">
                    {{ __('app.new_user') }}
                </h5>
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
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('app.email') }}</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>


                        <div class="col-md-6">
                            <label class="form-label">{{ __('app.phone') }}</label>
                            <input type="text" name="phone" class="form-control">
                        </div>

                        <div class="col-12" id="specialiteField" style="display:none">
                            <label class="form-label">{{ __('app.specialite') }}</label>
                            <input type="text" name="specialite" class="form-control">
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">{{ __('app.cancel') }}</button>
                    <button class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>{{ __('app.save') }}
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// toggle sections
function toggleSection(key){
    const el = document.getElementById(key+'Section');
    el.style.display = el.style.display === 'none' ? '' : 'none';
}

// role switch
document.getElementById('roleSelect').addEventListener('change', function(){
    document.getElementById('specialiteField').style.display =
        this.value === 'medecin' ? 'block' : 'none';
});
</script>
@endpush