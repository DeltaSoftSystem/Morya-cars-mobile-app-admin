@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Car Details - {{ $car->title }}</h3>
        <div class="card-tools">
            <a href="{{ route('admin.car-listings.index') }}" class="btn btn-secondary btn-sm">Back to Listings</a>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <!-- Car Images Carousel -->
            <div class="col-md-6">
                @if($car->images->count())
                <div id="carCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($car->images as $index => $image)
                        <div class="carousel-item {{ $index==0 ? 'active' : '' }}">
                            <img src="{{ asset($image->image_path) }}" class="d-block w-100" alt="Car Image">
                        </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
                @else
                <p>No images uploaded.</p>
                @endif
            </div>

            <!-- Car Basic Details -->
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr><th>Title</th><td>{{ $car->title }}</td></tr>
                    <tr><th>Make</th><td>{{ $car->make }}</td></tr>
                    <tr><th>Model</th><td>{{ $car->model }}</td></tr>
                    <tr><th>Variant</th><td>{{ $car->variant }}</td></tr>
                    <tr><th>Year</th><td>{{ $car->year }}</td></tr>
                    <tr><th>KM Driven</th><td>{{ $car->km_driven }}</td></tr>
                    <tr><th>Fuel</th><td>{{ $car->fuel_type }}</td></tr>
                    <tr><th>Transmission</th><td>{{ $car->transmission }}</td></tr>
                    <tr><th>Body Type</th><td>{{ $car->body_type }}</td></tr>
                    <tr><th>Color</th><td>{{ $car->color }}</td></tr>
                    <tr><th>Price</th><td>{{ $car->price }}</td></tr>
                    <tr><th>Status</th>
                        <td>
                            <span class="badge 
                            {{ $car->status=='approved'?'badge-success':($car->status=='rejected'?'badge-danger':'badge-warning') }}">
                                {{ ucfirst($car->status) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Seller Info -->
        <div class="mt-3">
            <h5>Seller Info</h5>
            <table class="table table-bordered">
                <tr><th>Name</th><td>{{ $car->user->name }}</td></tr>
                <tr><th>Email</th><td>{{ $car->user->email }}</td></tr>
            </table>
        </div>

        <!-- Features -->
        <div class="mt-3">
            <h5>Features</h5>
            @if($car->features->count())
            <ul>
                @foreach($car->features as $feature)
                <li>{{ $feature->feature_name }}: {{ $feature->is_available ? 'Yes' : 'No' }}</li>
                @endforeach
            </ul>
            @else
            <p>No features added.</p>
            @endif
        </div>

        <!-- Inspection Reports -->
        <div class="mt-3">
            <h5>Inspections</h5>
            @if($car->inspections->count())
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Inspector</th>
                        <th>Center</th>
                        <th>Status</th>
                        <th>Report</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($car->inspections as $inspection)
                    <tr>
                        <td>{{ $inspection->inspection_date?->format('d M Y') }}</td>
                        <td>{{ $inspection->inspector_name }}</td>
                        <td>{{ $inspection->inspection_center }}</td>
                        <td>
                            <span class="badge 
                            {{ $inspection->status=='passed'?'badge-success':($inspection->status=='failed'?'badge-danger':'badge-warning') }}">
                                {{ ucfirst($inspection->status) }}
                            </span>
                        </td>
                        <td>
                            @if($inspection->report_url)
                            <a href="{{ asset($inspection->report_url) }}" target="_blank">View Report</a>
                            @else
                            N/A
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p>No inspections available.</p>
            @endif
        </div>

        <!-- Approve/Reject Buttons -->
        @if($car->status=='pending')
        <div class="mt-3">
            <form action="{{ route('admin.car-listings.approve', $car) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success">Approve</button>
            </form>
            <button class="btn btn-danger" onclick="rejectCar({{ $car->id }})">Reject</button>
        </div>
        @endif

    </div>
</div>

<script>
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
@endsection
