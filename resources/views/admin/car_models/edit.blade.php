@extends('layouts.app')

@section('title', 'Edit Car Model')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-md-6 m-auto">

        
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Car Model</h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('car-models.update', $car_model->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Select Make</label>
                            <select name="make_id" class="form-control" required>
                                @foreach($makes as $make)
                                    <option value="{{ $make->id }}"
                                        {{ $car_model->make_id == $make->id ? 'selected' : '' }}>
                                        {{ $make->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Model Name</label>
                            <input type="text"
                                name="name"
                                class="form-control"
                                value="{{ old('name', $car_model->name) }}"
                                required>
                        </div>

                        <button class="btn btn-primary">Update</button>
                        <a href="{{ route('car-models.index') }}" class="btn btn-secondary">Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
