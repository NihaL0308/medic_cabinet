@extends('layouts.app')

@section('title', auth()->user()->isMedecin() ? __('app.my_services') : __('app.services'))
@section('page-title', auth()->user()->isMedecin() ? __('app.my_services') : __('app.services'))

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h2 class="h4 mb-1">{{ auth()->user()->isMedecin() ? __('app.my_services') : __('app.services') }}</h2>
        <p class="text-muted mb-0">
            {{ auth()->user()->isMedecin() ? __('app.manage_my_services') : __('app.manage_services') }}
        </p>
    </div>

    @can('create', \App\Models\Service::class)
        <a href="{{ route('services.create') }}" class="btn btn-primary">
            {{ __('app.new_service') }}
        </a>
    @endcan
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <form method="GET" action="{{ route('services.index') }}" class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <label for="service-search" class="form-label fw-semibold">{{ __('app.search') }}</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fas fa-search text-primary"></i>
                    </span>
                    <input
                        id="service-search"
                        type="text"
                        name="search"
                        class="form-control border-start-0"
                        value="{{ $search }}"
                        placeholder="{{ __('app.search_services') }}">
                    <button class="btn btn-outline-secondary" type="submit">{{ __('app.search') }}</button>
                </div>
            </div>
        </form>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold mb-2">{{ __('app.service_count') }}</div>
                <div class="display-6 fw-bold text-primary mb-2">{{ $services->total() }}</div>
                <p class="text-muted mb-0">{{ __('app.service_scope_notice') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">{{ __('app.name') }}</th>
                        @if(auth()->user()->isAdmin())
                            <th>{{ __('app.doctor_owner') }}</th>
                        @endif
                        <th>{{ __('app.description') }}</th>
                        <th>{{ __('app.duration') }}</th>
                        <th>{{ __('app.price') }}</th>
                        <th class="text-end pe-4">{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-semibold">{{ $service->name }}</div>
                                <div class="text-muted small">#{{ $service->id }}</div>
                            </td>
                            @if(auth()->user()->isAdmin())
                                <td>
                                    <div class="fw-semibold">{{ $service->medecin?->name ?? __('app.unassigned') }}</div>
                                    <div class="text-muted small">{{ $service->medecin?->specialite ?? __('app.not_available') }}</div>
                                </td>
                            @endif
                            <td class="text-muted">{{ $service->description ?: '-' }}</td>
                            <td>{{ $service->duree_minutes }} {{ __('app.minutes') }}</td>
                            <td>{{ $service->prix !== null ? number_format((float) $service->prix, 2) . ' ' . __('app.currency') : '-' }}</td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-2">
                                    @can('update', $service)
                                        <a href="{{ route('services.edit', $service) }}" class="btn btn-sm btn-outline-primary">
                                            {{ __('app.edit') }}
                                        </a>
                                    @endcan

                                    @can('delete', $service)
                                        <form method="POST" action="{{ route('services.destroy', $service) }}" onsubmit="return confirm('{{ __('app.delete_warning') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                {{ __('app.delete') }}
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->isAdmin() ? 6 : 5 }}" class="text-center py-5">
                                <div class="fw-semibold mb-1">{{ __('app.no_services') }}</div>
                                <div class="text-muted">{{ __('app.empty_services_help') }}</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($services->hasPages())
    <div class="mt-4">
        {{ $services->links() }}
    </div>
@endif
@endsection
