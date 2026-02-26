@extends('layouts.app')

@section('title', 'Add Car Make')

@section('content')
<div class="container-fluid">

<div class="row">
    <div class="col-md-6 m-auto">
        
       
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Add Car Make</h3>
        </div>

        <div class="card-body">
            <form action="{{ route('car-makes.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Make Name</label>
                    <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="form-group">
                    <label>Car Segment</label>
                    <select name="segment" class="form-control" required>
                        <option value="standard" {{ old('segment') == 'standard' ? 'selected' : '' }}>
                            Standard
                        </option>
                        <option value="premium" {{ old('segment') == 'premium' ? 'selected' : '' }}>
                            Premium
                        </option>
                        <option value="luxury" {{ old('segment') == 'luxury' ? 'selected' : '' }}>
                            Luxury
                        </option>
                    </select>
                    @error('segment') <small class="text-danger">{{ $message }}</small> @enderror
                </div>


                <button class="btn btn-success">Save</button>
                <a href="{{ route('car-makes.index') }}" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
 </div>    
</div>

</div>
@endsection
