@extends('layouts.app')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="card-header d-flex align-items-center">
                <h3 class="card-title mb-0">Accessory Booking Requests</h3>

               <form method="GET" class="ml-auto" id="searchForm">
                    <input type="text"
                        name="search"
                        id="search"
                        value="{{ request('search') }}"
                        class="form-control form-control-sm"
                        placeholder="Search name / mobile">
                </form>
            </div>

            <div class="card-body p-0">

                <table class="table table-bordered table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Accessory</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                            <th>Customer</th>
                            <th>Mobile</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td>{{ $booking->id }}</td>
                                <td>{{ $booking->accessory->name ?? '-' }}</td>
                                <td>{{ $booking->quantity }}</td>
                                <td>₹{{ number_format($booking->unit_price, 2) }}</td>
                                <td class="text-success">
                                    ₹{{ number_format($booking->total_amount, 2) }}
                                </td>
                                <td>{{ $booking->name }}</td>
                                <td>{{ $booking->mobile }}</td>
                                <td>{{$booking->address}}</td>
                                <td>
                                    <form method="POST"
                                          action="{{ route('accessories.bookings.status', $booking->id) }}">
                                        @csrf
                                        <select name="status"
                                                class="form-control form-control-sm"
                                                onchange="this.form.submit()">
                                            <option value="pending"
                                                {{ $booking->status=='pending'?'selected':'' }}>
                                                Pending
                                            </option>
                                            <option value="contacted"
                                                {{ $booking->status=='contacted'?'selected':'' }}>
                                                Contacted
                                            </option>
                                            <option value="closed"
                                                {{ $booking->status=='closed'?'selected':'' }}>
                                                Closed
                                            </option>
                                        </select>
                                    </form>
                                </td>

                                <td>{{ $booking->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">
                                    No booking requests found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>

            <div class="card-footer">
                {{ $bookings->links() }}
            </div>

        </div>
    </div>
</div>

<script>
document.getElementById('search').addEventListener('keyup', function () {
    document.getElementById('searchForm').submit();
});
</script>
@endsection
