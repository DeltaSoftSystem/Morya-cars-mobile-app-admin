@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between mb-3">
        <h5>Services</h5>
        <a href="{{ route('services.create') }}"
           class="btn btn-sm btn-primary">+ Add Service</a>
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
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Items</th>
                        <th width="120">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                        <tr>
                            <td>{{ $service->id }}</td>
                            <td>{{ $service->name }}</td>
                            <td>{{ $service->slug }}</td>
                            <td>
                                <span class="badge bg-{{ $service->is_active ? 'success' : 'secondary' }}">
                                    {{ $service->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('services.items.index', $service->id) }}"
                                    class="btn btn-sm btn-outline-secondary">
                                    Items
                                    </a>

                            </td>
                            <td>
                                <a href="{{ route('services.edit', $service->id) }}"
                                   class="btn btn-sm btn-outline-primary">Edit</a>

                                <form action="{{ route('services.destroy', $service->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Delete this service?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No services found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $services->links() }}
        </div>
    </div>
</div>
@endsection
