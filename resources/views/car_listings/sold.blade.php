@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">🚗 Sold Cars</h4>

        <a href="{{ route('admin.car-listings.index') }}" class="btn btn-sm btn-outline-secondary">
            ← Back to Active Listings
        </a>
    </div>

    {{-- Filters (reuse existing filters UI) --}}

    <div class="card">
        <div class="card-body p-0">
            <form method="GET" class="card mb-3">
    <div class="card-body">
        <div class="row g-2">

            <div class="col-md-3 mb-2">
                <input type="text" name="search" class="form-control"
                       placeholder="Search car"
                       value="{{ request('search') }}">
            </div>

            <div class="col-md-2 mb-2">
                <select name="make_id" id="make_id" class="form-control">
                    <option value="">Make</option>
                    @foreach($makes as $make)
                        <option value="{{ $make->id }}"
                            {{ request('make_id') == $make->id ? 'selected' : '' }}>
                            {{ $make->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <select name="model_id" id="model_id" class="form-control">
                    <option value="">Model</option>

                    {{-- Preload models if make already selected --}}
                    @foreach($models as $model)
                        <option value="{{ $model->id }}"
                            {{ request('model_id') == $model->id ? 'selected' : '' }}>
                            {{ $model->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <input type="number" name="price_min" class="form-control"
                       placeholder="Min Price" value="{{ request('price_min') }}">
            </div>

            <div class="col-md-2">
                <input type="number" name="price_max" class="form-control"
                       placeholder="Max Price" value="{{ request('price_max') }}">
            </div>

            <div class="col-md-2">
                <input type="date" name="sold_from" class="form-control"
                       value="{{ request('sold_from') }}">
            </div>

            <div class="col-md-2">
                <input type="date" name="sold_to" class="form-control"
                       value="{{ request('sold_to') }}">
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary w-100">Filter</button>
                <a href="{{ route('admin.car-listings.sold') }}"
                   class="btn btn-outline-secondary w-100">
                    Reset
                </a>
            </div>

        </div>
    </div>
</form>
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Car</th>
                        <th>Reg No</th>
                        <th>Seller</th>
                        <th>Price</th>
                        <th>Sold On</th>
                        <th>Buyer</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($soldCars as $car)
                        <tr>
                            <td>{{ $car->id }}</td>
                            <td>
                                {{ $car->make }} {{ $car->model }} ({{ $car->year }}) - {{ $car->color }}
                            </td>
                            <td>
                                {{strtoupper($car->registration_number)}}
                            </td>
                            <td>{{ $car->user->name ?? 'N/A' }}</td>
                            <td>₹{{ number_format($car->price) }}</td>
                            <td>{{ $car->sold_at ? $car->sold_at->format('d M Y') : '-' }}</td>
                            <td>
                                {{ $car->booking && $car->booking->user
                                    ? $car->booking->user->name
                                    : 'N/A' }}
                            </td>
                            <td>
                                <a href="{{ route('admin.car-listings.show', $car->id) }}"
                                   class="btn btn-sm btn-primary">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                No sold cars found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $soldCars->links() }}
    </div>

</div>
@push('scripts')
<script>
$(document).ready(function () {

    $('#make_id').on('change', function () {
        let makeId = $(this).val();
        let modelSelect = $('#model_id');

        modelSelect.html('<option value="">Loading...</option>');

        if (!makeId) {
            modelSelect.html('<option value="">Model</option>');
            return;
        }

        $.ajax({
            url: "{{ url('/admin/get-models') }}/" + makeId,
            type: 'GET',
            success: function (models) {
                modelSelect.html('<option value="">Model</option>');

                $.each(models, function (index, model) {
                    modelSelect.append(
                        '<option value="' + model.id + '">' + model.name + '</option>'
                    );
                });
            },
            error: function () {
                modelSelect.html('<option value="">Model</option>');
                alert('Unable to load models');
            }
        });
    });

});
</script>
@endpush

@endsection
