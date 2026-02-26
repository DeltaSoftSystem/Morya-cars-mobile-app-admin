@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">Dealers</h3>
            </div>

            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <input type="text" id="dealer-search" class="form-control mb-3" placeholder="Search dealers by name / mobile / email" value="{{ request('search') }}">
                <div id="dealer-content">
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Business</th>
                <th>GST</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dealers as $dealer)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $dealer->name }}</td>
                    <td>{{ $dealer->mobile }}</td>
                    <td>{{ $dealer->email }}</td>
                    <td>{{ $dealer->dealerProfile->business_name ?? '-' }}</td>
                    <td>{{ $dealer->dealerProfile->gst_number ?? '-' }}</td>
                    <td>
                        <a href="{{ route('app-users.show', $dealer->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        No dealers found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $dealers->links('pagination::bootstrap-4') }}
</div>


            </div>
        </div>

    </div>
</div>

<script>
let dealerTimer = null;

document.getElementById('dealer-search')
    .addEventListener('keyup', function () {

    clearTimeout(dealerTimer);
    const query = this.value;

    dealerTimer = setTimeout(() => {
        fetch(`${window.location.pathname}?search=${encodeURIComponent(query)}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            const dom = new DOMParser().parseFromString(html, 'text/html');
            const content = dom.getElementById('dealer-content').innerHTML;
            document.getElementById('dealer-content').innerHTML = content;
        });
    }, 400);
});

// Pagination (AJAX)
document.addEventListener('click', function (e) {
    if (e.target.closest('#dealer-content .pagination a')) {
        e.preventDefault();

        fetch(e.target.closest('a').href, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            const dom = new DOMParser().parseFromString(html, 'text/html');
            const content = dom.getElementById('dealer-content').innerHTML;
            document.getElementById('dealer-content').innerHTML = content;
        });
    }
});
</script>

@endsection
