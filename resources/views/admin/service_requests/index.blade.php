@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <h4 class="mb-3">Service Requests</h4>

    <!-- Filters -->
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <select name="service_id" class="form-control">
                <option value="">All Services</option>
                @foreach($services as $service)
                    <option value="{{ $service->id }}"
                        {{ request('service_id') == $service->id ? 'selected' : '' }}>
                        {{ $service->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <select name="status" class="form-control">
                <option value="">All Status</option>
                @foreach(['pending','assigned','in_progress','completed','cancelled'] as $status)
                    <option value="{{ $status }}"
                        {{ request('status') == $status ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_',' ', $status)) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary">Filter</button>
            <a href="{{ route('service-requests.index') }}"
               class="btn btn-light">Reset</a>
        </div>
    </form>

    <!-- Table -->
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Service</th>
                        <th>User</th>
                        <th>Mobile</th>
                        <th>City</th>
                        <th>PinCode</th>
                        <th>Car</th>
                        <th>Status</th>
                        <th>Requested At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $req)
                        <tr>
                            <td>{{ $req->id }}</td>
                            <td>{{ $req->service->name ?? '-' }}</td>
                            <td>{{ $req->name }}</td>
                            <td>{{ $req->mobile }}</td>
                            <td>{{ $req->city }}</td>
                            <td>{{ $req->pincode }}</td>
                            <td>
                                @if($req->car)
                                    {{ $req->car->make }} {{ $req->car->model }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ ucfirst(str_replace('_',' ', $req->status)) }}
                                </span>
                            </td>
                            <td>{{ $req->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('service-requests.show', $req->id) }}" class="btn btn-sm btn-outline-primary">
                                    View
                                    </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $requests->links() }}
        </div>
    </div>

</div>
@endsection
