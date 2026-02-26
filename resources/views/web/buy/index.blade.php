@extends('web.layouts.app')
@section('title','Buy Used Cars')

@section('content')

<section class="buy-page py-4">
<div class="container-xl">

    <!-- Breadcrumb -->
    <div class="mb-4 small text-muted">
        <a href="/" class="text-decoration-none">Home</a> ›
        <strong>Buy Used Cars</strong>
    </div>

    <div class="row g-4">

        <!-- ================= FILTER SIDEBAR (DESKTOP) ================= -->
        <div class="col-lg-3 d-none d-lg-block">
            <div class="filter-sidebar">
                <div class="filter-box">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Filters</h5>
                        <a href="#" id="resetFilter" class="small">Reset All</a>
                    </div>

                    <div class="filter-group">
                        <label>Make</label>
                        <select id="brand" class="form-select">
                            <option value="">All</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->name }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Body Type</label>
                        <select id="body_type" class="form-select">
                            <option value="">All</option>
                            <option>SUV</option>
                            <option>Sedan</option>
                            <option>Hatchback</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Price</label>
                        <div class="d-flex gap-2">
                            <input type="number" id="min_price" class="form-control" placeholder="From">
                            <input type="number" id="max_price" class="form-control" placeholder="To">
                        </div>
                    </div>

                    <button id="applyFilter" class="btn btn-dark w-100 mt-4">
                        Apply Filters
                    </button>

                </div>
            </div>
        </div>

        <!-- ================= MAIN CONTENT ================= -->
        <div class="col-lg-9">

            <!-- TOP BAR -->
            <div class="buy-top-bar d-flex justify-content-between align-items-center mb-4">

                <h2 class="buy-heading">
                    Browse <span id="carCount">{{$carCount}}</span> Used Cars
                </h2>

                <div class="d-flex gap-3 align-items-center">

                    <!-- MOBILE FILTER BTN -->
                    <button class="btn btn-outline-dark d-lg-none"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#filterOffcanvas">
                        Filters
                    </button>

                    <!-- SORT -->
                    <select id="sortBy" class="form-select sort-dropdown">
                        <option value="latest">Sort By</option>
                        <option value="price_low">Price Low to High</option>
                        <option value="price_high">Price High to Low</option>
                        <option value="year_new">Newest</option>
                    </select>

                </div>

            </div>

            <!-- CAR GRID -->
            <div id="carContainer" class="row g-4">
@foreach($cars as $car)

@php
    $img = $car->images->where('is_primary',1)->first()
        ?? $car->images->first();
@endphp

<div class="col-md-4">
    <a href="{{ route('cars.show', $car->id) }}" class="car-link">

        <div class="deal-card">

            <div class="deal-img-wrap">
                <img src="{{ $img ? asset('storage/'.$img->image_path) : asset('theme/img/no-car.jpg') }}">
            </div>

            <div class="deal-body">
                <div class="d-flex justify-content-between mb-2">
                    <h5>{{ $car->make }} {{ $car->model }}</h5>
                    <span class="year">{{ $car->year }}</span>
                </div>

                <div class="price-row">
                    <div>
                        <small>Starts from</small>
                        <div class="price-month">
                            ₹ {{ number_format($car->price / 60) }}/month
                        </div>
                    </div>

                    <div class="text-end">
                        <small>Full Price</small>
                        <div class="price-full">
                            ₹ {{ number_format($car->price) }}
                        </div>
                    </div>
                </div>

                <hr>

                <div class="odo-row">
                    <span>Odometer</span>
                    <strong>{{ number_format($car->km_driven) }} kms</strong>
                </div>
            </div>

        </div>

    </a>
</div>

@endforeach
</div>


            <div id="loading" class="text-center py-4 d-none">
                Loading...
            </div>

        </div>

    </div>
</div>
</section>

<!-- ================= MOBILE OFFCANVAS ================= -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="filterOffcanvas">
    <div class="offcanvas-header">
        <h5>Filters</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">

        <div class="filter-box">

            <div class="filter-group">
                <label>Make</label>
                <select id="brand_m" class="form-select">
                    <option value="">All</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand }}">{{ $brand }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label>Body Type</label>
                <select id="body_type_m" class="form-select">
                    <option value="">All</option>
                    <option>SUV</option>
                    <option>Sedan</option>
                </select>
            </div>

            <div class="filter-group">
                <label>Price</label>
                <div class="d-flex gap-2">
                    <input type="number" id="min_price_m" class="form-control" placeholder="From">
                    <input type="number" id="max_price_m" class="form-control" placeholder="To">
                </div>
            </div>

            <button id="applyFilterMobile" class="btn btn-dark w-100 mt-4">
                Apply Filters
            </button>

        </div>

    </div>
</div>

@push('scripts')
<script>
let page = 1;
let loading = false;

function fetchCars(reset = false) {

    if(loading) return;
    loading = true;

    if(reset){
        page = 1;
        document.getElementById('carContainer').innerHTML = '';
    }

    document.getElementById('loading').classList.remove('d-none');

    fetch(`{{ route('buy.cars') }}?page=${page}&brand=${brand.value}&body_type=${body_type.value}&min_price=${min_price.value}&max_price=${max_price.value}&sort=${sortBy.value}`)
    .then(res => res.json())
    .then(data => {

        document.getElementById('carCount').innerText = data.total;

        data.data.forEach(car => {
            document.getElementById('carContainer')
            .insertAdjacentHTML('beforeend', cardTemplate(car));
        });

        page++;
        loading = false;
        document.getElementById('loading').classList.add('d-none');
    });
}

function cardTemplate(car){
return `
<div class="col-md-6 col-xl-4">
    <div class="car-card">

        <div class="car-img-wrap">
            <span class="sale-tag">Special Offer</span>
            <button class="wishlist-btn">♡</button>
            <img src="${car.image}" class="car-img">
        </div>

        <div class="mt-3">
            <div class="d-flex justify-content-between">
                <h6>${car.make} ${car.model}</h6>
                <strong>${car.year}</strong>
            </div>

            <div class="d-flex justify-content-between mt-2">
                <div>
                    <small>Starts from</small>
                    <div class="car-price">₹ ${car.monthly}</div>
                </div>

                <div class="text-end">
                    <small>Full Price</small>
                    <div><strong>₹ ${car.price}</strong></div>
                </div>
            </div>

            <hr>

            <div class="d-flex justify-content-between small text-muted">
                <span>Odometer</span>
                <strong>${car.km_driven} kms</strong>
            </div>
        </div>

    </div>
</div>`;
}

applyFilter.onclick = () => fetchCars(true);
sortBy.onchange = () => fetchCars(true);
resetFilter.onclick = () => {
    brand.value = '';
    body_type.value = '';
    min_price.value = '';
    max_price.value = '';
    fetchCars(true);
};

window.addEventListener('scroll', () => {
    if(window.innerHeight + window.scrollY >= document.body.offsetHeight - 300){
        fetchCars();
    }
});

fetchCars();


success: function(res){

    let html = '';

    res.data.forEach(car => {

        html += `
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="car-card">
                <h5>${car.make} ${car.model}</h5>
                <p>${car.year}</p>
                <p>₹ ${Number(car.price).toLocaleString()}</p>
            </div>
        </div>
        `;
    });

    $('#carsContainer').html(html);
}
</script>
@endpush


@endsection
