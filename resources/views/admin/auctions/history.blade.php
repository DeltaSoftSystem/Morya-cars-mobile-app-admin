@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Auction History</h3>
    </div>

    <div class="card-body">

        <!-- Live Search -->
        <input type="text" id="search" class="form-control mb-3" placeholder="Search by car, seller, ID" value="{{ $search ?? '' }}">

        <div id="auctionTable">
            <table class="table table-bordered table-hover table-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Car</th>
                        <th>Seller</th>
                        <th>Winner</th>
                        <th>Final Price</th>
                        <th>Total Bids</th>
                        <th>Ended At</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody id="auctionBody">
            @foreach($auctions as $auction)
            <tr>
                <td>#{{ $auction->id }}</td>

                <td>{{ $auction->carListing->title ?? 'No Car' }}</td>

                <td>
                    {{ $auction->carListing->user->name ?? 'Seller Deleted' }} <br>
                    <small>{{ $auction->carListing->user->mobile ?? '-' }}</small>
                </td>

                <td>
                    @if($auction->winner_name)
                        <strong>{{ $auction->winner_name }}</strong>
                    @elseif($auction->resultWinner)
                        <strong>{{ $auction->resultWinner->name }}</strong><br>
                        <small>{{ $auction->resultWinner->mobile ?? '-' }}</small>
                    @else
                        <span class="badge badge-secondary">No Winner</span>
                    @endif
                </td>

                <td>₹{{ number_format($auction->current_bid ?? 0) }}</td> <!-- Correct Final Price -->

                <td>{{ $auction->bids->count() }}</td>

                <td>{{ $auction->ended_on ? \Carbon\Carbon::parse($auction->ended_on)->format('d M Y h:i A') : 'N/A' }}</td>

                <td>
                    
                    <a href="{{ route('auctions.show', $auction->id) }}" class="btn btn-sm btn-info">
                        <i class="fas fa-eye"></i> View
                    </a>
                </td>
            </tr>
            @endforeach
            </tbody>

            </table>

            <div class="mt-3" id="paginationLinks">
                {{ $auctions->links('pagination::bootstrap-4') }}
            </div>
        </div>

    </div>
</div>

<script>
document.getElementById('search').addEventListener('keyup', function () {
    let q = this.value;

    fetch("{{ route('auctions.history') }}?search=" + q, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.text())
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        document.getElementById('auctionBody').innerHTML =
            doc.querySelector('#auctionBody').innerHTML;

        document.getElementById('paginationLinks').innerHTML =
            doc.querySelector('#paginationLinks').innerHTML;
    });
});
</script>

@endsection
