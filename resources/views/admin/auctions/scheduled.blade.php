@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Scheduled Auctions</h3>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-hover table-sm">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Car</th>
                    <th>Start At</th>
                    <th>End At</th>
                    <th>Base Price</th>
                    <th>Bid Increment</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($auctions as $auction)
                <tr>
                    <td>#{{ $auction->id }}</td>
                    <td>{{ $auction->carListing->title ?? 'N/A' }}</td>
                    <td>{{ $auction->start_at ?? '-' }}</td>
                    <td>{{ $auction->end_at ?? '-' }}</td>
                    <td>{{ $auction->base_price ?? '-' }}</td>
                    <td>{{ $auction->bid_increment ?? '-' }}</td>
                    <td>
                        @if($auction->carListing->auction_status == 'approved')
                            <a href="{{ route('auctions.schedule.form', $auction->id) }}" class="btn btn-warning btn-sm">
                                Schedule
                            </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $auctions->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection
