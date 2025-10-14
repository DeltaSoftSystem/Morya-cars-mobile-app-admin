@extends('layouts.app')

@section('title', 'Edit Subscription Plan')

@section('content')
<div class="container-fluid">
    <h1>Edit Subscription Plan</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.subscriptions.plans.update', $plan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Plan Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $plan->name) }}" required>
                </div>

                <div class="form-group">
                    <label for="price">Price ($)</label>
                    <input type="number" name="price" id="price" class="form-control" value="{{ old('price', $plan->price) }}" step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="validity_days">Validity (Days)</label>
                    <input type="number" name="validity_days" id="validity_days" class="form-control" value="{{ old('validity_days', $plan->validity_days) }}" required>
                </div>

                <div class="form-group">
                    <label for="features">Features</label>
                    <textarea name="features" id="features" class="form-control">{{ old('features', $plan->features) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Plan</button>
                <a href="{{ route('admin.subscriptions.plans.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
