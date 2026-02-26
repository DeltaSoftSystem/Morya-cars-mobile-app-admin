@extends('layouts.app')

@section('title', 'Edit Car Make')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-md-6 m-auto">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Car Make</h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('car-makes.update', $car_make->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Make Name</label>
                            <input type="text" name="name" class="form-control" required value="{{ old('name', $car_make->name) }}">
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <select name="segment" class="form-control" required>
                            <option value="standard" {{ $car_make->segment == 'standard' ? 'selected' : '' }}>Standard</option>
                            <option value="premium" {{ $car_make->segment == 'premium' ? 'selected' : '' }}>Premium</option>
                            <option value="luxury" {{ $car_make->segment == 'luxury' ? 'selected' : '' }}>Luxury</option>
                        </select>

                        <div class="mt-4">
                        <button class="btn btn-primary">Update</button>
                        <a href="{{ route('car-makes.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
