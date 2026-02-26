@extends('layouts.app')

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="d-flex justify-content-between mb-3">
<h4>Morya Cars – Synced Inventory</h4>

 <a href="{{ route('admin.car-listings.index') }}"
       class="btn btn-secondary">
        ← Back to All Car Listings
    </a>


</div>

<div class="card card-body mb-3">
    <form method="GET" class="row g-2 align-items-end">

        <div class="col-md-2">
            <label>Make</label>
            <select id="make" name="make_id" class="form-control">
                <option value="">All</option>
                @foreach($makes as $make)
                    <option value="{{ $make->id }}"
                        {{ request('make_id') == $make->id ? 'selected' : '' }}>
                        {{ $make->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label>Model</label>
            <select id="model" name="model_id" class="form-control">
                <option value="">All</option>
                @foreach($models as $model)
                    <option value="{{ $model->id }}"
                        {{ request('model_id') == $model->id ? 'selected' : '' }}>
                        {{ $model->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label>Year From</label>
            <select name="year_from" class="form-control">
                <option value="">From</option>
                @for ($y = date('Y'); $y >= 1990; $y--)
                    <option value="{{ $y }}"
                        {{ request('year_from') == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>
        </div>

        <div class="col-md-2">
            <label>Year To</label>
            <select name="year_to" class="form-control">
                <option value="">To</option>
                @for ($y = date('Y'); $y >= 1990; $y--)
                    <option value="{{ $y }}"
                        {{ request('year_to') == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>
        </div>

        <div class="col-md-2">
            <label>Fuel</label>
            <select name="fuel_type" class="form-control">
                <option value="">All</option>
                @foreach(['Petrol','Diesel','CNG','Electric'] as $fuel)
                    <option value="{{ $fuel }}"
                        {{ request('fuel_type') == $fuel ? 'selected' : '' }}>
                        {{ $fuel }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="">All</option>
                @foreach(['approved','inactive'] as $st)
                    <option value="{{ $st }}"
                        {{ request('status') == $st ? 'selected' : '' }}>
                        {{ ucfirst($st) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label>Search</label>
            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Make / Model / Reg No"
                   value="{{ request('search') }}">
        </div>

        <div class="col-md-3">
            <button class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.morya-cars.index') }}"
               class="btn btn-secondary">
                Reset
            </a>
        </div>

    </form>
</div>

<div class="card">
    <div class="card-header">
       <div class="d-flex justify-content-between mb-3">
        <div class="card-title">Car Listings</div>
            <form method="POST"
                    action="{{ route('admin.cars.sync') }}"
                    onsubmit="return confirm('Fetch latest cars from Morya Cars?')">
                    @csrf
                    <button class="btn btn-outline-warning">
                        <i class="fas fa-sync"></i> Sync Cars from app.moryacars.in
                    </button>
                </form>
            </div>
    </div>


    <div class="card-body table-responsive">
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Year</th>
                    <th>Status</th>
                    <th>Actions</th>
                    <th>Approved/Reject</th>
                </tr>
            </thead>
            <tbody>
                    @forelse($cars as $car)
                        <tr>
                            <td>{{ $car->id }}</td>
                            <td>{{ $car->title }}</td>
                            <td>{{ $car->make }}</td>
                            <td>{{ $car->model }}</td>
                            <td>{{ $car->year }}</td>
                            <td>
                                  @if($car->booking) 
                            <span class="badge badge-danger">Booked</span>
                                @else
                                    <span class="badge 
                                    {{ $car->status=='approved'?'badge-success':($car->status=='rejected'?'badge-danger':'badge-warning') }}">
                                        {{ ucfirst($car->status) }}
                                    </span>

                                    @if($car->pendingEditRequest)
                                        <br>
                                        <span class="badge badge-warning mt-1">
                                            Edit Requested
                                        </span>
                                    @endif
                                @endif
                                
                            </td>
                            <td>
                                <a href="{{ route('admin.car-listings.show', $car) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ route('admin.car-listings.editSynced', $car->id) }}"
                                class="btn btn-sm btn-warning">
                                    ✏️ Edit
                                </a>

                                <a href="{{ route('admin.car-listings.images', $car->id) }}"
                                class="btn btn-sm btn-info">
                                    🖼 Images
                                </a>
                            </td>
                            <td>
                                {{-- Pending car approval --}}
                        @if($car->status == 'pending')
                            <form action="{{ route('admin.car-listings.approve', $car) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-check-circle"></i> Approve
                                </button>
                            </form>

                            <button class="btn btn-danger btn-sm" onclick="rejectCar({{ $car->id }})">
                                <i class="fas fa-times-circle"></i> Reject
                            </button>
                        @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="mb-3">
                                    <strong>No Morya Cars data found</strong>
                                    <br>
                                    <span class="text-muted">
                                        Sync cars from app.moryacars.in to start managing inventory
                                    </span>
                                </div>

                                <form method="POST"
                                    action="{{ route('admin.cars.sync') }}"
                                    onsubmit="return confirm('Fetch latest cars from Morya Cars?')">
                                    @csrf
                                    <button class="btn btn-warning">
                                        <i class="fas fa-sync"></i> Sync Cars Now
                                    </button>
                                </form>
                            </td>

                        </tr>
                    @endforelse

            </tbody>
        </table>

        {{ $cars->links('pagination::bootstrap-4') }}
    </div>
</div>

@push('scripts')
<script>

$('#make').change(function () {
    let makeId = $(this).val();
    $('#model').empty().append('<option value="">All Models</option>');

    if(makeId){
        $.get('/admin/get-models/' + makeId, function (data) {
            $.each(data, function(key, value){
                $('#model').append('<option value="'+ value.id +'">'+ value.name +'</option>');
            });
        });
    }
});

function rejectCar(carId){
    let reason = prompt("Enter rejection reason:");
    if(reason){
        let form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ url('car-listings') }}/" + carId + "/reject";

        let token = document.createElement('input');
        token.type = 'hidden';
        token.name = '_token';
        token.value = "{{ csrf_token() }}";
        form.appendChild(token);

        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'reason';
        input.value = reason;
        form.appendChild(input);

        document.body.appendChild(form);
        form.submit();
    }
}

</script>
@endpush
@endsection
