@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h4>Edit Morya Car</h4>
    <a href="{{ route('admin.morya-cars.index') }}" class="btn btn-secondary">
        ← Back
    </a>
</div>

{{-- ================= CAR SUMMARY ================= --}}
<div class="card mb-3 border-info">
    <div class="card-header bg-info text-white d-flex justify-content-between">
        <strong>Car Details (Synced from Morya Cars)</strong>
    </div>

    <div class="card-body row text-sm">

        <div class="col-md-3 mb-2">
            <strong>Make:</strong><br>
            {{ $car->make }}
        </div>

        <div class="col-md-3 mb-2">
            <strong>Model:</strong><br>
            {{ $car->model }}
        </div>

        <div class="col-md-3 mb-2">
            <strong>Variant:</strong><br>
            {{ $car->variant ?? '-' }}
        </div>

        <div class="col-md-3 mb-2">
            <strong>Year:</strong><br>
            {{ $car->year }}
        </div>

        <div class="col-md-3 mb-2">
            <strong>Fuel:</strong><br>
            {{ $car->fuel_type }}
        </div>

        <div class="col-md-3 mb-2">
            <strong>KM Driven:</strong><br>
            {{ number_format($car->km_driven) }}
        </div>

        <div class="col-md-3 mb-2">
            <strong>Registration No:</strong><br>
            {{ $car->registration_number }}
        </div>

        <div class="col-md-3 mb-2">
            <strong>Status:</strong><br>
            <span class="badge badge-success">Synced</span>
        </div>

    </div>
</div>
{{-- ================= END CAR SUMMARY ================= --}}


@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Please fix the errors below:</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.car-listings.updateSynced', $car->id) }}">
@csrf

{{-- ================= SELLING DETAILS ================= --}}
<div class="card mb-3">
<div class="card-header">Selling Details</div>
<div class="card-body row">

    <div class="col-md-4">
        <label>Selling Price *</label>
        <input type="number" name="price" class="form-control" value="{{ $car->price }}" required>
    </div>

    <div class="col-md-4">
        <label>Expected Price</label>
        <input type="number" name="expected_price" class="form-control" value="{{ $car->expected_price }}">
    </div>

    <div class="col-md-4 mt-4">
        <label>
            <input type="checkbox" name="is_negotiable" {{ $car->is_negotiable ? 'checked' : '' }}>
            Negotiable
        </label>
    </div>

</div>
</div>

{{-- ================= VEHICLE INFO ================= --}}
<div class="card mb-3">
<div class="card-header">Vehicle Information</div>
<div class="card-body row">

    <div class="col-md-4">
        <label>Transmission</label>
        <select name="transmission" class="form-control">
            <option value="Manual" {{ $car->transmission=='Manual'?'selected':'' }}>Manual</option>
            <option value="Automatic" {{ $car->transmission=='Automatic'?'selected':'' }}>Automatic</option>
        </select>
    </div>

    <div class="col-md-4">
        <label>Body Type</label>
        <select name="body_type" class="form-control">
            @foreach(['Hatchback','SUV','MUV','Coupe','Convertible','Pickup','Luxury'] as $type)
                <option value="{{ $type }}" {{ $car->body_type==$type?'selected':'' }}>
                    {{ $type }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label>Accident Info</label>
        <select name="accident" class="form-control">
            <option value="no" {{ $car->accident=='no'?'selected':'' }}>No Accident</option>
            <option value="minor" {{ $car->accident=='minor'?'selected':'' }}>Minor Accident</option>
            <option value="major" {{ $car->accident=='major'?'selected':'' }}>Major Accident</option>
        </select>
    </div>

</div>
</div>

{{-- ================= LOCATION ================= --}}
<div class="card mb-3">
<div class="card-header">Car Location</div>
<div class="card-body row">

    <div class="col-md-4">
        <label>City*</label>
        <input name="location_city" class="form-control" value="{{ $car->location_city }}">
    </div>

    <div class="col-md-4">
        <label>Registration State*</label>
        <input name="registration_state" class="form-control" value="{{ $car->registration_state }}">
    </div>

    <div class="col-md-2">
        <label>Latitude</label>
        <input name="latitude" class="form-control" value="{{ $car->latitude }}">
    </div>

    <div class="col-md-2">
        <label>Longitude</label>
        <input name="longitude" class="form-control" value="{{ $car->longitude }}">
    </div>

</div>
</div>

{{-- ================= INSURANCE & PUCC ================= --}}
<div class="card mb-3">
<div class="card-header">Insurance & PUCC</div>
<div class="card-body row">

    <div class="col-md-4">
        <label>Insurance Company</label>
        <input name="insurance_company" class="form-control" value="{{ $car->insurance_company }}">
    </div>

    <div class="col-md-4">
        <label>Policy Number</label>
        <input name="insurance_policy_number" class="form-control" value="{{ $car->insurance_policy_number }}">
    </div>

    <div class="col-md-4">
        <label>Insurance Valid Upto</label>
        <input type="date" name="insurance_upto" class="form-control" value="{{ $car->insurance_upto }}">
    </div>

    <div class="col-md-6 mt-3">
        <label>PUCC Number</label>
        <input name="pucc_number" class="form-control" value="{{ $car->pucc_number }}">
    </div>

    <div class="col-md-6 mt-3">
        <label>PUCC Valid Upto</label>
        <input type="date" name="pucc_upto" class="form-control" value="{{ $car->pucc_upto }}">
    </div>

</div>
</div>

{{-- ================= FEATURES ================= --}}
<div class="card mb-3">
<div class="card-header">Car Features</div>
<div class="card-body row">

@php
$features = [
    'Sunroof','Navigation','Parking Sensor',
    'Reverse Camera','Airbags','ABS','ESP'
];
@endphp

@foreach($features as $feature)
<div class="col-md-3">
    <label>
        <input type="checkbox"
               name="features[{{ $feature }}]"
               value="1"
               {{ optional($car->features->firstWhere('feature_name',$feature))->is_available ? 'checked' : '' }}>
        {{ $feature }}
    </label>
</div>
@endforeach

</div>
</div>

<div class="text-center pb-4">
<button class="btn btn-success">
    <i class="fas fa-save"></i> Save Changes
</button>
</div>

</form>
@endsection
