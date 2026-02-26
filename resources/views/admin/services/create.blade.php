@extends('layouts.app')

@section('content')
<div class="container">

    <h5 class="mb-3">
        {{ isset($service) ? 'Edit Service' : 'Add Service' }}
    </h5>

    <div class="card">
        <div class="card-body">

            <form method="POST"
                action="{{ isset($service)
                    ? route('services.update', $service->id)
                    : route('services.store') }}">

                @csrf
                @if(isset($service)) @method('PUT') @endif

                <div class="mb-3">
                    <label>Service Name</label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name', $service->name ?? '') }}"
                           required>
                </div>

                <div class="mb-3">
                    <label>Status</label>
                    <select name="is_active" class="form-control">
                        <option value="1" {{ (old('is_active', $service->is_active ?? 1) == 1) ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="0" {{ (old('is_active', $service->is_active ?? 1) == 0) ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>
                </div>

                <button class="btn btn-primary">
                    {{ isset($service) ? 'Update' : 'Save' }}
                </button>

                <a href="{{ route('services.index') }}"
                   class="btn btn-light">Cancel</a>

            </form>

        </div>
    </div>
</div>
@endsection
