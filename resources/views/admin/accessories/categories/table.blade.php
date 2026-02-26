@forelse($categories as $category)
<tr>
    <td>{{ $category->name }}</td>
    <td>
        @if($category->status)
            <span class="badge badge-success">Active</span>
        @else
            <span class="badge badge-secondary">Inactive</span>
        @endif
    </td>
    <td>
        <button class="btn btn-sm btn-primary"
            onclick="editCategory({{ $category }})">
            <i class="fas fa-edit"></i>
        </button>

        <form action="{{ route('accessory-categories.destroy', $category->id) }}"
              method="POST" class="d-inline"
              onsubmit="return confirm('Delete category?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-danger">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    </td>
</tr>
@empty
<tr>
    <td colspan="3" class="text-center text-muted">
        No categories found
    </td>
</tr>
@endforelse
