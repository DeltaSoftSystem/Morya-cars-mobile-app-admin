@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <!-- Auction & Seller Info Card -->
    <div class="card mb-3" style="max-width: 800px; margin: auto;">
        <div class="card-header bg-primary text-white py-2">
            <h5 class="mb-0">Auction Details</h5>
        </div>
        <div class="card-body py-2 px-3">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Car:</strong> {{ $auction->title }}</p>
                    <p class="mb-1"><strong>Make:</strong> {{ $auction->make ?? '-' }}</p>
                    <p class="mb-1"><strong>Model:</strong> {{ $auction->model ?? '-' }}</p>
                    <p class="mb-1"><strong>Year:</strong> {{ $auction->year ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1"><strong>Seller:</strong> {{ $auction->user->name ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $auction->user->email ?? '-' }}</p>
                    <p class="mb-1"><strong>Mobile:</strong> {{ $auction->user->mobile ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Form Card -->
    <div class="card" style="max-width: 800px; margin: auto;">
        <div class="card-header bg-success text-white py-2">
            <h5 class="mb-0">Set Auction Schedule</h5>
        </div>
        <div class="card-body py-2 px-3">
            <form action="{{ route('auctions.schedule.submit', $auction->id) }}" method="POST">
                @csrf

                <div class="row mb-2">
                    <div class="col-md-6">
                        <label for="start_at" class="form-label small">Start Date & Time</label>
                        <input type="datetime-local" name="start_at" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-6">
                        <label for="end_at" class="form-label small">End Date & Time</label>
                        <input type="datetime-local" name="end_at" class="form-control form-control-sm" required>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-6">
                        <label for="base_price" class="form-label small">Base Price (₹)</label>
                       
    <input type="number" name="base_price" class="form-control form-control-sm" 
           value="{{ old('base_price', $auction->base_price ?? $auction->price ?? 0) }}" required>
    <small class="text-muted">Original Car Listing Price: ₹{{ number_format($auction->price ?? 0) }}</small>

                    </div>
                    <div class="col-md-6">
                        <label for="bid_increment" class="form-label small">Bid Increment (₹)</label>
                        <input type="number" name="bid_increment" class="form-control form-control-sm" value="{{ old('bid_increment', $auction->bid_increment ?? 10000) }}" required>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-2">
                    <a href="{{ route('auctions.requested') }}" class="btn btn-secondary btn-sm me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary btn-sm">Schedule Auction</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
