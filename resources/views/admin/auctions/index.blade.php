@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">

        <div class="card">
    <div class="card-header d-flex align-items-center">
    <h3 class="card-title mb-0">Auction History</h3>
    <div class="flex-grow-1"></div> <!-- pushes button to right -->
    <a href="{{ route('auctions.selectCar') }}" class="btn btn-primary">Create Auction</a>
</div>



    <div class="card-body">

        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>Auction ID</th>
                    <th>Car Title</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Year</th>
                    <th>Base Price</th>
                    <th>Bid Increment</th>
                    <th>Start At</th>
                    <th>End At</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($auctions as $auction)
                <tr>
                    <td>{{ $auction->id }}</td>
                    <td>{{ $auction->car->title ?? 'N/A' }}</td>
                    <td>{{ $auction->car->make ?? '' }}</td>
                    <td>{{ $auction->car->model ?? '' }}</td>
                    <td>{{ $auction->car->year ?? '' }}</td>
                    <td>{{ $auction->base_price }}</td>
                    <td>{{ $auction->bid_increment }}</td>
                    <td>{{ $auction->start_at }}</td>
                    <td>{{ $auction->end_at }}</td>
                    <td>
                        @if($auction->car)
                            {{ ucfirst($auction->car->auction_status) }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
    @if($auction->car)
        @if($auction->car->auction_status === 'pending')
            <form action="{{ route('auctions.approve', $auction->id) }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-success btn-sm">Approve</button>
            </form>

            <form action="{{ route('auctions.reject', $auction->id) }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-danger btn-sm">Reject</button>
            </form>
        @else
            <span class="text-muted">{{ ucfirst($auction->car->auction_status) }}</span>
        @endif
    @endif
</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $auctions->links('pagination::bootstrap-4') }}
    </div>
        </div>
    </div>
</div>
@endsection
