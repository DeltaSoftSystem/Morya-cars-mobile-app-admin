@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header"><h4>Auction Deposit Requests</h4></div>

    <div class="card-body table-responsive p-0">
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Auction</th>
                    <th>Amount</th>
                    <th>Proof</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($deposits as $d)
                <tr>
                    <td>{{ $d->user->name ?? 'User Deleted' }}</td>
                    <td>#{{ $d->auction_id }}</td>
                    <td>₹{{ number_format($d->deposit_amount) }}</td>
                    <td>
                        @if($d->payment_proof)
                        <a href="{{ asset('storage/'.$d->payment_proof) }}" target="_blank" class="btn btn-sm btn-info">View</a>
                        @else
                        No File
                        @endif
                    </td>
                    <td>{{ ucfirst($d->status) }}</td>
                    <td>
                        @if($d->status == 'pending')
                            <div class="btn-group" role="group">
                                <form method="POST" action="{{ route('deposits.approve',$d->id) }}">
                                    @csrf
                                    <button class="btn btn-success btn-sm">Approve</button>
                                </form>

                                <form method="POST" action="{{ route('deposits.reject',$d->id) }}">
                                    @csrf
                                    <button class="btn btn-danger btn-sm">Reject</button>
                                </form>
                            </div>
                        @elseif($d->status == 'approved')
                            <div class="btn-group" role="group">
                                <form method="POST" action="{{ route('deposits.refund',$d->id) }}">
                                    @csrf
                                    <button class="btn btn-warning btn-sm">Refund</button>
                                </form>
                            </div>
                        @endif

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $deposits->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection
