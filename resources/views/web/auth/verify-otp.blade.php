@extends('web.layouts.app')

@section('content')
<div class="container col-md-4 mt-5">
    <h4>Verify OTP</h4>

    <form method="POST" action="/verify-otp">
        @csrf
        <input type="text" name="otp" class="form-control mb-2" placeholder="Enter OTP">
        @error('otp') <div class="text-danger">{{ $message }}</div> @enderror
        <button class="btn btn-success w-100">Verify</button>
    </form>
</div>

@if(app()->environment('local') && session('dev_otp'))
    <div class="alert alert-warning text-center">
        <strong>DEV OTP:</strong> {{ session('dev_otp') }}
    </div>
@endif
@endsection
