@extends('layouts.app')

@section('title', 'Add Car Model')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-md-6 m-auto">

       
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add Car Model</h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('car-models.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label>Select Make</label>
                            <select name="make_id" class="form-control" required>
                                <option value="">-- Select Make --</option>
                                @foreach($makes as $make)
                                    <option value="{{ $make->id }}">{{ $make->name }}</option>
                                @endforeach
                            </select>
                            @error('make_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group">
                            <label>Model Name</label>
                            <input type="text" name="name" class="form-control" required>
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <button class="btn btn-success">Save</button>
                        <a href="{{ route('car-models.index') }}" class="btn btn-secondary">Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
