@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h4>Manage Images – {{ $car->make }} {{ $car->model }}</h4>
    <a href="{{ route('admin.morya-cars.index') }}" class="btn btn-secondary">
        ← Back
    </a>
</div>

{{-- ================= CAR SUMMARY ================= --}}
<div class="card mb-3 border-info">
    <div class="card-header bg-info text-white">
        <strong>Car Details</strong>
    </div>

    <div class="card-body row align-items-center text-sm">

        {{-- Primary Image --}}
        <div class="col-md-2 text-center">
            @php
                $primaryImage = $car->images->firstWhere('is_primary', 1);
            @endphp

            @if($primaryImage)
                <img src="{{ asset('storage/'.$primaryImage->image_path) }}"
                     class="img-fluid rounded"
                     style="max-height:100px; object-fit:cover;">
            @else
                <div class="border rounded p-3 text-muted">
                    No Image
                </div>
            @endif
        </div>

        {{-- Car Info --}}
        <div class="col-md-10">
            <div class="row">

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
                    <strong>Reg. No:</strong><br>
                    {{ $car->registration_number }}
                </div>

                <div class="col-md-3 mb-2">
                    <span class="badge badge-success mt-3">
                        Synced from Morya Cars
                    </span>
                </div>

            </div>
        </div>

    </div>
</div>
{{-- ================= END CAR SUMMARY ================= --}}

{{-- Upload --}}
<form method="POST"
      action="{{ route('admin.car-listings.images.store', $car->id) }}"
      enctype="multipart/form-data"
      class="mb-4">
    @csrf

    <input type="file" name="images[]" multiple class="form-control mb-2" required>
    <button class="btn btn-primary">Upload Images</button>
</form>

{{-- Gallery --}}
<div class="row">
@foreach($car->images as $image)
    <div class="col-md-3 mb-4">
        <div class="card">
            <img src="{{ asset('storage/'.$image->image_path) }}"
                 class="card-img-top"
                 style="height:180px; object-fit:cover;">

            <div class="card-body text-center">
                @if($image->is_primary)
                    <span class="badge badge-success mb-2">Primary</span>
                @else
                    <form method="POST"
                          action="{{ route('admin.car-images.primary', $image->id) }}">
                        @csrf
                        <button class="btn btn-sm btn-outline-success mb-2">
                            Set Primary
                        </button>
                    </form>
                @endif

                <form method="POST"
                      action="{{ route('admin.car-images.delete', $image->id) }}"
                      onsubmit="return confirm('Delete this image?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
@endforeach
</div>

@endsection
