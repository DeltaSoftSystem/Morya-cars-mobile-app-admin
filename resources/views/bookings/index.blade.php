@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Bookings</h3>
    </div>

    <div class="card-body">

        <!-- Live Search -->
        <input type="text" id="search" class="form-control mb-3" placeholder="Search by user, mobile, booking ID, UTR" value="{{ $search ?? '' }}">

        <!-- Bookings Table -->
        <div id="bookingsTable">
            <table class="table table-bordered table-hover table-sm">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>User</th>
                        <th>Car</th>
                        <th>Reg No</th>
                        <th>Booking Amount</th>
                        <th>Payment Mode</th>
                        <th>Payment Status</th>
                        <th>Booking Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody id="bookingsBody">
                    @foreach($bookings as $booking)
                    <tr>
                        <td>#{{ $booking->id }}</td>

                        <td>
                            {{ $booking->user->name }} <br>
                            <small>{{ $booking->user->mobile }}</small>
                        </td>

                        <td>
                            {{ $booking->carListing->title ?? 'N/A' }}
                        </td>
                         <td>
                            {{ $booking->carListing->registration_number ?? 'N/A' }}
                        </td>

                        <td>₹{{ number_format($booking->booking_amount) }}</td>

                        <td>{{ ucfirst($booking->payment_mode) }}</td>

                        <td>
                            @if($booking->payment_status == 'paid')
                        <span class="badge bg-success">Buyer Paid</span>

                    @elseif($booking->payment_status == 'pending')
                        <span class="badge bg-warning text-dark">Pending</span>

                    @elseif($booking->payment_status == 'seller_paid')
                        <span class="badge bg-info text-dark">Seller Paid</span>

                    @else
                        <span class="badge bg-danger">
                            {{ ucfirst($booking->payment_status) }}
                        </span>
                    @endif

                        </td>

                        <td>
                            @if($booking->booking_status == 'confirmed')
                                <span class="badge bg-success">Confirmed</span>
                            @elseif($booking->booking_status == 'cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($booking->booking_status) }}</span>
                            @endif
                        </td>

                        <td>{{ $booking->created_at->format('d M Y h:i A') }}</td>

                        <td>
                            <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="mt-3" id="paginationLinks">
                {{ $bookings->links('pagination::bootstrap-4') }}
            </div>
        </div>

    </div>
</div>

<!-- Live Search Script -->
<script>
document.getElementById('search').addEventListener('keyup', function () {
    let q = this.value;

    fetch("{{ route('bookings.index') }}?search=" + q, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.text())
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        document.getElementById('bookingsBody').innerHTML =
            doc.querySelector('#bookingsBody').innerHTML;

        document.getElementById('paginationLinks').innerHTML =
            doc.querySelector('#paginationLinks').innerHTML;
    });
});
</script>

@endsection
