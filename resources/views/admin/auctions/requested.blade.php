@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Requested Auctions</h3>
    </div>

    <div class="card-body">

        <!-- Search -->
        <input type="text" id="search" class="form-control mb-3" placeholder="Search by user, car" value="{{ $search ?? '' }}">

        <table class="table table-bordered table-hover table-sm">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Seller</th>
                    <th>Car</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($carListings as $car)
<tr>
    <td>#{{ $car->id }}</td>
    <td>
        {{ $car->user->name ?? 'N/A' }} <br>
        <small>{{ $car->user->mobile ?? '' }}</small>
    </td>
    <td>{{ $car->title }}</td>
    <td>
    @php
        $statusClass = match($car->auction_status) {
            'requested' => 'bg-warning',
            'approved'  => 'bg-success',
            'rejected'  => 'bg-danger',
            'scheduled' => 'bg-primary',
            default     => 'bg-secondary',
        };
    @endphp

    <span class="badge {{ $statusClass }}">
        {{ ucfirst($car->auction_status) }}
    </span>
</td>

    <td>
        <form action="{{ route('auctions.approve', $car->id) }}" method="POST" class="d-inline">
            @csrf
            <button class="btn btn-success btn-sm">Approve</button>
        </form>

        <form action="{{ route('auctions.reject', $car->id) }}" method="POST" class="d-inline">
            @csrf
            <button class="btn btn-danger btn-sm">Reject</button>
        </form>
        {{-- Schedule Auction Button for Approved --}}
    @if($car->auction_status == 'approved')
        <a href="{{ route('auctions.schedule.form', $car->id) }}" class="btn btn-warning btn-sm">
            Schedule Auction
        </a>
    @endif
    </td>
</tr>
@endforeach

            </tbody>
        </table>

        <div class="mt-3">
            {{ $carListings->links('pagination::bootstrap-4') }}
        </div>

    </div>
</div>
@endsection
