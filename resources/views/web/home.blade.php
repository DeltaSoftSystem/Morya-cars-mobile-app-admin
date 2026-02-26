@extends('web.layouts.app')
@section('title','Home')

@section('content')

{{-- HERO --}}
<section class="hero-section">
    <div class="container-xl">

        <div class="hero-card" data-aos="fade-up">

            <!-- Top Content -->
            <div class="row align-items-start">

                <div class="col-lg-9">
                    <h1 class="hero-title" data-aos="fade-right" data-aos-delay="100">
                        Find Your Perfect Used Car
                    </h1>
                    <p data-aos="fade-right" data-aos-delay="200">Inspected vehicles, fair prices, and trusted service.</p>
                </div>

                <div class="col-lg-3 text-lg-start mt-3 mt-lg-0">
                    <div class="sell-box" data-aos="zoom-in" data-aos-delay="300">
                        <div>
                            Sell your used car<br>
                            hassle free
                        </div>
                        <img src="{{asset('theme/img/car-sell-icon.png')}}" alt="" class="sale-badge">
                    </div>
                </div>

            </div>

            <!-- Filter Bar -->
            <div class="hero-filter"  data-aos="fade-up" data-aos-delay="350">

                <div class="filter-item">
                    <label>Monthly Installments</label>
                    <select class="form-select">
                        <option>Any Amount</option>
                    </select>
                </div>

                <div class="filter-item">
                    <label>Full Price</label>
                    <select class="form-select">
                        <option>Any Amount</option>
                    </select>
                </div>

                <button class="btn btn-warning hero-btn" data-aos="zoom-in" data-aos-delay="600">
                    <i class="fa fa-search"></i>
                    Show All Cars
                </button>

            </div>
            <img src="{{asset('theme/img/hero-car2.png')}}" class="hero-car" alt="Hero Car" >
        </div>
        
    </div>
</section>


<section class="deals-section">
    <div class="container-xl">

        <!-- Header -->
        <div class="deals-head d-flex justify-content-between align-items-center mb-4">
            <h2 class="deals-title">
                <i class="fa-solid fa-car-on text-warning"></i> Special Deals
            </h2>

            <a href="#" class="see-all-btn">
                See All →
            </a>
        </div>

        <!-- Cards Row -->
      

       <div class="swiper deals-swiper">
    <div class="swiper-wrapper">

        @foreach($cars as $car)
        @php
             $img = $car->images->where('is_primary',1)->first()
                   ?? $car->images->first();
        @endphp

        <div class="swiper-slide">
    <div class="deal-card">

        <div class="deal-img-wrap">
            <img src="{{ $img ? asset('storage/'.$img->image_path) : asset('theme/img/no-car.jpg') }}">
                    {{-- <button class="fav-btn">
                        <i class="fa-regular fa-heart"></i>
                    </button> --}}
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
</div>


        @endforeach

    </div>

    <div class="swiper-button-prev deals-prev"></div>
    <div class="swiper-button-next deals-next"></div>
</div>


    </div>
</section>



