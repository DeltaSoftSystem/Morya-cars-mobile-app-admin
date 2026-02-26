@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex align-items-center mb-4">
    <h2 class="mb-0">
        Car Details: {{ $car->title }}

        @if($car->sale_status === 'sold')
            <span class="badge badge-danger ml-2">SOLD</span>
        @endif
    </h2>

    <a href="{{ url()->previous() ?? route('admin.car-listings.index') }}" class="btn btn-secondary btn-sm ml-auto">
        Back
    </a>
</div>


    <!-- Main Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row">

                <!-- Left: Car Images -->
                <div class="col-md-6 mb-3">
                    @if($car->images->count())
                        <div id="carCarousel" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                @foreach($car->images as $index => $image)
                                    <li data-target="#carCarousel" data-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"></li>
                                @endforeach
                            </ol>
                            <div class="carousel-inner">
                                @foreach($car->images as $index => $image)
                                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                        <img src="{{ Storage::url($image->image_path) }}" class="d-block w-100 rounded" alt="Car Image">
                                    </div>
                                @endforeach
                            </div>
                            <a class="carousel-control-prev" href="#carCarousel" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carCarousel" role="button" data-slide="next">
                                <span class="carousel-control-next-icon"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    @else
                        <p class="text-muted">No images uploaded.</p>
                    @endif
                </div>

                <!-- Right: Basic Details -->
                <div class="col-md-6">
                    <h5 class="mb-3">Basic Information</h5>
                    <table class="table table-sm table-bordered">
                        <tbody>
                            <tr><th>Title</th><td>{{ $car->title }}</td></tr>
                            <tr><th>Make</th><td>{{ $car->make }}</td></tr>
                            <tr><th>Model</th><td>{{ $car->model }}</td></tr>
                            <tr><th>Variant</th><td>{{ $car->variant }}</td></tr>
                            <tr><th>Year</th><td>{{ $car->year }}</td></tr>
                            <tr><th>KM Driven</th><td>{{ number_format($car->km_driven) }}</td></tr>
                            <tr><th>Fuel</th><td>{{ $car->fuel_type }}</td></tr>
                            <tr><th>Transmission</th><td>{{ $car->transmission }}</td></tr>
                            <tr><th>Body Type</th><td>{{ $car->body_type }}</td></tr>
                            <tr><th>Color</th><td>{{ $car->color }}</td></tr>
                            <tr><th>Accident</th><td>{{ ucfirst($car->accident) }}</td></tr>
                            <tr><th>Price</th><td>₹ {{ number_format($car->price) }}</td></tr>
                            <tr><th>Expected Price</th><td>₹ {{ number_format($car->expected_price) }}</td></tr>
                            <tr><th>Negotiable?</th><td>{{ $car->is_negotiable ? 'Yes' : 'No' }}</td></tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge 
                                        {{ $car->status=='approved'?'badge-success':($car->status=='rejected'?'badge-danger':'badge-warning') }}">
                                        {{ ucfirst($car->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Sale Status</th>
                                <td>
                                    <span class="badge
                                        {{ $car->sale_status === 'sold' ? 'badge-danger' :
                                        ($car->sale_status === 'reserved' ? 'badge-warning' : 'badge-success') }}">
                                        {{ ucfirst($car->sale_status ?? 'available') }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div> <!-- /row -->

            <!-- Registration & Location -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card card-sm mb-3">
                        <div class="card-header bg-light"><strong>Registration Details</strong></div>
                        <div class="card-body p-2">
                            <table class="table table-sm mb-0">
                                <tr><th>Owner Count</th><td>{{ $car->owner_count }}</td></tr>
                                <tr><th>State</th><td>{{ $car->registration_state }}</td></tr>
                                <tr><th>City</th><td>{{ $car->registration_city }}</td></tr>
                                <tr><th>Number</th><td>{{ $car->registration_number }}</td></tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-sm mb-3">
                        <div class="card-header bg-light"><strong>Location</strong></div>
                        <div class="card-body p-2">
                            <table class="table table-sm mb-0">
                                <tr><th>City</th><td>{{ $car->location_city }}</td></tr>
                                <tr><th>State</th><td>{{ $car->location_state }}</td></tr>
                                <tr><th>Latitude</th><td>{{ $car->latitude }}</td></tr>
                                <tr><th>Longitude</th><td>{{ $car->longitude }}</td></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div class="card mb-3">
                <div class="card-header bg-light"><strong>Built-in Features</strong></div>
                <div class="card-body p-2">
                    <table class="table table-sm mb-0">
                        <tr><th>Sunroof</th><td>{{ $car->has_sunroof ? 'Yes' : 'No' }}</td></tr>
                        <tr><th>Navigation</th><td>{{ $car->has_navigation ? 'Yes' : 'No' }}</td></tr>
                        <tr><th>Parking Sensor</th><td>{{ $car->has_parking_sensor ? 'Yes' : 'No' }}</td></tr>
                        <tr><th>Reverse Camera</th><td>{{ $car->has_reverse_camera ? 'Yes' : 'No' }}</td></tr>
                        <tr><th>Airbags</th><td>{{ $car->has_airbags ? 'Yes' : 'No' }}</td></tr>
                        <tr><th>ABS</th><td>{{ $car->has_abs ? 'Yes' : 'No' }}</td></tr>
                        <tr><th>ESP</th><td>{{ $car->has_esp ? 'Yes' : 'No' }}</td></tr>
                    </table>
                </div>
            </div>

            <!-- Additional Features -->
            @if($car->features->count())
            <div class="card mb-3">
                <div class="card-header bg-light"><strong>Additional Features</strong></div>
                <div class="card-body">
                    <ul class="mb-0">
                        @foreach($car->features as $feature)
                            <li>{{ $feature->feature_name }}: {{ $feature->is_available ? 'Yes' : 'No' }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <!-- Inspection Reports -->
            {{-- <div class="card mb-3">
                <div class="card-header bg-light"><strong>Inspection Reports</strong></div>
                <div class="card-body p-2">
                    <table class="table table-sm mb-0">
                        <tr><th>Report URL</th>
                            <td>
                                @if($car->inspection_report_url)
                                    <a href="{{ asset($car->inspection_report_url) }}" target="_blank">View Report</a>
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        <tr><th>Summary</th><td>{{ $car->inspection_summary ?? 'N/A' }}</td></tr>
                    </table>
                </div>
            </div> --}}

            <!-- Seller Info -->
            <div class="card mb-3">
                <div class="card-header bg-light"><strong>Seller Info</strong></div>
                <div class="card-body p-2">
                    <table class="table table-sm mb-0">
                        <tr><th>Name</th><td>{{ $car->user->name }}</td></tr>
                        <tr><th>Email</th><td>{{ $car->user->email }}</td></tr>
                        <tr><th>Mobile</th><td>{{ $car->user->mobile }}</td></tr>
                    </table>
                </div>
            </div>

            @if($car->sale_status === 'sold')
<div class="card mb-3 border-danger">
    <div class="card-header bg-danger text-white">
        <strong>Sale Details</strong>
    </div>
    <div class="card-body p-2">
        <table class="table table-sm mb-0">
            <tr>
                <th>Sold On</th>
                <td>
                    {{ $car->sold_at
                        ? $car->sold_at->format('d M Y, h:i A')
                        : 'N/A' }}
                </td>
            </tr>

            <tr>
                <th>Buyer Name</th>
                <td>
                    {{ optional(optional($car->booking)->user)->name ?? 'N/A' }}
                </td>
            </tr>

            <tr>
                <th>Buyer Mobile</th>
                <td>
                    {{ optional(optional($car->booking)->user)->mobile ?? 'N/A' }}
                </td>
            </tr>

            <tr>
                <th>Booking Amount</th>
                <td>
                    ₹ {{ number_format(optional($car->booking)->booking_amount ?? 0) }}
                </td>
            </tr>

            <tr>
                <th>Payment Status</th>
                <td>
                    <span class="badge badge-success">
                        {{ ucfirst(optional($car->booking)->payment_status ?? 'paid') }}
                    </span>
                </td>
            </tr>
        </table>
    </div>
</div>
@endif


            <!-- Admin Review -->
            <div class="card mb-3">
                <div class="card-header bg-light"><strong>Admin Review</strong></div>
                <div class="card-body p-2">
                    <table class="table table-sm mb-0">
                        <tr><th>Approved At</th><td>{{ $car->approved_at ?? 'N/A' }}</td></tr>
                        <tr><th>Rejection Reason</th><td>{{ $car->admin_rejection_reason ?? 'N/A' }}</td></tr>
                    </table>
                </div>
            </div>

            <!-- Listing Stats -->
            {{-- <div class="card mb-3">
                <div class="card-header bg-light"><strong>Listing Stats</strong></div>
                <div class="card-body p-2">
                    <table class="table table-sm mb-0">
                        <tr><th>Featured</th><td>{{ $car->is_featured ? 'Yes' : 'No' }}</td></tr>
                        <tr><th>Views</th><td>{{ $car->views_count }}</td></tr>
                        <tr><th>Leads</th><td>{{ $car->leads_count }}</td></tr>
                    </table>
                </div>
            </div> --}}

            <!-- Approve / Reject -->
            @if($car->status === 'pending' && $car->sale_status !== 'sold')
            <div class="text-right mb-3">
                <form action="{{ route('admin.car-listings.approve', $car) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-success">Approve</button>
                </form>
                <button class="btn btn-danger" onclick="rejectCar({{ $car->id }})">Reject</button>
            </div>
            @endif

        </div> <!-- card-body -->
    </div> <!-- card -->
</div>

<script>
function rejectCar(carId){
    let reason = prompt("Enter rejection reason:");
    if(reason){
        let form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/car-listings/${carId}/reject`;

        let csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = "{{ csrf_token() }}";
        form.appendChild(csrf);

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
