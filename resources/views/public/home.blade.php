@extends('public.layout')

@section('content')
<div class="hero-section d-flex align-items-center" style="min-height: 100vh; padding-top: 80px;">
    <div class="container">
        <div class="row align-items-center">
            
            <div class="col-lg-6 col-md-12 mb-5 mb-lg-0">
                <div class="hero-text ps-lg-4">
                    <h5 class="text-olive mb-3 text-uppercase" style="font-weight: 600; letter-spacing: 3px; font-size: 14px;">
                        Est. 2025
                    </h5>
                    <h1 class="display-3 font-serif mb-4" style="font-weight: 800; line-height: 1.1; color: var(--color-dark);">
                        Experience the <br>
                        <span style="color: var(--color-olive); font-style: italic;">Art</span> of Coffee
                    </h1>
                    <p class="lead text-muted mb-5" style="font-size: 17px; line-height: 1.8; max-width: 90%;">
                        Menyajikan kopi terbaik dari nusantara dengan sentuhan seni dan suasana yang menenangkan.
                    </p>
                    <div class="hero-buttons">
                        <a href="{{ route('order') }}" class="btn btn-watu-primary me-3 px-5 py-3 rounded-pill shadow-sm">
                            Order Now
                        </a>
                        <a href="#about" class="btn btn-watu-outline px-5 py-3 rounded-pill">
                            Learn More
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12 text-center position-relative">
                 <div class="blob-bg" style="
                    position: absolute;
                    width: 450px; height: 450px;
                    background-color: #eaddcf;
                    border-radius: 50%;
                    top: 50%; left: 50%;
                    transform: translate(-50%, -50%);
                    z-index: 1;
                    opacity: 0.8;
                "></div>

                <img src="{{ asset('images/banner-main.png') }}" 
                     alt="Watu Coffee Art" 
                     class="hero-img img-fluid position-relative"
                     style="
                        width: 100%;
                        z-index: 2;
                        max-height: 500px; 
                        mix-blend-mode: multiply; 
                        filter: contrast(1.1) saturate(1.1);
                     ">
            </div>

        </div>
    </div>
</div>

<div id="about" class="section-padding"> 
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 text-center text-lg-start mb-4 mb-lg-0">
                <img src="{{ asset('images/about-arca.png') }}" 
                     alt="Watu Arca" 
                     class="img-fluid arca-illustration"
                     style="max-height: 500px; width: 100%; opacity: 0.9; filter: contrast(110%); mix-blend-mode: multiply;">
            </div>
            
            <div class="col-lg-1"></div> <div class="col-lg-6 mt-4 mt-lg-0">
                <h6 class="text-olive text-uppercase mb-2" style="letter-spacing: 2px;">About Us</h6>
                <h2 class="mb-4 display-5 font-serif" style="font-weight: 700; color: var(--color-dark);">
                    Filosofi Kopi & Batu
                </h2>
                <p class="lead mb-4" style="color: #555; font-weight: 400;">
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
@endsection