@extends('layouts.app')

@section('content')
<div class="container">

    <div class="card shadow-sm mt-3">
        <div class="card-header d-flex align-items-center">
            <h3 class="card-title mb-0">Auction #{{ $auction->id }} - Details</h3>
            <div class="flex-grow-1"></div> <!-- pushes button to right -->
            <a href="{{ route('auctions.history') }}" class="btn btn-primary btn-sm">← Back</a>
        </div>

        <div class="card-body">

            <!-- 🔹 CAR & SELLER INFO -->
            <h5><strong>Car:</strong> {{ $auction->carListing->title ?? 'N/A' }}</h5>
            <p><strong>Vehicle No:</strong> {{ $auction->carListing->registration_number ?? 'Not Available' }}</p>
            
            <p><strong>Seller:</strong> 
                {{ $auction->carListing->user->name ?? 'Seller Deleted' }} <br>
                <small>{{ $auction->carListing->user->mobile ?? '-' }}</small>
            </p>

            <!-- 🔹 FINAL PRICE + TIME -->
            <p><strong>Final Price:</strong> ₹{{ number_format($auction->result->current_bid ?? $auction->final_price ?? 0) }}</p>
            <p><strong>Ended At:</strong>
                {{ $auction->result && $auction->result->end_at
                    ? \Carbon\Carbon::parse($auction->result->end_at)->format('d M Y h:i A')
                    : 'N/A'
                }}
            </p>

            <hr>

            <!-- 🔹 BID HISTORY TABLE -->
            <h5 class="mb-3">📜 Bid History</h5>
            <table class="table table-sm table-hover table-bordered text-center align-middle">
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
                    <tr class="{{ $bid->amount == ($auction->result->current_bid ?? 0) ? 'table-success fw-bold' : '' }}">
                        <td>{{ $bid->id }}</td>
                        <td>@if($auction->result && $auction->result->winnerUser)
    <strong>{{ $auction->result->winnerUser->name }}</strong><br>
    <small>
        📞 {{ $auction->result->winnerUser->mobile ?? '-' }} <br>
        ✉️ {{ $auction->result->winnerUser->email ?? '-' }}
    </small>
@elseif($auction->result && $auction->result->winner_name)
    <strong>{{ $auction->result->winner_name }}</strong><br>
    <small class="text-muted">Contact not available</small>
@else
    <span class="badge badge-secondary">No Winner</span>
@endif

                        </td>
                        <td>₹{{ number_format($bid->amount) }}</td>
                        <td> {{ $bid->bid_at 
                            ? \Carbon\Carbon::parse($bid->bid_at)->format('d M Y h:i A') 
                            : '-' 
                        }}</td>
                        <td>
                            @if($bid->amount == ($auction->result->current_bid ?? 0))
                                <span class="badge bg-success">Winner 🏆</span>
                            @else
                                <span class="badge bg-secondary">Placed</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-4 text-muted">No bids found.</td></tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

</div>
@endsection
