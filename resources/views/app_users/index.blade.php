@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">App Users</h3>
    </div>
    <div class="card-body">

        <!-- Live Search Input -->
        <input type="text" id="search" class="form-control mb-3" placeholder="Search by name, email, or mobile" value="{{ $search ?? '' }}">

        <!-- Users Table -->
        <!-- Table wrapper -->
<div id="usersTable">
    <table class="table table-bordered table-hover table-sm">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Role</th>
                <th>Status</th>
                <th>Mobile Verified</th>
                <th>Email Verified</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="usersBody">
            @foreach($appUsers as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->mobile }}</td>
                <td>{{ ucfirst($user->role) }}</td>
                <td>{{ ucfirst($user->status) }}</td>
                <td>
                    @if($user->is_mobile_verified)
                        <span class="badge bg-success">Yes</span>
                    @else
                        <span class="badge bg-warning">No</span>
                    @endif
                </td>
                <td>
                    @if($user->is_email_verified)
                        <span class="badge bg-success">Yes</span>
                    @else
                        <span class="badge bg-warning">No</span>
                    @endif
                </td>
                <td>
                    <form action="{{ route('app-users.toggleStatus', $user->id) }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-warning">
                            {{ $user->status === 'active' ? 'Block' : 'Activate' }}
                        </button>
                    </form>
                    <a href="{{ route('app-users.show', $user->id) }}" class="btn btn-sm btn-info">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-3" id="paginationLinks">
        {{ $appUsers->links('pagination::bootstrap-4') }}
    </div>
</div>

    </div>
</div>

<!-- Live Search Script (AJAX) -->
<script>
   const searchInput = document.getElementById('search');

searchInput.addEventListener('keyup', function() {
    const query = this.value;

    fetch("{{ route('app-users.index') }}?search=" + query, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.text())
    .then(html => {
        // Only replace tbody and pagination
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        // Replace tbody
        document.getElementById('usersBody').innerHTML = doc.querySelector('#usersBody').innerHTML;
        // Replace pagination
        document.getElementById('paginationLinks').innerHTML = doc.querySelector('#paginationLinks').innerHTML;
    });
});

</script>
@endsection
