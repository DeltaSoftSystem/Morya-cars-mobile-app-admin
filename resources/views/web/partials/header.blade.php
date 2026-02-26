<nav class="navbar navbar-expand-lg navbar-dark dealer-header fixed-top">

    <div class="container-xl">

        {{-- Logo --}}
       <a class="navbar-brand brand-wrap" href="/">
            {{-- <img src="{{ asset('admin/img/morya-logo.png') }}" alt="Logo">
            <span class="brand-text">MORYA AUTO HUB</span> --}}
             <img src="{{ asset('theme/img/morya-auto-logo.png') }}" alt="Logo">
        </a>


        {{-- Mobile Toggle --}}
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Menu --}}
        <div class="collapse navbar-collapse" id="mainNav">

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-lg-4">

                <li class="nav-item">
                    <a class="nav-link dealer-link" href="#">Buy</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link dealer-link" href="#">Sell</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link dealer-link" href="#">Finance</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link dealer-link" href="#">About</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link dealer-link" href="#">Contact</a>
                </li>

            </ul>

            {{-- Right Icons --}}
            <div class="d-flex align-items-center gap-3 ms-lg-4">

                <a href="#" class="icon-link">
                    <i class="fa-solid fa-phone-volume"></i>
                </a>

                <a href="#" class="icon-link">
                    <i class="fa-solid fa-heart"></i>
                </a>

                <a href="#" class="icon-link">
                    <i class="fa-solid fa-user"></i>
                </a>


            </div>

        </div>
    </div>
</nav>

<div class="header-spacer"></div> {{-- spacer for fixed header --}}

<div class="social-float">
    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
    <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
    <a href="#"><i class="fa-brands fa-instagram"></i></a>
    <a href="#"><i class="fa-brands fa-youtube"></i></a>
</div>