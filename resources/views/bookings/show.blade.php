@extends('layouts.app')

@section('content')

@php
    /* =======================
       BUYER CALCULATIONS
    ======================== */
    $buyerPaid = $booking->paymentProofs
        ->where('status', 'verified')
        ->sum('amount');

    $buyerRemaining = max(0, $booking->booking_amount - $buyerPaid);

    /* =======================
       SELLER CALCULATIONS
    ======================== */
    $sellerPaid = $booking->sellerPayments->sum('amount');
    $sellerRemaining = max(0, $booking->booking_amount - $sellerPaid);
@endphp

{{-- ================= CAR DETAILS ================= --}}
<div class="card mb-3">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
        <div class="mb-0">Car Details</div>

        <a href="{{ route('bookings.index') }}"
        class="btn btn-secondary btn-sm">
            ← Back to Bookings
        </a>
</div>
    </div>
    <div class="card-body">

        @if($booking->carListing)
        <div class="alert alert-light py-2 mb-3">
            <small>
                <strong>Car:</strong>
                {{ $booking->carListing->make }}
                {{ $booking->carListing->model }}
                {{ $booking->carListing->variant }}
                ({{ $booking->carListing->year }})
                |
                <strong>Reg No:</strong> {{ $booking->carListing->registration_number }}
                |
                <strong>Fuel:</strong> {{ ucfirst($booking->carListing->fuel_type) }}
                |
                <strong>KM:</strong> {{ number_format($booking->carListing->km_driven) }}
                |
                <strong>Price:</strong> ₹{{ number_format($booking->carListing->price) }}
            </small>
        </div>
        @endif

        @if(in_array($booking->booking_status, ['pending_payment', 'confirmed']))
        <button class="btn btn-outline-danger btn-sm"
                data-toggle="modal"
                data-target="#cancelBookingModal">
            <i class="fas fa-times"></i> Cancel Booking
        </button>
        @endif

    </div>
</div>

{{-- ================= CANCEL MODAL ================= --}}
<div class="modal fade" id="cancelBookingModal">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST">
                @csrf
                <div class="modal-header py-2">
                    <h6 class="modal-title">Cancel Booking</h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <textarea name="admin_comment"
                              class="form-control form-control-sm"
                              rows="3"
                              required
                              placeholder="Reason for cancellation"></textarea>
                </div>
                <div class="modal-footer py-2">
                    <button class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    <button class="btn btn-danger btn-sm">Confirm Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ================= BUYER PAYMENT ================= --}}
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">
            <span class="badge badge-success">Buyer</span> Payment Proofs
        </h5>
    </div>

    <div class="card-body">

        <div class="alert alert-info py-2">
            <small>
                <strong>Total Paid:</strong> ₹{{ number_format($buyerPaid) }}
                |
                <strong>Remaining:</strong> ₹{{ number_format($buyerRemaining) }}
            </small>
        </div>

        @if($booking->paymentProofs->count())
        <table class="table table-bordered table-sm">
            <thead class="thead-light">
                <tr>
                    <th>File</th>
                    <th>UTR</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th width="160">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($booking->paymentProofs as $proof)
                <tr>
                    <td>
                        <a href="{{ asset('storage/'.$proof->file_path) }}" target="_blank">View</a>
                    </td>
                    <td>{{ $proof->utr_number ?? '-' }}</td>
                    <td>₹{{ number_format($proof->amount) }}</td>
                    <td>{{ $proof->payment_date }}</td>
                    <td>
                        <span class="badge badge-{{ $proof->status=='verified'?'success':($proof->status=='pending'?'warning':'danger') }}">
                            {{ ucfirst($proof->status) }}
                        </span>
                    </td>
                    <td>
    @if($proof->status === 'pending')
        <form action="{{ route('payment-proof.verify',$proof->id) }}"
              method="POST" class="d-inline">@csrf
            <button class="btn btn-success btn-sm">Verify</button>
        </form>

        <form action="{{ route('payment-proof.reject',$proof->id) }}"
              method="POST" class="d-inline">@csrf
            <button class="btn btn-warning btn-sm">Reject</button>
        </form>

        <form action="{{ route('payment-proof.delete',$proof->id) }}"
              method="POST" class="d-inline"
              onsubmit="return confirm('Delete this payment proof?');">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm">Delete</button>
        </form>
    @else
        <span class="text-muted">Locked</span>
    @endif
</td>

                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        {{-- Upload --}}
        @if($booking->booking_status !== 'cancelled' && $buyerRemaining > 0)
        <hr>
        <form action="{{ route('bookings.upload-proof',$booking->id) }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="col-md-3">
                    <input type="file" name="file" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="utr_number" class="form-control" placeholder="UTR">
                </div>
                <div class="col-md-3">
                    <input type="number" name="amount" class="form-control"
                           min="1" max="{{ $buyerRemaining }}" required placeholder="Amount">
                </div>
                <div class="col-md-3">
                    <input type="date" name="payment_date" class="form-control">
                </div>
            </div>
            <button class="btn btn-primary btn-sm mt-2">Upload Proof</button>
        </form>
        @endif

    </div>
</div>

{{-- ================= SELLER PAYMENT ================= --}}
@if(auth()->user()->role === 'admin')
<div class="alert alert-warning small">
    booking_status: <strong>{{ $booking->booking_status }}</strong> |
    sellerRemaining: <strong>{{ $sellerRemaining }}</strong> |
    buyerRemaining: <strong>{{ $buyerRemaining }}</strong>
</div>

<div class="card mt-3">
    <div class="card-header">
        <strong><span class="badge badge-info">Seller</span> Payments</strong>
    </div>
    <div class="card-body">

        @if($booking->sellerPayments->count())
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Mode</th>
                    <th>Proof</th>
                </tr>
            </thead>
            <tbody>
                @foreach($booking->sellerPayments as $pay)
                <tr>
                    <td>{{ $pay->payment_date }}</td>
                    <td>₹{{ number_format($pay->amount) }}</td>
                    <td>{{ strtoupper($pay->payment_mode) }}</td>
                    <td>
                        <a href="{{ asset('storage/'.$pay->proof_file) }}" target="_blank">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <small class="text-muted">
            Paid: ₹{{ number_format($sellerPaid) }} |
            Remaining: ₹{{ number_format($sellerRemaining) }}
        </small>

        @if(
    auth()->user()->role === 'admin' &&
    in_array($booking->booking_status, ['confirmed']) &&
    $buyerRemaining == 0 &&
    $sellerRemaining > 0
)
        <br>
        <button class="btn btn-outline-success btn-sm mt-2"
        data-toggle="modal"
        data-target="#sellerPaymentModal">
    Add Seller Payment
</button>

        @endif

    </div>
</div>
@endif

{{-- ================= SELLER MODAL ================= --}}
<div class="modal fade" id="sellerPaymentModal">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('seller-payment.store',$booking->id) }}"
                  method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header py-2">
                    <h6>Seller Payment</h6>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="number" name="amount"
                           max="{{ $sellerRemaining }}"
                           class="form-control mb-2" placeholder="Amount" required>
                    <select name="payment_mode" class="form-control mb-2" required>
                        <option value="">Mode</option>
                        <option value="upi">UPI</option>
                        <option value="bank">Bank</option>
                        <option value="cash">Cash</option>
                    </select>
                    <input type="text" name="transaction_ref" class="form-control mb-2" placeholder="Txn Ref">
                    <input type="date" name="payment_date" class="form-control mb-2" required>
                    <input type="file" name="proof_file" class="form-control mb-2" required>
                    <textarea name="admin_comment" class="form-control" rows="2" placeholder="Remark"></textarea>
                </div>
                <div class="modal-footer py-2">
                    <button class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    <button class="btn btn-success btn-sm">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
