@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between mb-3">
        <h5>{{ $service->name }} – Service Items</h5>

        <div>
        <a href="{{ route('services.items.create', $service->id) }}"
           class="btn btn-sm btn-primary">+ Add Item</a>
        <a href="{{ route('services.index') }}" class="btn btn-sm btn-secondary me-1">
            ← Back
        </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-3 table-responsive">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th width="120">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->price ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $item->is_active ? 'success':'secondary' }}">
                                    {{ $item->is_active ? 'Active':'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('services.items.edit', [$service->id,$item->id]) }}"
                                   class="btn btn-sm btn-outline-primary">Edit</a>

                                <form method="POST"
                                      action="{{ route('services.items.destroy', [$service->id,$item->id]) }}"
                                      class="d-inline"
                                      onsubmit="return confirm('Delete item?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Del</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">No items</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
