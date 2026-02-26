@extends('layouts.app')

@section('content')
<div class="container">

    <h5 class="mb-3">Edit Service</h5>

    <div class="card">
        <div class="card-body">

            <form method="POST"
                  action="{{ route('services.update', $service->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>Service Name</label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name', $service->name) }}"
                           required>
                </div>

                <div class="mb-3">
                    <label>Status</label>
                    <select name="is_active" class="form-control">
                        <option value="1" {{ $service->is_active ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="0" {{ !$service->is_active ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>
                </div>

                <button class="btn btn-primary">Update</button>
                <a href="{{ route('services.index') }}"
                   class="btn btn-light">Cancel</a>
            </form>

        </div>
    </div>

</div>
@endsection
