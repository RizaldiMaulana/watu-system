@extends('public.layout')

@section('content')
<div class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            
            <div class="col-lg-6 col-md-12 mb-5 mb-lg-0">
                <div class="hero-text ps-lg-4">
                    <h5 class="text-olive mb-3 text-uppercase tracking-[3px] font-semibold">
                        Est. 2025
                    </h5>
                    <h1 class="hero-title mb-4">
                        Experience the <br>
                        <span class="italic text-olive">Art</span> of Coffee
                    </h1>
                    <p class="hero-sub text-muted mb-5 max-w-[90%]">
                        Menyajikan kopi terbaik dari nusantara dengan sentuhan seni dan suasana yang menenangkan.
                    </p>
                    <div class="hero-buttons">
                        <a href="{{ route('order') }}" class="btn btn-watu-primary me-3">
                            Order Now
                        </a>
                        <a href="#about" class="btn btn-watu-outline smooth-scroll">
                            Learn More
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12 text-center position-relative">
                <div class="hero-image-frame">
                    <img src="{{ asset('images/banner-main.png') }}" 
                        alt="Watu Coffee Art" 
                        class="img-fluid w-100 object-cover"
                        style="height: 650px; width: 100%;">
                </div>
            </div>

        </div>
    </div>
</div>

<div id="about" class="section-padding bg-white"> 
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 text-center text-lg-start mb-4 mb-lg-0">
                <div class="about-image-frame">
                    <img src="{{ asset('images/about-arca.png') }}" 
                        alt="Watu Arca" 
                        class="img-fluid arca-illustration w-100 object-cover"
                        style="height: 500px;">
                </div>
            </div>
            
            <div class="col-lg-1"></div>
            
            <div class="col-lg-6 mt-4 mt-lg-0">
                <h6 class="text-olive text-uppercase mb-2 tracking-[2px] font-semibold">About Us</h6>
                <h2 class="mb-4 display-5 font-serif font-bold">
                    Filosofi Kopi & Batu
                </h2>
                <p class="lead mb-4 text-[#555]">
                    Watu Coffee bukan sekadar tempat minum kopi. Kami memadukan cita rasa kopi pilihan Nusantara dengan estetika seni yang abadi.
                </p>
                <p class="text-muted mb-5">
                     Seperti 'Watu' (Batu) yang kokoh dan abadi, kami berkomitmen menyajikan kualitas yang tak lekang oleh waktu dalam setiap seduhan, menciptakan ruang di mana cerita dan inspirasi bertemu.
                </p>
                <a href="{{ route('order') }}" class="btn btn-watu-primary">
                    Discover Our Menu
                </a>
            </div>
        </div>
    </div>
</div>

{{-- FEATURES SECTION --}}
<div class="section-padding">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h6 class="text-olive text-uppercase tracking-[2px] font-semibold">Why Choose Us</h6>
                <h2 class="display-5 font-serif font-bold">The Watu Experience</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="feature-box p-4 bg-white rounded-[20px] shadow-sm text-center h-100 transition hover:-translate-y-2 duration-300">
                    <div class="icon mb-4 text-olive text-4xl">
                        <i class="fa fa-coffee"></i>
                    </div>
                    <h4 class="font-serif font-bold mb-3">Premium Beans</h4>
                    <p class="text-muted">Biji kopi pilihan yang dipetik dan diproses dengan standar tertinggi untuk rasa yang autentik.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="feature-box p-4 bg-white rounded-[20px] shadow-sm text-center h-100 transition hover:-translate-y-2 duration-300">
                    <div class="icon mb-4 text-olive text-4xl">
                        <i class="fa fa-leaf"></i>
                    </div>
                    <h4 class="font-serif font-bold mb-3">Natural Process</h4>
                    <p class="text-muted">Kami mengutamakan proses alami yang menjaga kelestarian lingkungan dan kemurnian rasa.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box p-4 bg-white rounded-[20px] shadow-sm text-center h-100 transition hover:-translate-y-2 duration-300">
                    <div class="icon mb-4 text-olive text-4xl">
                        <i class="fa fa-heart"></i>
                    </div>
                    <h4 class="font-serif font-bold mb-3">Made with Love</h4>
                    <p class="text-muted">Setiap cangkir diseduh dengan passion dan dedikasi oleh barista berpengalaman kami.</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- FEATURED MENU SECTION --}}