{{-- Sell your car facility --}}
<section class="container-xl my-4 pt-4 pb-4">
    <div class="row g-4">

        <!-- LEFT SELL CARD -->
        <div class="col-lg-3 col-md-12">
            <div class="sell-card h-100 text-center" data-aos="fade-right">
                <div class="sell-banner-wrap">
                    
                    <img src="{{asset('theme/img/sell-car-snap.jpg')}}" class="img-fluid" alt="car">

                    <a href="#" class="banner-btn-overlay"></a>
                </div>

            </div>
        </div>

        <!-- RIGHT CONTENT -->
        <div class="col-lg-9">
            <div class="row g-4">

                <!-- CARD 1 -->
                <div class="col-md-6">
                    <div class="feature-card h-100 text-center p-4" data-aos="zoom-in" data-aos-delay="100">

                        <div class="badge-row mb-3">
                            <span class="badge-pill"><i class="fa-regular fa-circle-check text-success"></i> Multi-Point Quality Check</span>
                            <span class="badge-pill"><i class="fa-regular fa-circle-check text-success"></i> Verified Odometer</span>
                            <span class="badge-pill"><i class="fa-regular fa-circle-check text-success"></i> Certified Documentation</span>
                        </div>

                        <img src="{{asset('theme/img/car-inspect.png')}}" class="img-fluid mb-3">

                        <p class="mb-0">
                            Each vehicle goes through a detailed technical and cosmetic evaluation before it reaches our showroom floor.
                        </p>
                    </div>
                </div>


                <!-- CARD 2 -->
                <div class="col-md-6">
                    <div class="feature-card h-100 text-center p-4" data-aos="zoom-in" data-aos-delay="250">

                        <span class="green-label"><i class="fa-regular fa-circle-check text-white"></i> Complimentary 12-Month Protection</span>

                        <img src="{{asset('theme/img/car-protect1.png')}}" class="img-fluid my-3">

                        <p class="mb-0">
                            Drive with confidence — selected cars include a one-year protection plan at no extra cost.
                        </p>
                    </div>
                </div>


                <!-- FINANCE STRIP -->
                <div class="col-12">
                    <div class="finance-card p-4 d-flex flex-wrap align-items-center justify-content-between"
                        data-aos="fade-up" data-aos-delay="150">

                        <div class="finance-row d-flex align-items-start gap-3">

    <div class="tick-circle">✓</div>

    <div class="finance-text">
        <h5 class="mb-1">Flexible finance made simple</h5>
        <p class="mb-0 text-muted">
            Choose from bank loans, low down-payment plans, or easy ownership programs.
        </p>
    </div>

</div>


                        <span class="zero-pill">0%</span>

                    </div>
                </div>


            </div>
        </div>

    </div>
</section>


{{-- New Stock --}}

<section class="deals-section">
    <div class="container-xl">

        <!-- Header -->
        <div class="deals-head d-flex justify-content-between align-items-center mb-4">
            <h2 class="deals-title">
                <i class="fa-solid fa-tag text-warning"></i>
               Fresh Arrivals
            </h2>

            <a href="#" class="see-all-btn">
                See All →
            </a>
        </div>

        <!-- Cards Row -->
      

       <div class="swiper deals-swiper">
    <div class="swiper-wrapper">

        @foreach($cars as $car)
        @php
             $img = $car->images->where('is_primary',1)->first()
                   ?? $car->images->first();
        @endphp

        <div class="swiper-slide">
    <div class="deal-card">

        <div class="deal-img-wrap">
            <img src="{{ $img ? asset('storage/'.$img->image_path) : asset('theme/img/no-car.jpg') }}">
                    {{-- <button class="fav-btn">
                        <i class="fa-regular fa-heart"></i>
                    </button> --}}
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
    </div>


        @endforeach

    </div>

    <div class="swiper-button-prev deals-prev"></div>
    <div class="swiper-button-next deals-next"></div>
    </div>


    </div>
</section>

{{-- sell your used car --}}
<section class="sell-hero-section my-5">
    <div class="container-xl sell-hero-box text-center" style="background:
     linear-gradient(180deg, rgba(248,249,250,0.92) 0%, rgba(233,237,241,0.85) 40%, rgba(15,23,32,0.92) 120%),
     url('{{ asset('theme/img/sell-used-car.png') }}');
     background-size: cover;
     background-position: center;
     background-repeat: no-repeat;">

        <h2 class="fw-bold mb-3">Sell your used car — hassle free</h2>

        <p class="text-muted mb-4">
            Get best market value, instant payout, and finance support.
            Complete the process in 3 simple steps — form, inspection, final offer.
        </p>

        <!-- CAR IMAGE -->
        <div class="hero-car-wrap mb-4">
            <img src="{{asset('theme/img/sellcar-mobile.png')}}" class="img-fluid hero-car">
            
        </div>

        <!-- STEPS -->
        <div class="row g-3 justify-content-center mb-4">

            <div class="col-md-4">
                <div class="step-card">
                    
                    <div class="step-no">
                        <svg width="43" height="43" viewBox="0 0 43 43" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_338_2367)"><path d="M17.9167 39.4168C17.9167 34.665 18.0126 28.3162 21.3727 24.9562C24.7327 21.5961 31.0815 21.5002 35.8333 21.5002C31.0815 21.5002 24.7327 21.4042 21.3727 18.0442C18.0126 14.6841 17.9167 8.33529 17.9167 3.5835C17.9167 8.33529 17.8207 14.6841 14.4607 18.0442C11.1006 21.4042 4.7518 21.5002 0 21.5002C4.7518 21.5002 11.1006 21.5961 14.4607 24.9562C17.8207 28.3162 17.9167 34.665 17.9167 39.4168Z" fill="white"></path></g><defs><clipPath id="clip0_338_2367"><rect width="43" height="43" fill="white"></rect></clipPath></defs></svg>
                        01
                    </div>
                    <div class="step-text">Submit Car Details</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="step-card">
                    <div class="step-no">
                        <svg width="44" height="43" viewBox="0 0 44 43" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_338_2375)"><path d="M18.5846 39.4168C18.5846 34.665 18.6806 28.3162 22.0406 24.9562C25.4007 21.5961 31.7495 21.5002 36.5013 21.5002C31.7495 21.5002 25.4007 21.4042 22.0406 18.0442C18.6806 14.6841 18.5846 8.33529 18.5846 3.5835C18.5846 8.33529 18.4887 14.6841 15.1286 18.0442C11.7686 21.4042 5.41976 21.5002 0.667969 21.5002C5.41976 21.5002 11.7686 21.5961 15.1286 24.9562C18.4887 28.3162 18.5846 34.665 18.5846 39.4168Z" fill="white"></path><path d="M38.293 16.125C38.293 14.6995 38.3218 12.7948 39.3298 11.7868C40.3378 10.7788 42.2424 10.75 43.668 10.75C42.2424 10.75 40.3378 10.7212 39.3298 9.7132C38.3218 8.70519 38.293 6.80054 38.293 5.375C38.293 6.80054 38.2642 8.70519 37.2562 9.7132C36.2482 10.7212 34.3435 10.75 32.918 10.75C34.3435 10.75 36.2482 10.7788 37.2562 11.7868C38.2642 12.7948 38.293 14.6995 38.293 16.125Z" fill="white"></path></g><defs><clipPath id="clip0_338_2375"><rect width="43" height="43" fill="white" transform="translate(0.667969)"></rect></clipPath></defs></svg>
                        02
                    </div>
                    <div class="step-text">Get Quick Evaluation</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="step-card">
                    <div class="step-no">
                        <svg width="44" height="43" viewBox="0 0 44 43" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_338_2384)"><path d="M18.2487 39.4168C18.2487 34.665 18.3447 28.3162 21.7047 24.9562C25.0647 21.5961 31.4136 21.5002 36.1654 21.5002C31.4136 21.5002 25.0647 21.4042 21.7047 18.0442C18.3447 14.6841 18.2487 8.33529 18.2487 3.5835C18.2487 8.33529 18.1527 14.6841 14.7927 18.0442C11.4327 21.4042 5.08383 21.5002 0.332031 21.5002C5.08383 21.5002 11.4327 21.5961 14.7927 24.9562C18.1527 28.3162 18.2487 34.665 18.2487 39.4168Z" fill="white"></path><path d="M37.957 16.125C37.957 14.6995 37.9858 12.7948 38.9938 11.7868C40.0018 10.7788 41.9065 10.75 43.332 10.75C41.9065 10.75 40.0018 10.7212 38.9938 9.7132C37.9858 8.70519 37.957 6.80054 37.957 5.375C37.957 6.80054 37.9282 8.70519 36.9202 9.7132C35.9122 10.7212 34.0076 10.75 32.582 10.75C34.0076 10.75 35.9122 10.7788 36.9202 11.7868C37.9282 12.7948 37.957 14.6995 37.957 16.125Z" fill="white"></path><path d="M36.1654 35.8332C36.1654 34.8828 36.1846 33.613 36.8566 32.941C37.5286 32.269 38.7983 32.2498 39.7487 32.2498C38.7983 32.2498 37.5286 32.2306 36.8566 31.5586C36.1846 30.8866 36.1654 29.6169 36.1654 28.6665C36.1654 29.6169 36.1462 30.8866 35.4742 31.5586C34.8022 32.2306 33.5324 32.2498 32.582 32.2498C33.5324 32.2498 34.8022 32.269 35.4742 32.941C36.1462 33.613 36.1654 34.8828 36.1654 35.8332Z" fill="white"></path></g><defs><clipPath id="clip0_338_2384"><rect width="43" height="43" fill="white" transform="translate(0.332031)"></rect></clipPath></defs></svg>
                        03
                    </div>
                    <div class="step-text">Instant Deal & Payment</div>
                </div>
            </div>

        </div>

        <a href="/sell-car" class="btn btn-gold px-5 py-3">
            Sell my car
        </a>

    </div>
</section>


{{-- Auto Finance Section --}}
<section class="finance-pro-section my-5">
    <div class="container-xl" >

        <div class="finance-pro-box text-center" style="background:
     linear-gradient(180deg, rgba(248,249,250,0.92) 0%, rgba(233,237,241,0.85) 40%, rgba(15,23,32,0.92) 120%),
     url('{{ asset('theme/img/finance-bg.jpg') }}');
     background-size: cover;
     background-position: center;
     background-repeat: no-repeat;">

            <span class="finance-tag">AUTO LOAN SUPPORT</span>

            <h2 class="finance-title">
                Check Your Car Loan Eligibility Instantly
            </h2>

            <p class="finance-sub">
                Fast approvals • Multiple bank tie-ups • Zero paperwork hassle • Best rate negotiation
            </p>

            <!-- feature row -->
            <div class="row g-3 justify-content-center finance-features">

                <div class="col-md-3">
                    <div class="fin-pill">✔ Multi-Bank Network</div>
                </div>

                <div class="col-md-3">
                    <div class="fin-pill">✔ Same Day Approval</div>
                </div>

                <div class="col-md-3">
                    <div class="fin-pill">✔ Lowest EMI Plans</div>
                </div>

                <div class="col-md-3">
                    <div class="fin-pill">✔ Documentation Help</div>
                </div>

            </div>

            <br>
            <div class="mt-3 d-flex gap-3 justify-content-center flex-wrap">

                <a href="/finance-check" class="btn btn-gold-pro">
                    Check Eligibility
                </a>
                <a href="#" class="btn btn-outline-warning pt-3">
                    Talk to Finance Expert
                </a>
                

            </div>

        </div>

    </div>
</section>


{{-- Why choose us --}}

<section class="why-section py-5">
    <div class="container-xl why-box text-center">

        <h2 class="why-title">Why Choose Morya Auto Hub</h2>

        <p class="why-sub mb-5">
            Premium pre-owned cars, verified quality, and transparent deals — all in one place.
        </p>

        <!-- FEATURE CARDS -->
        <div class="row g-4">

            <!-- CARD 1 -->
            <div class="col-md-4">
                <div class="why-card" data-aos="fade-up">
                    <div class="why-icon green">₹</div>
                    <img src="{{asset('theme/img/why1.png')}}" class="img-fluid why-car">
                    <h6>Easy Finance Support</h6>
                </div>
            </div>

            <!-- CARD 2 -->
            <div class="col-md-4">
                <div class="why-card" data-aos="fade-up" data-aos-delay="150">
                    <div class="why-icon green">✓</div>
                    <img src="{{asset('theme/img/why2.png')}}" class="img-fluid why-car">
                    <h6>Certified & Inspected Cars</h6>
                </div>
            </div>

            <!-- CARD 3 -->
            <div class="col-md-4">
                <div class="why-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="why-icon green"><i class="fa-solid fa-thumbs-up"></i></div>
                    <img src="{{asset('theme/img/why3.png')}}" class="img-fluid why-car">
                    <h6>Trusted by Customers</h6>
                </div>
            </div>

        </div>

        <!-- TRUST STRIP -->
        <div class="trust-strip mt-5 text-start" data-aos="zoom-in">

            <div class="row align-items-center g-4">

                <div class="col-md-3 text-center">
                    <img src="{{asset('theme/img/hero-car.png')}}" class="trust-badge">
                </div>

                <div class="col-md-9">
                    <h5>Quality Checked Used Cars</h5>
                    <p class="mb-0 text-muted">
                        Every vehicle goes through multi-point inspection and documentation verification.
                        We ensure only reliable, quality vehicles reach our customers.
                    </p>
                </div>

            </div>

        </div>

    </div>
</section>

{{-- faqs --}}
<section class="faq-contact-section py-5">
    <div class="container">

        <!-- FAQ -->
        <div class="faq-box mb-5">
            <h2 class="section-title mb-4">Frequently Asked Questions</h2>

            <div class="accordion custom-accordion" id="faqAccordion">

                <!-- ITEM -->
                <div class="accordion-item">
                    <button class="accordion-button collapsed" data-bs-toggle="collapse"
                        data-bs-target="#faq1">
                        What types of used cars do you offer?
                    </button>
                    <div id="faq1" class="accordion-collapse collapse"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            We offer a wide range of inspected and certified used cars across multiple brands and budgets.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <button class="accordion-button collapsed" data-bs-toggle="collapse"
                        data-bs-target="#faq2">
                        Do you provide vehicle financing options?
                    </button>
                    <div id="faq2" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            Yes, we provide flexible finance options with multiple bank tie-ups and quick approvals.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <button class="accordion-button collapsed" data-bs-toggle="collapse"
                        data-bs-target="#faq3">
                        What is your warranty policy?
                    </button>
                    <div id="faq3" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            Selected vehicles come with warranty coverage. Details depend on the car and plan selected.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <button class="accordion-button collapsed" data-bs-toggle="collapse"
                        data-bs-target="#faq4">
                        Can I trade in my current vehicle?
                    </button>
                    <div id="faq4" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            Yes, we offer trade-in services with fair market valuation.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <button class="accordion-button collapsed" data-bs-toggle="collapse"
                        data-bs-target="#faq5">
                        What documents are required?
                    </button>
                    <div id="faq5" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            Valid ID, driving license, and basic registration documents are required.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <button class="accordion-button collapsed" data-bs-toggle="collapse"
                        data-bs-target="#faq6">
                        Can I test drive before buying?
                    </button>
                    <div id="faq6" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            Absolutely — test drives are available for most vehicles.
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- CONTACT + MAP -->
        <div class="row g-4 align-items-stretch">

            <!-- CONTACT CARD -->
            <div class="col-lg-5">
                <div class="contact-card h-100">

                    <h4 class="mb-3">Contact Us</h4>

                    <div class="contact-item">
                        <span><i class="fa-solid fa-phone-volume"></i></span>
                        <div>
                            <small>Phone</small>
                            <strong>+91 XXXXX XXXXX</strong>
                        </div>
                    </div>

                    <div class="contact-item">
                        <span><i class="fa-solid fa-location-dot"></i></span>
                        <div>
                            <small>Morya AUTO HUB</small>
                            <p class="mb-1">
                                Jogeshwari - Vikhroli Link Rd,<br> 
                                near Suvarna Temple, Panchkutir Ganesh Nagar,<br>
                                Powai, Mumbai, Maharashtra 400076
                            </p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <span><i class="fa-regular fa-clock"></i></span>
                        <div>
                            <small>Working Hours</small>
                            <p class="mb-0">Mon – Sun : 11:00 AM – 8:00 PM</p>
                        </div>
                    </div>

                    <a href="https://www.google.com/maps?sca_esv=ea2551c9b8b376fe&sxsrf=ANbL-n79GZrBe54ATDvN86Zi2PpsXRNziA:1770793891959&biw=1536&bih=738&uact=5&gs_lp=Egxnd3Mtd2l6LXNlcnAiD21vcnlhY2FycyBwb3dhaTINEC4YgAQYxwEYDRivATIIEAAYCBgNGB4yAhAmMgsQABiABBiGAxiKBTILEAAYgAQYhgMYigUyCBAAGIAEGKIEMggQABiABBiiBDIIEAAYgAQYogQyHBAuGIAEGMcBGA0YrwEYlwUY3AQY3gQY4ATYAQFI_Q1QkgJY3AtwAXgBkAEAmAHAAaABswiqAQMwLja4AQPIAQD4AQGYAgegApwJwgIKEAAYsAMY1gQYR8ICBxAAGIAEGA3CAgYQABgWGB7CAggQABiiBBiJBZgDAIgGAZAGCLoGBggBEAEYFJIHBTEuNC4yoAelSLIHBTAuNC4yuAeSCcIHBzItMS41LjHIB1WACAA&um=1&ie=UTF-8&fb=1&gl=in&sa=X&geocode=KcEKaynwx-c7MVqX4fhc1Wje&daddr=Morya+Cars+Pvt+Ltd,+Jogeshwari+-+Vikhroli+Link+Rd,+near+Suvarna+Temple,+Panchkutir+Ganesh+Nagar,+Powai,+Mumbai,+Maharashtra+400076" class="btn btn-outline-warning mt-3 w-100">
                        Get Directions
                    </a>

                </div>
            </div>

            <!-- MAP -->
            <div class="col-lg-7">
                <div class="map-box h-100">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3769.6352360441256!2d72.90794187520646!3d19.123651582090766!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7c7f0296b0ac1%3A0xde68d55cf8e1975a!2sMorya%20Cars%20Pvt%20Ltd%20Powai%20Showroom!5e0!3m2!1sen!2sin!4v1770794093574!5m2!1sen!2sin"
                        width="100%" height="100%" style="border:0;"
                        allowfullscreen="" loading="lazy">
                    </iframe>

                </div>
            </div>

        </div>

    </div>
</section>


<!-- WhatsApp Floating -->
<a href="#" class="whatsapp-float">
    <i class="fab fa-whatsapp"></i>
</a>



@push('scripts')
<script>
const reveals = document.querySelectorAll('.reveal-slide');

const io = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('show');
    }
  });
}, { threshold: 0.2 });

reveals.forEach(el => io.observe(el));

//car deal cads slide
document.addEventListener("DOMContentLoaded", function () {

 new Swiper('.deals-swiper', {
    slidesPerView: 4,
    spaceBetween: 24,
    slidesPerGroup: 1,

    navigation: {
        nextEl: '.deals-next',
        prevEl: '.deals-prev',
    },

    breakpoints: {
        0: { slidesPerView: 1 },
        576: { slidesPerView: 2 },
        992: { slidesPerView: 3 },
        1200: { slidesPerView: 4 }
    }
 });

});

// hide social icons on scroll down
let lastScrollY = window.scrollY;

window.addEventListener("scroll", function () {
    const currentScrollY = window.scrollY;
    const social = document.querySelector(".social-float");

    if (!social) return;

    if (currentScrollY > lastScrollY && currentScrollY > 120) {
        // scrolling down
        social.classList.add("hide-on-scroll");
    } else {
        // scrolling up
        social.classList.remove("hide-on-scroll");
    }

    lastScrollY = currentScrollY;
});
</script>
@endpush
@endsection
