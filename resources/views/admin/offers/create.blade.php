@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Create Offer</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('offers.store') }}" method="POST">
            @csrf

            @include('admin.offers._form')

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    Save Offer
                </button>
                <a href="{{ route('offers.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
