@extends('web.layouts.app')
@section('title', $car->make . ' ' . $car->model)

@section('content')

<div class="container-xl my-4">

    {{-- BREADCRUMB --}}
    <nav class="mb-4">
        <ol class="breadcrumb custom-breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="/buy">Buy Used Cars</a></li>
            <li class="breadcrumb-item active">
                {{ $car->make }} {{ $car->model }} {{ $car->year }}
            </li>
        </ol>
    </nav>

    <div class="row g-4">

        {{-- LEFT IMAGE GALLERY --}}
        <div class="col-lg-8">

    <div class="car-gallery">

        <!-- MAIN SLIDER -->
        <div class="swiper mainSwiper">
            <div class="swiper-wrapper">

                @if($car->images && $car->images->count() > 0)

                    @foreach($car->images as $image)
                        <div class="swiper-slide">
                            <img src="{{ asset('storage/'.$image->image_path) }}" 
                                class="main-image">
                        </div>
                    @endforeach

                @else

                    <div class="swiper-slide">
                        <img src="{{ asset('theme/img/no-car.jpg') }}" 
                            class="main-image">
                    </div>

                @endif

            </div>


            <!-- Arrows -->
            <div class="swiper-button-prev main-prev"></div>
            <div class="swiper-button-next main-next"></div>
        </div>

        <!-- THUMBNAILS -->
        <div class="thumb-container mt-3">
            @foreach($car->images as $index => $img)
                <img src="{{ asset('storage/'.$img->image_path) }}"
                     class="thumb"
                     data-index="{{ $index }}">
            @endforeach
        </div>

    </div>

</div>


        {{-- RIGHT DETAILS CARD --}}
        <div class="col-lg-4">

            <div class="price-card">

                <div class="sale-banner">Ramadan Sale</div>

                <h3 class="car-title">
                    {{ $car->make }} {{ $car->model }} {{ $car->variant }}
                </h3>

                <small class="text-muted">Stock no: {{ $car->stock_no }}</small>

                <div class="price-main mt-3">
                    ₹ {{ number_format($car->price) }}
                </div>

                <div class="emi-price">
                    ₹ {{ number_format($car->price/60) }}/Month
                </div>

                <div class="viewing-now">
                    🔴 18 People are viewing right now
                </div>

                <button class="btn btn-danger w-100 btn-lg mt-3">
                    Book a free test drive
                </button>

                <div class="d-flex gap-2 mt-3">
                    <button class="btn btn-light w-50">Call Us</button>
                    <button class="btn btn-outline-dark w-50">Buy this Car</button>
                </div>

            </div>

            {{-- OFFER STRIP --}}
            <div class="offer-strip mt-3">
                <div class="zero">0%</div>
                <div>
                    <strong>Downpayment for all cars</strong><br>
                    1 Year free warranty
                </div>
            </div>

        </div>

    </div>
</div>

