@if($errors->any())
    <div class="alert alert-danger">
        <div class="fw-semibold mb-2">{{ $errors->first() }}</div>
    </div>
@endif

<form method="POST" action="{{ $action }}">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="row g-4">
        @if(auth()->user()->isAdmin())
            <div class="col-12">
                <label for="medecin_id" class="form-label fw-semibold">{{ __('app.assigned_doctor') }}</label>
                <select id="medecin_id" name="medecin_id" class="form-select @error('medecin_id') is-invalid @enderror" required>
                    <option value="">{{ __('app.select_doctor') }}</option>
                    @foreach($medecins as $medecin)
                        <option value="{{ $medecin->id }}" @selected(old('medecin_id', $service->medecin_id) == $medecin->id)>
                            {{ $medecin->name }}{{ $medecin->specialite ? ' (' . $medecin->specialite . ')' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('medecin_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        @endif

        <div class="col-12">
            <label for="name" class="form-label fw-semibold">{{ __('app.name') }}</label>
            <input
                id="name"
                type="text"
                name="name"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $service->name) }}"
                maxlength="255"
                required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12">
            <label for="description" class="form-label fw-semibold">{{ __('app.description') }}</label>
            <textarea
                id="description"
                name="description"
                rows="4"
                class="form-control @error('description') is-invalid @enderror"
                maxlength="2000">{{ old('description', $service->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="duree_minutes" class="form-label fw-semibold">{{ __('app.duration') }}</label>
            <input
                id="duree_minutes"
                type="number"
                name="duree_minutes"
                class="form-control @error('duree_minutes') is-invalid @enderror"
                value="{{ old('duree_minutes', $service->duree_minutes ?: 30) }}"
                min="5"
                step="5"
                required>
            @error('duree_minutes')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="prix" class="form-label fw-semibold">{{ __('app.price') }}</label>
            <input
                id="prix"
                type="number"
                name="prix"
                class="form-control @error('prix') is-invalid @enderror"
                value="{{ old('prix', $service->prix) }}"
                min="0"
                step="0.01">
            @error('prix')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
        <a href="{{ route('services.index') }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
    </div>
</form>
