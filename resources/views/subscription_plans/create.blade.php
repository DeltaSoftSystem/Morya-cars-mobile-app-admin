@extends('layouts.app')

@section('title', 'Add Subscription Plan')

@section('content')
<div class="container-fluid">
    
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
    @endif

    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add New Subscription Plan</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.subscriptions.plans.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Plan Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="price">Price (₹)</label>
                            <input type="number" name="price" id="price" class="form-control" value="{{ old('price', 0) }}" step="0.01" required>
                        </div>

                        <div class="form-group">
                            <label for="validity_days">Validity (Days)</label>
                            <input type="number" name="validity_days" id="validity_days" class="form-control" value="{{ old('validity_days', 30) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="features">Features</label>
                            <textarea name="features" id="features" class="form-control">{{ old('features') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save Plan</button>
                        <a href="{{ route('admin.subscriptions.plans.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection
