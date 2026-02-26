<footer class="site-footer">
    <div class="container">

        <div class="row g-4">

            <!-- BRAND -->
            <div class="col-lg-3 col-md-6 text-center">
                {{-- <img src="{{ asset('admin/img/morya-logo.png') }}" alt="Logo" class="footer-logo mb-3">
                <div class="footer-brand">MORYA AUTO HUB</div> --}}

                <img src="{{asset('theme/img/morya-auto-logo-vertical.png')}}" class="footer-logo mb-3">
                <p class="footer-about">
                    Morya Auto Hub — trusted destination for quality pre-owned cars,
                    finance support, and verified vehicles.
                </p>
            </div>

            <!-- QUICK LINKS -->
            <div class="col-lg-2 col-md-6">
                <h6 class="footer-title">Quick Links</h6>
                <ul class="footer-links">
                    <li><a href="#">Buy Car</a></li>
                    <li><a href="#">Sell Car</a></li>
                    <li><a href="#">Finance</a></li>
                    <li><a href="#">Valuation</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>

            <!-- POPULAR BRANDS -->
            <div class="col-lg-2 col-md-6">
                <h6 class="footer-title">Popular Brands</h6>
                <ul class="footer-links">
                    <li><a href="#">BMW</a></li>
                    <li><a href="#">Audi</a></li>
                    <li><a href="#">Mercedes</a></li>
                    <li><a href="#">Toyota</a></li>
                    <li><a href="#">Hyundai</a></li>
                </ul>
            </div>

            <!-- SERVICES -->
            <div class="col-lg-2 col-md-6">
                <h6 class="footer-title">Services</h6>
                <ul class="footer-links">
                    <li><a href="#">Car Finance</a></li>
                    <li><a href="#">Trade-In</a></li>
                    <li><a href="#">Inspection</a></li>
                    <li><a href="#">Warranty</a></li>
                </ul>
            </div>

            <!-- CONTACT -->
            <div class="col-lg-3 col-md-6">
                <h6 class="footer-title">Contact</h6>

                <p class="footer-contact">
                    📞 +91 XXXXX XXXXX<br>
                    📍 Your showroom address here
                </p>

                <div class="footer-social">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                    <a href="#"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>

        </div>

        <!-- BOTTOM -->
        <div class="footer-bottom">
            <p>
                © {{ date('Y') }} Morya Auto Hub. All rights reserved.
            </p>
        </div>

    </div>
</footer>
