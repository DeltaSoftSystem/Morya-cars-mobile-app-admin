@extends('web.layouts.app')
@section('title','About Us')

@section('content')

<section class="about-hero-section">

    <div class="container-xl">

        {{-- Breadcrumb --}}
        <div class="about-breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>›</span>
            <span>About Us</span>
        </div>

        {{-- Title Box --}}
        <div class="about-title-box">
            <h1>
                About Morya Auto Hub: Your Trusted Used Car Dealership
            </h1>
        </div>

    </div>

    {{-- Background Image --}}
    <div class="about-hero-image">
        <img src="{{ asset('theme/img/about-hero.png') }}" alt="About Morya Auto Hub">
    </div>

</section>

<section class="about-content-section py-5">

    <div class="container-xl">

        <div class="row align-items-center g-5">

            {{-- LEFT CONTENT --}}
            <div class="col-lg-6">

                <h2 class="about-main-heading">
                    Driven by Trust. Powered by Transparency.
                </h2>

                <p class="about-text">
                    At <strong>Morya Auto Hub</strong>, we believe that buying or selling a car should be simple,
                    transparent, and rewarding. Built on trust and long-term customer relationships,
                    our mission is to provide high-quality pre-owned vehicles along with a smooth,
                    stress-free experience from start to finish.
                </p>

                <p class="about-text">
                    Every vehicle in our inventory is carefully selected and thoroughly inspected
                    to ensure reliability, performance, and value. Whether you're looking for a
                    practical family car, a stylish sedan, or a feature-packed SUV,
                    we help you find the perfect match for your lifestyle and budget.
                </p>

                <p class="about-text">
                    Beyond car sales, we also make selling your vehicle easy and transparent.
                    Our fair evaluation process ensures competitive pricing with minimal hassle.
                    No hidden surprises — just honest guidance and dependable service.
                </p>

            </div>

            {{-- RIGHT SIDE HIGHLIGHT BOXES --}}
            <div class="col-lg-6">

                <div class="about-feature-card">
                    <h5>✔ Quality Inspected Vehicles</h5>
                    <p>Every car undergoes detailed checks to ensure performance and reliability.</p>
                </div>

                <div class="about-feature-card">
                    <h5>✔ Transparent Pricing</h5>
                    <p>Clear documentation and honest evaluations with no hidden costs.</p>
                </div>

                <div class="about-feature-card">
                    <h5>✔ Customer-First Approach</h5>
                    <p>We prioritize your needs and guide you through every step confidently.</p>
                </div>

                <div class="about-feature-card">
                    <h5>✔ Hassle-Free Selling</h5>
                    <p>Quick, smooth process for customers looking to sell their cars.</p>
                </div>

            </div>

        </div>

    </div>

</section>

<section class="why-choose-section py-5">
    <div class="container-xl">
        <div class="row align-items-center g-5">

            {{-- IMAGE --}}
            <div class="col-lg-4">
                <img src="{{ asset('theme/img/morya-car-delivery.jpg') }}" 
                     class="img-fluid rounded-4 shadow-sm"
                     alt="Morya Auto Hub">
            </div>

            {{-- CONTENT --}}
            <div class="col-lg-8">
                <h2 class="section-heading mb-3">
                    Why Choose Morya Auto Hub?
                </h2>

                <p class="section-text">
                    At <strong>Morya Auto Hub</strong>, trust and transparency are at the heart of everything we do. 
                    We are committed to delivering a smooth and reliable car buying and selling experience 
                    backed by honest pricing and professional service.
                </p>

                <p class="section-text">
                    Whether you're looking to purchase a quality pre-owned vehicle or sell your car at a 
                    competitive market price, our experienced team ensures a hassle-free process from start to finish.
                </p>

            </div>
        </div>
    </div>
</section>

<section class="vehicle-range-section py-5">
    <div class="container-xl">

        <div class="section-header text-center mb-5">
            <h2>Our Vehicle Categories</h2>
            <p>Explore a wide range of quality pre-owned vehicles across multiple segments.</p>
        </div>

        <div class="row g-4">

            @php
                $categories = [
                    'Sedans','SUVs','Hatchbacks','Luxury Cars',
                    'Sports Cars','Compact Cars','Family Vehicles',
                    'Premium SUVs','Budget Friendly Cars',
                    'Automatic & Manual','Petrol & Diesel','Low Mileage Options'
                ];
            @endphp

            @foreach($categories as $category)
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="category-card">
                        <span>✔</span>
                        <p>{{ $category }}</p>
                    </div>
                </div>
            @endforeach

        </div>

    </div>
</section>
<section class="difference-section py-5 bg-light">
    <div class="container-xl">

        <div class="section-header text-center mb-5">
            <h2>What Makes Us Different?</h2>
        </div>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="feature-card">
                    <div class="icon">💰</div>
                    <h5>Transparent Pricing</h5>
                    <p>No hidden charges. Honest and fair market pricing.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card">
                    <div class="icon">🔍</div>
                    <h5>Quality Checked Vehicles</h5>
                    <p>Each car is thoroughly inspected for reliability.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card">
                    <div class="icon">🤝</div>
                    <h5>Customer First Approach</h5>
                    <p>Professional guidance and smooth documentation process.</p>
                </div>
            </div>

        </div>

    </div>
</section>
<section class="process-section py-5 bg-light">
    <div class="container-xl">

        <div class="section-header text-center mb-5">
            <h2 class="process-title">Buying a Used Car Made Simple</h2>
            <p class="process-sub">
                A smooth and transparent experience from selection to ownership.
            </p>
        </div>

        <div class="row g-4">

            <div class="col-lg-3 col-md-6">
                <div class="process-card">
                    <div class="process-number">01</div>
                    <div class="process-icon">
                        <i class="fa-solid fa-car-side"></i>
                    </div>
                    <h5>Explore Inventory</h5>
                    <p>Browse our available cars online or visit our showroom.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="process-card">
                    <div class="process-number">02</div>
                    <div class="process-icon">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <h5>Inspect & Test Drive</h5>
                    <p>Check the vehicle thoroughly and take it for a drive.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="process-card">
                    <div class="process-number">03</div>
                    <div class="process-icon">
                        <i class="fa-solid fa-file-invoice"></i>
                    </div>
                    <h5>Review Details</h5>
                    <p>Get complete transparency on pricing and documentation.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="process-card">
                    <div class="process-number">04</div>
                    <div class="process-icon">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <h5>Complete Purchase</h5>
                    <p>Quick paperwork and smooth ownership transfer.</p>
                </div>
            </div>

        </div>

    </div>
</section>

<section class="selling-section py-5">
    <div class="container-xl text-white">

        <div class="section-header text-center mb-5">
            <h2>Selling Your Car – Fast & Hassle-Free</h2>
        </div>

        <div class="row g-4">

            <div class="col-md-3">
                <div class="sell-step-card">
                    <h6>01. Submit Details</h6>
                    <p>Share your vehicle information easily.</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="sell-step-card">
                    <h6>02. Evaluation</h6>
                    <p>Our experts assess your car fairly.</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="sell-step-card">
                    <h6>03. Instant Offer</h6>
                    <p>Receive a competitive market offer.</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="sell-step-card">
                    <h6>04. Quick Payment</h6>
                    <p>Complete paperwork and receive payment smoothly.</p>
                </div>
            </div>

        </div>

    </div>
</section>

<section class="finance-section py-5 bg-light">
    <div class="container-xl">

        <div class="finance-box text-center">
            <h2>Flexible Car Finance Options</h2>
            <p>
                Morya Auto Hub offers flexible finance assistance to make vehicle ownership easier and affordable.
                Our team supports you through eligibility checks, documentation, and approvals for a smooth experience.
            </p>
        </div>

    </div>
</section>

@endsection