<div class="section-padding bg-white">
    <div class="container">
        <div class="row align-items-end mb-5">
            <div class="col-lg-8">
                <h6 class="text-olive text-uppercase tracking-[2px] font-semibold">Our Favorites</h6>
                <h2 class="display-5 font-serif font-bold">Featured Menu</h2>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('order') }}" class="btn btn-watu-outline">View Full Menu</a>
            </div>
        </div>
        <div class="row">
            @if(isset($featured_products) && count($featured_products) > 0)
                @foreach($featured_products as $product)
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="menu-card-brewscape">
                        <div class="menu-card-img-container">
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}">
                        </div>
                        <h3 class="menu-card-title">{{ $product->name }}</h3>
                        <p class="menu-card-price">
                            Start from Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                        <a href="{{ route('order') }}" class="btn-add-brewscape text-center text-decoration-none">
                            Add to Cart
                        </a>
                    </div>
                </div>
                @endforeach
            @else
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Menu features are currently being updated.</p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- TESTIMONIALS SECTION --}}
<div class="section-padding text-white position-relative" style="background-color: var(--color-olive);">
    <div class="absolute inset-0 opacity-10" style="background-image: url('{{ asset('images/Bg.png') }}');"></div>
    <div class="container position-relative z-10">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <div class="mb-5">
                    <i class="fa fa-quote-left text-4xl opacity-50 mb-4"></i>
                    <h2 class="display-5 font-serif font-bold mb-4">Stories from our Customers</h2>
                </div>
                
                <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <p class="lead italic mb-4 text-lg">"Tempat paling nyaman untuk ngerjain tugas atau sekadar ngobrol santai. Kopinya juara, suasananya tenang banget!"</p>
                            <h5 class="font-bold uppercase tracking-widest text-sm">- Sarah A.</h5>
                        </div>
                        <div class="carousel-item">
                            <p class="lead italic mb-4 text-lg">"The best roastery in town! Biji kopinya fresh, manual brew-nya konsisten enak. Highly recommended."</p>
                            <h5 class="font-bold uppercase tracking-widest text-sm">- Budi S.</h5>
                        </div>
                        <div class="carousel-item">
                            <p class="lead italic mb-4 text-lg">"Pelayanan ramah dan tempatnya estetik parah. Bakal balik lagi pasti!"</p>
                            <h5 class="font-bold uppercase tracking-widest text-sm">- Linda K.</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- VISIT US SECTION --}}
<div class="section-padding bg-[#F9F7F2]">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <div class="bg-white p-5 rounded-[30px] shadow-lg relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-10">
                        <i class="fa fa-map-marker text-8xl text-olive"></i>
                    </div>
                    <h6 class="text-olive text-uppercase tracking-[2px] font-semibold mb-2">Visit Us</h6>
                    <h2 class="display-5 font-serif font-bold mb-4">Our Roastery</h2>
                    
                    <div class="d-flex align-items-start mb-4">
                        <div class="me-3 mt-1 text-olive"><i class="fa fa-map-marker fa-lg"></i></div>
                        <div>
                            <h5 class="font-bold mb-1">Address</h5>
                            <p class="text-muted">Jl. Srikandi Raya, Lerep Satu, Lerep, Kec. Ungaran Bar., Kabupaten Semarang<br>Indonesia, 50519</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start mb-4">
                        <div class="me-3 mt-1 text-olive"><i class="fa fa-clock-o fa-lg"></i></div>
                        <div>
                            <h5 class="font-bold mb-1">Opening Hours</h5>
                            <p class="text-muted display-block mb-1">Mon - Fri: 14:00 - 22:00</p>
                            <p class="text-muted">Sat - Sun: 14:00 - 23:00</p>
                        </div>
                    </div>

                    <a href="https://maps.google.com" target="_blank" class="btn btn-watu-primary w-100">Get Directions</a>
                </div>
            </div>
            <div class="col-lg-6 ps-lg-5">
                 <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3958.9669760763504!2d110.38618777414469!3d-7.129816669925754!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e708906868c76f1%3A0x743f8837ce2886b0!2sWatu%20Coffee!5e0!3m2!1sid!2sid!4v1765264232432!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
</div>

@endsection