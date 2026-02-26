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
        <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
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
       <div class="d-flex justify-content-between mb-3">
        <div class="card-title">Car Listings</div>

         <a href="{{ route('admin.morya-cars.index') }}"
           class="btn btn-info mr-2">
            <i class="fas fa-car"></i> Morya Cars (Synced)
        </a>

        
    </div>

       
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

                        {{-- Edit request actions --}}
                        @if($car->pendingEditRequest)
                            <button class="btn btn-warning btn-sm"
                                data-toggle="modal"
                                data-target="#editRequestModal{{ $car->id }}">
                                <i class="fas fa-edit"></i> View Edit
                            </button>
                        @endif
                    </td>

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

@foreach($carListings as $car)
@if($car->pendingEditRequest)
<div class="modal fade" id="editRequestModal{{ $car->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Request – Car #{{ $car->id }}</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>Field</th>
                            <th>Old Value</th>
                            <th>New Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($car->pendingEditRequest->changes as $field => $values)
                        <tr>
                            <td>{{ ucfirst(str_replace('_',' ', $field)) }}</td>
                            <td>
                                @if(is_bool($values['old']) || in_array($values['old'], [0,1,'0','1'], true))
                                    <span class="badge badge-{{ $values['old'] ? 'success' : 'danger' }}">
                                        {{ $values['old'] ? 'Yes' : 'No' }}
                                    </span>
                                @else
                                    {{ $values['old'] ?? '-' }}
                                @endif
                            </td>

                            <td>
                                @if(is_bool($values['new']) || in_array($values['new'], [0,1,'0','1'], true))
                                    <span class="badge badge-{{ $values['new'] ? 'success' : 'danger' }}">
                                        {{ $values['new'] ? 'Yes' : 'No' }}
                                    </span>
                                @else
                                    {{ $values['new'] ?? '-' }}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="modal-footer d-flex justify-content-between align-items-start">

    {{-- APPROVE --}}
    <form method="POST"
          action="{{ route('admin.car-listings.edit-approve', $car->pendingEditRequest->id) }}">
        @csrf
        <button class="btn btn-success">
            <i class="fas fa-check"></i> Approve Edit
        </button>
    </form>

    {{-- REJECT --}}
    <form method="POST"
          action="{{ route('admin.car-listings.edit-reject', $car->pendingEditRequest->id) }}"
          class="d-flex align-items-start">
        @csrf

        <input type="text"
               name="reason"
               class="form-control mr-2"
               placeholder="Reject reason"
               style="width: 200px;"
               required>

        <button class="btn btn-danger">
            <i class="fas fa-times"></i> Reject
        </button>
    </form>

</div>


        </div>
    </div>
</div>
@endif
@endforeach

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