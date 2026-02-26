<table class="table table-bordered table-hover table-sm">
    <thead>
        <tr>
            <th>ID</th>
            <th>Model Name</th>
            <th>Make</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody id="modelBody">
        @foreach($models as $model)
        <tr>
            <td>{{ $model->id }}</td>
            <td>{{ $model->name }}</td>
            <td>{{ $model->make->name }}</td>

            <td>
                <a href="{{ route('car-models.edit', $model->id) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>

                <form action="{{ route('car-models.destroy', $model->id) }}"
                      method="POST" style="display:inline"
                      onsubmit="return confirm('Delete this model?');">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-3" id="paginationLinks">
    {{ $models->links('pagination::bootstrap-4') }}
</div>
