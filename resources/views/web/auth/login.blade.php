@extends('web.layouts.app')

@section('content')
<div class="container col-md-4 mt-5">
    <h4>Login with OTP</h4>

    <form method="POST" action="/send-otp">
        @csrf
        <input type="text" name="mobile" class="form-control mb-2" placeholder="Mobile Number">
        @error('mobile') <div class="text-danger">{{ $message }}</div> @enderror
        <button class="btn btn-dark w-100">Send OTP</button>
    </form>
</div>
@endsection
