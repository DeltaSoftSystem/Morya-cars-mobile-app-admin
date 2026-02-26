@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-8 mx-auto">

<div class="card">
    <div class="card-header d-flex align-items-center">
    <h3 class="card-title mb-0">Car Makes</h3>

    <a href="{{ route('car-makes.create') }}"
       class="btn btn-sm btn-success ml-auto">
        <i class="fas fa-plus"></i> Add Make
    </a>
</div>



    <div class="card-body">

        <!-- Live Search Input -->
        <input type="text" id="search" class="form-control mb-3"
               placeholder="Search make name"
               value="{{ $search ?? '' }}">

        <!-- Table Wrapper (THIS ENTIRE DIV WILL BE REPLACED ON AJAX) -->
        <div id="makeTable">

            <table class="table table-bordered table-hover table-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Make Name</th>
                        <th>Total Models</th>
                        <th>Segment</th>
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
                                <span class="badge 
                                    {{ $make->segment == 'luxury' ? 'badge-dark' : 
                                    ($make->segment == 'premium' ? 'badge-warning' : 'badge-secondary') }}">
                                    {{ ucfirst($make->segment) }}
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('car-makes.edit', $make->id) }}"
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                <form action="{{ route('car-makes.destroy', $make->id) }}"
                                      method="POST" style="display:inline"
                                      onsubmit="return confirm('Delete this make?');">
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

            <!-- Pagination -->
            <div class="mt-3" id="paginationLinks">
                {{ $makes->appends(['search' => $search])->links('pagination::bootstrap-4') }}
            </div>

        </div>
    </div>
</div>

    </div>
</div>

<!-- AJAX Search + Pagination -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const search = document.getElementById('search');
    const tableWrapper = document.getElementById('makeTable');

    // Load HTML into table wrapper
    function loadTable(url) {
        fetch(url, { headers: { "X-Requested-With": "XMLHttpRequest" } })
            .then(res => res.text())
            .then(html => {
                // Replace whole table wrapper
                tableWrapper.innerHTML = html;

                attachPaginationEvents(); // re-activate pagination links
            });
    }

    // Attach click event to pagination links
    function attachPaginationEvents() {
        document.querySelectorAll('#paginationLinks a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                let url = this.href;

                // Preserve search parameter on pagination
                if (search.value.trim() !== '') {
                    const q = encodeURIComponent(search.value);
                    url += (url.includes('?') ? '&' : '?') + "search=" + q;
                }

                loadTable(url);
            });
        });
    }

    // Search event
    search.addEventListener('keyup', function () {
        const q = encodeURIComponent(this.value.trim());
        const url = "{{ route('car-makes.index') }}?search=" + q;

        loadTable(url);
    });

    attachPaginationEvents(); // first load
});
</script>
@endsection
