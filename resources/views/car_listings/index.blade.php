@extends('layouts.app')

@section('content')

<div class="card card-body">
    <form method="GET" class="mb-3 row g-2 align-items-end">
     <!-- Make Dropdown -->
    <div class="col-md-3">
        <label>Make</label>
        <select id="make" name="make_id" class="form-control">
            <option value="">All Makes</option>
            @foreach($makes as $make)
                <option value="{{ $make->id }}" {{ request('make_id') == $make->id ? 'selected' : '' }}>
                    {{ $make->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Model Dropdown -->
    <div class="col-md-3">
        <label>Model</label>
        <select id="model" name="model_id" class="form-control">
            <option value="">All Models</option>
            @foreach($models as $model)
                <option value="{{ $model->id }}" {{ request('model_id') == $model->id ? 'selected' : '' }}>
                    {{ $model->name }}
                </option>
            @endforeach
        </select>
    </div>


    <div class="col-md-2">
    <label>Year From</label>
    <select name="year_from" class="form-control">
        <option value="">From</option>
        @for ($year = date('Y'); $year >= 1990; $year--)
            <option value="{{ $year }}" {{ request('year_from') == $year ? 'selected' : '' }}>
                {{ $year }}
            </option>
        @endfor
    </select>
</div>

<div class="col-md-2">
    <label>Year To</label>
    <select name="year_to" class="form-control">
        <option value="">To</option>
        @for ($year = date('Y'); $year >= 1990; $year--)
            <option value="{{ $year }}" {{ request('year_to') == $year ? 'selected' : '' }}>
                {{ $year }}
            </option>
        @endfor
    </select>
</div>


    <div class="col-md-2">
        <label>Fuel Type</label>
        <select name="fuel_type" class="form-control">
            <option value="">All</option>
            <option value="Petrol" {{ request('fuel_type')=='Petrol'?'selected':'' }}>Petrol</option>
            <option value="Diesel" {{ request('fuel_type')=='Diesel'?'selected':'' }}>Diesel</option>
            <option value="CNG" {{ request('fuel_type')=='CNG'?'selected':'' }}>CNG</option>
            <option value="Electric" {{ request('fuel_type')=='Electric'?'selected':'' }}>Electric</option>
        </select>
    </div>

    <div class="col-md-2">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="">All</option>
            <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
            <option value="approved" {{ request('status')=='approved'?'selected':'' }}>Approved</option>
            <option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Rejected</option>
        </select>
    </div>

    <div class="col-md-2">
        <label>Price Min</label>
        <input type="number" name="price_min" class="form-control" value="{{ request('price_min') }}">
    </div>

    <div class="col-md-2">
        <label>Price Max</label>
        <input type="number" name="price_max" class="form-control" value="{{ request('price_max') }}">
    </div>

    <div class="col-md-2">
        <label>Keyword</label>
        <input type="text" name="search" class="form-control" placeholder="Title / Make / Model" value="{{ request('search') }}">
    </div>

    <div class="col-md-2">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('admin.car-listings.index') }}" class="btn btn-secondary">Reset</a>
    </div>
</form>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Car Listings</h3>
       
    </div>

    <div class="card-body table-responsive">


        <table class="table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Seller</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Year</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($carListings as $car)
                <tr @if($car->fuel_type == 'Electric') class="table-success" @endif>
                    <td>{{ $car->id }}</td>
                    <td>{{ $car->title }}</td>
                    <td>{{ $car->user->name ?? 'Admin' }}</td>
                    <td>{{ $car->make ?? '-' }}</td>
                    <td>{{ $car->model ?? '-' }}</td>
                    <td>{{ $car->year }}</td>
                    <td>
                        <span class="badge 
                        {{ $car->status=='approved'?'badge-success':($car->status=='rejected'?'badge-danger':'badge-warning') }}">
                            {{ ucfirst($car->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.car-listings.show', $car) }}" class="btn btn-info btn-sm">View</a>

                        @if($car->status=='pending')
                        <form action="{{ route('admin.car-listings.approve', $car) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                        </form>
                        <button class="btn btn-danger btn-sm" onclick="rejectCar({{ $car->id }})">Reject</button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>


        <div class="mt-3">
            {{ $carListings->withQueryString()->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection

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
        form.action = `/admin/car-listings/${carId}/reject`;

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