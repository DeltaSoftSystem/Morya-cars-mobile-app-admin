@extends('layouts.app') 

@section('title', 'Subscription Plans')

@section('content')
<div class="container-fluid">
    

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
         <div class="card-header">
            <div class="row">
                <div class="col-sm-6"><h3 class="card-title">Subscription Plans</h3></div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.subscriptions.plans.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus"></i> Add New Plan
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-sm">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Validity (Days)</th>
                        <th>Features</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plans as $plan)
                    <tr>
                        <td>{{ $plan->id }}</td>
                        <td>{{ $plan->name }}</td>
                        <td>${{ number_format($plan->price, 2) }}</td>
                        <td>{{ $plan->validity_days }}</td>
                        <td>{{ $plan->features }}</td>
                        <td>
                            <a href="{{ route('admin.subscriptions.plans.edit', $plan->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.subscriptions.plans.destroy', $plan->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure to delete this plan?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center">No subscription plans found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
