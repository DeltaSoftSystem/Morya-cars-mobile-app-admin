@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">User Details</h3>
    </div>
    <div class="card-body">
        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
        <p><strong>Mobile:</strong> {{ $user->mobile }}</p>
        <p><strong>Mobile Verified:</strong> {{ $user->is_mobile_verified ? 'Yes' : 'No' }}</p>
        <p><strong>Email Verified:</strong> {{ $user->is_email_verified ? 'Yes' : 'No' }}</p>
        <p><strong>OTP:</strong> {{ $user->otp ?? 'N/A' }}</p>

        <p><strong>Status:</strong> {{ ucfirst($user->status) }}</p>
        <p><strong>Created At:</strong> {{ $user->created_at->format('d M Y, H:i') }}</p>
    </div>
</div>
@endsection
