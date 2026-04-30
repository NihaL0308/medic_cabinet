@extends('layouts.app')

@section('title', __('app.edit_service'))
@section('page-title', __('app.edit_service'))

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-lg-5">
                <div class="mb-4">
                    <h2 class="h4 mb-1">{{ __('app.edit_service') }}</h2>
                    <p class="text-muted mb-0">{{ __('app.service_form_help') }}</p>
                </div>

                @include('services.partials.form', [
                    'action' => route('services.update', $service),
                    'method' => 'PUT',
                    'submitLabel' => __('app.update'),
                ])
            </div>
        </div>
    </div>
</div>
@endsection