<section class="car-info-section">
    <div class="container-xl">

        {{-- CAR OVERVIEW --}}
        <div class="info-box">

            <h4 class="section-heading mb-4">Car Overview</h4>

           @php
            $overview = [

                ['label' => 'Year', 'value' => $car->year, 'icon' => 'fa-calendar'],

                ['label' => 'Mileage', 'value' => $car->km_driven ? number_format($car->km_driven).' km' : null, 'icon' => 'fa-gauge'],

                ['label' => 'Fuel Type', 'value' => $car->fuel_type, 'icon' => 'fa-gas-pump'],

                ['label' => 'Transmission', 'value' => $car->transmission, 'icon' => 'fa-gears'],

                ['label' => 'Body Type', 'value' => $car->body_type, 'icon' => 'fa-car-side'],

                ['label' => 'Color', 'value' => $car->color, 'icon' => 'fa-palette'],

                ['label' => 'Owners', 'value' => $car->owner_count ? $car->owner_count.' Owner(s)' : null, 'icon' => 'fa-user'],

                ['label' => 'Insurance Upto', 'value' => $car->insurance_upto, 'icon' => 'fa-shield-halved'],

                ['label' => 'PUCC Upto', 'value' => $car->pucc_upto, 'icon' => 'fa-file-lines'],

                ['label' => 'Registration', 'value' => $car->registration_state, 'icon' => 'fa-location-dot'],

                ['label' => 'Accident', 'value' => $car->accident ? 'Yes' : 'No', 'icon' => 'fa-car-burst'],

            ];
            @endphp


            <div class="row g-3 overview-grid">

                @foreach($overview as $item)

                    @if(!empty($item['value']))

                        <div class="col-lg-2 col-md-3 col-6">
                            <div class="overview-card">

                                <div class="icon green">
                                    <i class="fa-solid {{ $item['icon'] }}"></i>
                                </div>

                                <small>{{ $item['label'] }}</small>
                                <strong>{{ $item['value'] }}</strong>

                            </div>
                        </div>

                    @endif

                @endforeach

            </div>


        </div>


        {{-- KEY FEATURES --}}
        <div class="info-box mt-5">

            <h4 class="section-heading mb-4">Key Features</h4>

           <div class="row g-3">

                @forelse($car->features as $feature)

                    <div class="col-lg-4 col-md-6 col-6">
                        <div class="feature-item">
                            <i class="fa-solid fa-circle-check"></i>
                            {{ $feature->feature_name }}
                        </div>
                    </div>

                @empty

                    <div class="col-12">
                        <p class="text-muted">No features listed for this vehicle.</p>
                    </div>

                @endforelse

            </div>


        </div>


        {{-- ABOUT --}}
        <div class="info-box mt-5">

            <h4 class="section-heading mb-3">About</h4>

            <p class="about-text">
                {!! $about !!}
            </p>

        </div>

    </div>
</section>

<section class="emi-section">
    <div class="container-xl">
        <div class="row g-4">

            {{-- LEFT SIDE --}}
            <div class="col-lg-7">
                <div class="emi-box">

                    <h3 class="mb-4">Estimate your monthly installment</h3>

                    {{-- Down Payment --}}
                    <label class="mb-2">Select downpayment</label>

                    <div class="down-box mb-3">
                        <div>
                            ₹ <span id="downValue">0</span>
                        </div>
                        <div>
                            (<span id="downPercent">0</span>%)
                        </div>
                    </div>

                    <input type="range"
                           id="downSlider"
                           min="0"
                           max="{{ $car->price }}"
                           value="0"
                           class="form-range">

                    {{-- Years --}}
                    <div class="mt-4">
                        <label class="mb-2">I can repay the loan in (years)*</label>
                        <div class="year-options">
                            <button class="year-btn" data-year="1">1</button>
                            <button class="year-btn" data-year="2">2</button>
                            <button class="year-btn" data-year="3">3</button>
                            <button class="year-btn" data-year="4">4</button>
                            <button class="year-btn active" data-year="5">5</button>
                        </div>
                    </div>

                </div>
            </div>

            {{-- RIGHT SIDE --}}
            <div class="col-lg-5">
                <div class="emi-result">

                    <div class="offer-strip-mini mb-3">
                        <strong>0%</strong>
                        <div>
                            Downpayment for all cars <br>
                            <small>1 Year free warranty</small>
                        </div>
                    </div>

                    <p class="text-muted">Indicative EMI</p>

                    <h2 class="emi-value">
                        ₹ <span id="emiAmount">0</span>
                    </h2>

                    <p>for <span id="emiYears">5</span> years</p>

                    <div class="emi-details mt-4">
                        <div>
                            <small>Down Payment</small>
                            <strong>₹ <span id="summaryDown">0</span></strong>
                        </div>

                        <div>
                            <small>Loan Amount</small>
                            <strong>₹ <span id="loanAmount">{{ number_format($car->price) }}</span></strong>
                        </div>

                        <div>
                            <small>Interest rate*</small>
                            <strong>3.5%</strong>
                        </div>
                    </div>

                    <button class="btn btn-danger w-100 btn-lg mt-4">
                        Book a free test drive
                    </button>

                </div>
            </div>

        </div>
    </div>
</section>

