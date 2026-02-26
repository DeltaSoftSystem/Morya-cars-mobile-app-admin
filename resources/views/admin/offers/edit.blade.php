@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Edit Offer</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('offers.update', $offer) }}" method="POST">
            @csrf
            @method('PUT')

            @include('admin.offers._form', ['offer' => $offer])

            <div class="mt-3">
                <button type="submit" class="btn btn-success">
                    Update Offer
                </button>
                <a href="{{ route('offers.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
