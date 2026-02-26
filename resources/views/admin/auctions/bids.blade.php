@extends('layouts.app')

@section('content')
<div class="container">

    <div class="card shadow-sm mt-3">
        
         <div class="card-header d-flex align-items-center">
            <h3 class="card-title mb-0">Auction #{{ $auction->id }} - Bid History</h3>
            <div class="flex-grow-1"></div> <!-- pushes button to right -->
            <a href="{{ route('auctions.history') }}" class="btn btn-sm btn-primary">← Back to History</a>
        </div>

        <div class="card-body">

            <!-- Auction & Car Info -->
            <div class="mb-3">
                <h5 class="mb-2"><strong>Car:</strong> {{ $auction->carListing->title ?? 'N/A' }}</h5>
                <p class="mb-1"><strong>Seller:</strong> {{ $auction->user->name ?? 'Seller Deleted' }} ({{ $auction->user->phone_no ?? '-' }})</p>
                <p class="mb-1"><strong>Final Price:</strong> ₹{{ number_format($auction->final_price ?? 0) }}</p>
                <p class="mb-0">
                    <strong>Ended At:</strong> 
                    {{ $auction->ended_on ? \Carbon\Carbon::parse($auction->ended_on)->format('d M Y h:i A') : 'N/A' }}
                </p>
            </div>

            <hr>

            <h5 class="mb-3">📜 Bid History</h5>

            <table class="table table-hover table-bordered text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Bidder</th>
                        <th>Amount (₹)</th>
                        <th>Bid Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($auction->bids as $bid)
                    <tr class="{{ $bid->amount == $auction->final_price ? 'table-success fw-bold' : '' }}">
                        <td>{{ $bid->id }}</td>
                        <td>{{ $bid->user->name ?? 'Unknown User' }}</td>
                        <td>₹{{ number_format($bid->amount) }}</td>
                        <td>{{ \Carbon\Carbon::parse($bid->bid_at)->format('d M Y h:i A') }}</td>
                        <td>
                            @if($bid->amount == $auction->final_price)
                                <span class="badge bg-success">Winner 🏆</span>
                            @else
                                <span class="badge bg-secondary">Placed</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-muted py-4">No bids were placed for this auction.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

</div>
@endsection
