<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    
    <link rel="icon" href="{{ asset('admin/img/morya-logo.png') }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Aclonica&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <link href="{{ asset('theme/css/auto-theme.css') }}" rel="stylesheet">


    
</head>

<body>

@include('web.partials.header')

@yield('content')

@include('web.partials.footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>


<script>
window.addEventListener("scroll", function(){
    document.querySelector(".dealer-header")
    .classList.toggle("scrolled", window.scrollY > 40);
});

AOS.init({
        duration: 900,
        once: true,
        offset: 120,
        easing: 'ease-out-cubic'
    });
</script>

@stack('scripts')
</body>
</html>
