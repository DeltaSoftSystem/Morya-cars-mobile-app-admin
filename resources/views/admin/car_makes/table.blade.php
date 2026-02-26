<table class="table table-bordered table-hover table-sm">
    <thead>
        <tr>
            <th>ID</th>
            <th>Make Name</th>
            <th>Total Models</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody id="makeBody">
        @foreach($makes as $make)
        <tr>
            <td>{{ $make->id }}</td>
            <td>{{ $make->name }}</td>
            <td>{{ $make->models->count() }}</td>
            <td>
                <a href="{{ route('car-makes.edit', $make->id) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('car-makes.destroy', $make->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this make?');">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-3" id="paginationLinks">
    {{ $makes->links('pagination::bootstrap-4') }}
</div>
