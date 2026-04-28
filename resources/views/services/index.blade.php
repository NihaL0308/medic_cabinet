@extends('layouts.app')

@section('title', __('app.services'))
@section('page-title', __('app.services'))

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h4 class="fw-bold text-primary">
        <i class="fas fa-stethoscope me-2"></i>
        {{ __('app.services') }}
    </h4>

    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
        <i class="fas fa-plus me-1"></i> {{ __('app.new_service') }}
    </button>

</div>

<!-- TABLE CARD -->
<div class="card border-0 shadow-sm" style="border-radius:14px; overflow:hidden">

    <div class="card-body p-0">

        <table class="table table-hover align-middle mb-0">

            <thead>
                <tr>
                    <th>{{ __('app.name') }}</th>
                    <th>{{ __('app.description') }}</th>
                    <th>{{ __('app.duration') }}</th>
                    <th>{{ __('app.price') }}</th>
                    <th>{{ __('app.actions') }}</th>
                </tr>
            </thead>

            <tbody>

            @forelse($services as $s)
                <tr>
                    <td class="fw-semibold text-primary">{{ $s->name }}</td>
                    <td class="text-muted">{{ $s->description }}</td>
                    <td>{{ $s->duree_minutes }} {{ __('app.minutes') }}</td>
                    <td class="fw-semibold">{{ $s->prix ? $s->prix.' MAD' : '-' }}</td>
                    <td>

                        <a href="{{ route('services.edit', $s) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i>
                        </a>

                        <button class="btn btn-sm btn-outline-danger btn-delete-service"
                            data-url="{{ route('services.destroy', $s) }}"
                            data-name="{{ $s->name }}">
                            <i class="fas fa-trash"></i>
                        </button>

                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        {{ __('app.no_services') }}
                    </td>
                </tr>
            @endforelse

            </tbody>

        </table>

    </div>
</div>

@if($services->hasPages())
    <div class="mt-3">
        {{ $services->links() }}
    </div>
@endif


<!-- CREATE MODAL -->
<div class="modal fade" id="createModal" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow" style="border-radius:14px">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle me-2"></i>
                    {{ __('app.new_service') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" action="{{ route('services.store') }}">
                @csrf

                <div class="modal-body">

                    @if($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label text-primary">{{ __('app.name') }}</label>
                        <input type="text" name="name" class="form-control focus-blue"
                               value="{{ old('name') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-primary">{{ __('app.description') }}</label>
                        <textarea name="description" class="form-control focus-blue" rows="2">
                            {{ old('description') }}
                        </textarea>
                    </div>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label text-primary">{{ __('app.duration') }}</label>
                            <input type="number" name="duree_minutes"
                                   class="form-control focus-blue"
                                   value="{{ old('duree_minutes', 30) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-primary">{{ __('app.price') }}</label>
                            <input type="number" name="prix"
                                   class="form-control focus-blue"
                                   value="{{ old('prix') }}" step="0.01">
                        </div>

                    </div>

                </div>

                <div class="modal-footer border-0">

                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        {{ __('app.cancel') }}
                    </button>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        {{ __('app.save') }}
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<!-- DELETE MODAL -->
<div class="modal fade" id="deleteModal" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow" style="border-radius:14px">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ __('app.confirm_delete') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center py-4">

                <i class="fas fa-trash fa-3x text-danger mb-3"></i>

                <p>{{ __('app.delete_warning') }}</p>

                <p class="fw-bold fs-5" id="deleteServiceName"></p>

            </div>

            <div class="modal-footer justify-content-center border-0">

                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    {{ __('app.cancel') }}
                </button>

                <form id="deleteForm" method="POST">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> {{ __('app.delete') }}
                    </button>
                </form>

            </div>

        </div>

    </div>

</div>

@endsection


@push('scripts')
<script>
document.querySelectorAll('.btn-delete-service').forEach(btn => {
    btn.addEventListener('click', function () {
        document.getElementById('deleteServiceName').textContent = this.dataset.name;
        document.getElementById('deleteForm').action = this.dataset.url;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    });
});

@if($errors->any())
    new bootstrap.Modal(document.getElementById('createModal')).show();
@endif
</script>
@endpush


<style>
    .form-control {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 9px;
    }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13,110,253,0.15);
    }

    .btn-primary {
        background: #0d6efd;
        border: none;
        border-radius: 10px;
    }

    .btn-primary:hover {
        background: #0b5ed7;
    }

    .table thead th {
        background: #f1f5ff;
        color: #64748b;
        font-size: 12px;
        text-transform: uppercase;
    }

    .table tbody tr:hover {
        background: #f8fbff;
    }
</style>