<section class="faq-section">
    <div class="container-xl">

        <h2 class="mb-4 fw-bold">Frequently Asked Questions</h2>

        <div class="accordion custom-faq" id="faqAccordion">

            @php
                $faqs = [
                    ["q" => "How often do you update your used car inventory?",
                     "a" => "We update our inventory daily. New vehicles are added regularly as soon as they pass inspection and quality checks."],

                    ["q" => "Do you offer financing options for used cars?",
                     "a" => "Yes, we work with leading banks and financial institutions to provide flexible financing options, including low down payment plans and competitive interest rates."],

                    ["q" => "What documents are required to purchase a used car?",
                     "a" => "You typically need a valid ID, driving license, and income proof for financed purchases. Our team will guide you through the process."],

                    ["q" => "Do your cars come with a warranty?",
                     "a" => "Many vehicles include warranty coverage or optional extended warranty plans. Please check individual listings for specific details."],

                    ["q" => "Can I trade in my current vehicle?",
                     "a" => "Yes, we accept trade-ins. Bring your vehicle for evaluation and receive a competitive market-based offer."],

                    ["q" => "Are your vehicles inspected before sale?",
                     "a" => "Every vehicle undergoes a multi-point inspection to ensure safety, quality, and reliability before being listed."],

                    ["q" => "Can I book a test drive?",
                     "a" => "Yes, you can book a test drive online or contact our showroom to schedule a convenient time."],

                    ["q" => "Do you handle documentation and ownership transfer?",
                     "a" => "Yes, we assist with all required paperwork including bank processing and ownership transfer."],
                ];
            @endphp

            @foreach($faqs as $index => $faq)

            <div class="accordion-item">
                <h2 class="accordion-header" id="heading{{ $index }}">
                    <button class="accordion-button collapsed"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $index }}">
                        {{ $faq['q'] }}
                    </button>
                </h2>

                <div id="collapse{{ $index }}"
                     class="accordion-collapse collapse"
                     data-bs-parent="#faqAccordion">

                    <div class="accordion-body">
                        {{ $faq['a'] }}
                    </div>
                </div>
            </div>

            @endforeach

        </div>

    </div>
</section>


@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    const swiper = new Swiper(".mainSwiper", {
        loop: false,
        navigation: {
            nextEl: ".main-next",
            prevEl: ".main-prev",
        }
    });

    // Thumbnail click
    document.querySelectorAll(".thumb").forEach((thumb) => {
        thumb.addEventListener("click", function () {
            let index = this.getAttribute("data-index");
            swiper.slideTo(index);
        });
    });

});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const carPrice = {{ $car->price }};
    const interestRate = 3.5 / 100;
    let years = 5;

    const slider = document.getElementById("downSlider");
    const downValue = document.getElementById("downValue");
    const downPercent = document.getElementById("downPercent");
    const emiAmount = document.getElementById("emiAmount");
    const emiYears = document.getElementById("emiYears");
    const summaryDown = document.getElementById("summaryDown");
    const loanAmountEl = document.getElementById("loanAmount");

    function calculateEMI() {

        const downPayment = parseFloat(slider.value);
        const loanAmount = carPrice - downPayment;

        const monthlyRate = interestRate / 12;
        const months = years * 12;

        const emi = (loanAmount * monthlyRate * Math.pow(1 + monthlyRate, months)) /
                    (Math.pow(1 + monthlyRate, months) - 1);

        downValue.innerText = downPayment.toLocaleString();
        summaryDown.innerText = downPayment.toLocaleString();
        loanAmountEl.innerText = loanAmount.toLocaleString();
        downPercent.innerText = ((downPayment / carPrice) * 100).toFixed(1);
        emiAmount.innerText = isFinite(emi) ? Math.round(emi).toLocaleString() : 0;
        emiYears.innerText = years;
    }

    slider.addEventListener("input", calculateEMI);

    document.querySelectorAll(".year-btn").forEach(btn => {
        btn.addEventListener("click", function () {
            document.querySelectorAll(".year-btn").forEach(b => b.classList.remove("active"));
            this.classList.add("active");
            years = parseInt(this.dataset.year);
            calculateEMI();
        });
    });

    calculateEMI();
});
</script>
@endpush
@endsection
