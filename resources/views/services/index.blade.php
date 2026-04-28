@extends('layouts.app')

@section('title', __('app.services'))
@section('page-title', __('app.services'))

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h2 class="h4 mb-1">{{ __('app.services') }}</h2>
        <p class="text-muted mb-0">{{ __('app.manage_services') }}</p>
    </div>

    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
        {{ __('app.new_service') }}
    </button>
</div>

<form method="GET" action="{{ route('services.index') }}" class="card mb-4">
    <div class="card-body">
        <div class="input-group">
            <span class="input-group-text bg-white">
                <i class="fas fa-search text-primary"></i>
            </span>
            <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="{{ __('app.search_services') }}">
            <button class="btn btn-outline-secondary" type="submit">{{ __('app.show') }}</button>
        </div>
    </div>
</form>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>{{ __('app.name') }}</th>
                        <th>{{ __('app.description') }}</th>
                        <th>{{ __('app.duration') }}</th>
                        <th>{{ __('app.price') }}</th>
                        <th class="text-end">{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                        <tr>
                            <td class="fw-semibold">{{ $service->name }}</td>
                            <td class="text-muted">{{ $service->description ?: '-' }}</td>
                            <td>{{ $service->duree_minutes }} {{ __('app.minutes') }}</td>
                            <td>{{ $service->prix ? number_format((float) $service->prix, 2) . ' MAD' : '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('services.edit', $service) }}" class="btn btn-sm btn-outline-primary">{{ __('app.edit') }}</a>
                                <button class="btn btn-sm btn-outline-danger btn-delete-service" data-url="{{ route('services.destroy', $service) }}" data-name="{{ $service->name }}">
                                    {{ __('app.delete') }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">{{ __('app.no_services') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($services->hasPages())
    <div class="mt-3">
        {{ $services->links() }}
    </div>
@endif

<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">{{ __('app.new_service') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" action="{{ route('services.store') }}">
                @csrf
                <div class="modal-body">
                    @if($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">{{ __('app.name') }}</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('app.description') }}</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('app.duration') }}</label>
                            <input type="number" name="duree_minutes" class="form-control" value="{{ old('duree_minutes', 30) }}" min="5" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('app.price') }}</label>
                            <input type="number" name="prix" class="form-control" value="{{ old('prix') }}" step="0.01" min="0">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('app.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('app.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">{{ __('app.confirm_delete') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">{{ __('app.delete_warning') }}</p>
                <strong id="deleteServiceName"></strong>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('app.cancel') }}</button>
                <form id="deleteForm" method="POST">
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
document.querySelectorAll('.btn-delete-service').forEach((button) => {
    button.addEventListener('click', function () {
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
