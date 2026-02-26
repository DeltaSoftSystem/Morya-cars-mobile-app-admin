@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">Dealer KYC Verification</h3>
            </div>

            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <input type="text" id="kyc-search" class="form-control mb-3" placeholder="Search dealer by name / mobile / email" value="{{ request('search') }}">
                <div id="kyc-content">

    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>#</th>
                <th>Dealer</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Document Type</th>
                <th>Document</th>
                <th>Status</th>
                <th width="170">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($documents as $doc)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $doc->user->name ?? '-' }}</td>
                    <td>{{ $doc->user->mobile ?? '-' }}</td>
                    <td>{{ $doc->user->email ?? '-' }}</td>
                    <td>{{ strtoupper(str_replace('_',' ', $doc->document_type)) }}</td>
                    <td>
                        <a href="{{ asset('storage/'.$doc->document_path) }}"
                           target="_blank"
                           class="btn btn-info btn-sm">
                            View
                        </a>
                    </td>
                    <td>
                        @if($doc->status === 'approved')
                            <span class="badge bg-success">Approved</span>
                        @elseif($doc->status === 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @else
                            <span class="badge bg-warning">Pending</span>
                        @endif
                    </td>
                    <td>
                        @if($doc->status === 'pending')
                            <form method="POST"
                                  action="{{ route('admin.dealers.kyc.approve', $doc->id) }}"
                                  class="d-inline">
                                @csrf
                                <button class="btn btn-success btn-sm">
                                    Approve
                                </button>
                            </form>

                            <button class="btn btn-danger btn-sm"
                                    data-toggle="modal"
                                    data-target="#rejectModal{{ $doc->id }}">
                                Reject
                            </button>

                            @include('admin.dealers._reject', ['doc' => $doc])
                        @else
                            —
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">
                        No KYC records found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $documents->links('pagination::bootstrap-4') }}

</div>

            </div>
        </div>

    </div>
</div>


<script>
let kycTimer = null;
const searchInput = document.getElementById('kyc-search');
const contentDiv = document.getElementById('kyc-content');

// Live search
searchInput.addEventListener('keyup', function () {
    clearTimeout(kycTimer);
    const query = this.value;

    kycTimer = setTimeout(() => {
        fetch(`${window.location.pathname}?search=${encodeURIComponent(query)}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(html => {
            const dom = new DOMParser().parseFromString(html, 'text/html');
            contentDiv.innerHTML = dom.getElementById('kyc-content').innerHTML;
        });
    }, 400);
});

// Pagination (AJAX)
document.addEventListener('click', function (e) {
    const link = e.target.closest('#kyc-content .pagination a');
    if (!link) return;

    e.preventDefault();

    fetch(link.href, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.text())
    .then(html => {
        const dom = new DOMParser().parseFromString(html, 'text/html');
        contentDiv.innerHTML = dom.getElementById('kyc-content').innerHTML;
    });
});
</script>

@endsection
