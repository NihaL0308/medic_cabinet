

@extends('layouts.app')

@section('title', __('app.appointments'))
@section('page-title', __('app.appointments'))

@section('content')

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-4">

    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAppointmentModal">
        {{ __('app.new_appointment') }}
    </button>
</div>

<!-- SEARCH -->
<div class="card mb-4 border-0 shadow-sm" style="border-radius:14px">
    <div class="card-body">

        <div class="input-group">

            <span class="input-group-text bg-white">
                <i class="fas fa-search text-primary"></i>
            </span>

            <input type="text"
                   id="searchInput"
                   class="form-control"
                   placeholder="Rechercher...">

            <span id="searchSpinner" class="input-group-text d-none bg-white">
                <div class="spinner-border spinner-border-sm text-primary"></div>
            </span>

        </div>

    </div>
</div>

<!-- TABLE -->
<div class="card border-0 shadow-sm" style="border-radius:14px; overflow:hidden">

    <div class="card-body p-0">

        <div id="appointmentsTable">
            @include('appointments.partials.table', ['appointments' => $appointments])
        </div>
    </div>
</div>

<!-- CREATE MODAL -->
<div class="modal fade" id="createAppointmentModal" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content border-0 shadow" style="border-radius:14px">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-plus me-2"></i>
                    {{ __('app.new_appointment') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" action="{{ route('appointments.store') }}">
                @csrf

                <div class="modal-body">

                    @if($errors->any())
                        <div>{{ $errors->first() }}</div>
                    @endif

                    <div class="row g-3">

                        @if(auth()->user()->isPatient())
                            <input type="hidden" name="patient_id" value="{{ auth()->id() }}">

                            <div class="col-md-6">
                                <label class="form-label text-primary">{{ __('app.patient') }}</label>
                                <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
                            </div>
                        @else
                            <div class="col-md-6">
                                <label class="form-label text-primary">{{ __('app.patient') }}</label>
                                <select name="patient_id" class="form-control" required>
                                    <option value="">patients</option>
                                    @foreach($patients as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="col-md-6">
                            <label class="form-label text-primary">{{ __('app.doctor') }}</label>
                            <select name="medecin_id" class="form-control" required>
                                <option value="">medecins</option>
                                @foreach($medecins as $m)
                                    <option value="{{ $m->id }}">
                                        {{ $m->name }} ({{ $m->specialite }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-primary">{{ __('app.service') }}</label>
                            <select name="service_id" class="form-control" required>
                                <option value="">services</option>
                                @foreach($services as $s)
                                    <option value="{{ $s->id }}">
                                        {{ $s->name }} ({{ $s->duree_minutes }} min)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label text-primary">{{ __('app.date') }}</label>
                            <input type="date" name="appointment_date"
                                   class="form-control"
                                   min="{{ date('Y-m-d') }}"
                                   value="{{ old('appointment_date') }}"
                                   required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label text-primary">{{ __('app.time') }}</label>
                            <input type="time" name="appointment_time"
                                   class="form-control"
                                   value="{{ old('appointment_time') }}"
                                   required>
                        </div>

                        <div class="col-12">
                            <label class="form-label text-primary">{{ __('app.notes') }}</label>
                            <textarea name="notes"
                                      class="form-control"
                                      rows="2"
                                      placeholder="Remarques">{{ old('notes') }}</textarea>
                        </div>

                    </div>

                </div>

                <div class="modal-footer border-0">

                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        {{ __('app.cancel') }}
                    </button>

                    <button type="submit" class="btn btn-primary">
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

            <div>
                <h5 class="modal-title">
                    
                    {{ __('app.confirm_delete') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center py-4">

                <p>{{ __('app.delete_warning') }}</p>

            </div>

            <div class="modal-footer justify-content-center border-0">

                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    {{ __('app.cancel') }}
                </button>

                <form id="deleteForm" method="POST">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>
                        {{ __('app.delete') }}
                    </button>
                </form>

            </div>

        </div>

    </div>

</div>

@endsection


@push('scripts')
<script>

let timer;
const input = document.getElementById('searchInput');
const spinner = document.getElementById('searchSpinner');

input.addEventListener('input', function () {

    clearTimeout(timer);

    timer = setTimeout(() => {

        spinner.classList.remove('d-none');

        axios.get('{{ route("appointments.index") }}', {
            params: { search: this.value },
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).then(res => {
            document.getElementById('appointmentsTable').innerHTML = res.data;
            bindDelete();
        }).finally(() => spinner.classList.add('d-none'));

    }, 400);

});

function bindDelete() {
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('deleteForm').action = this.dataset.url;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        });
    });
}

bindDelete();

@if($errors->any())
    new bootstrap.Modal(document.getElementById('createAppointmentModal')).show();
@endif

</script>
@endpush


<style>
.form-control {
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    font-size: 0.9rem;
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

.card {
    border-radius: 14px;
}
</style>