@forelse($medecins as $u)
<tr>
    <td class="fw-semibold">{{ $u->name }}</td>
    <td class="text-muted">{{ $u->email }}</td>
    <td>{{ $u->specialite ?? __('app.not_available') }}</td>
    <td>{{ $u->phone ?? __('app.not_available') }}</td>
    <td>
        <a href="{{ route('users.edit', $u) }}" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-edit"></i>
        </a>

        <button class="btn btn-sm btn-outline-danger btn-delete-user"
            data-url="{{ route('users.destroy', $u) }}"
            data-name="{{ $u->name }}">
            <i class="fas fa-trash"></i>
        </button>
    </td>
</tr>
@empty
<tr>
    <td colspan="5" class="text-center text-muted py-3">
        {{ __('app.no_doctors') }}
    </td>
</tr>
@endforelse	
