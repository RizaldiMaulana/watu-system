@extends('public.layout')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <!-- Animated Blob Background -->
    <div class="blob-bg"></div>
    
    <div class="container relative z-10">
        <div class="row align-items-center">
            <div class="col-lg-6 hero-text">
                <h5 class="text-olive text-uppercase font-weight-bold letter-spacing-2 mb-3">Welcome to Watu</h5>
                <h1 class="display-3 font-serif font-weight-bold mb-4">Experience the <span class="text-olive">Perfect Roast</span></h1>
                <p class="lead text-muted mb-5">
                    Discover the authentic taste of locally sourced, premium coffee beans. 
                    Roasted to perfection for those who appreciate the finer details.
                </p>
                <div class="hero-buttons">
                    <a href="{{ route('order') }}#pills-cafe" class="btn btn-watu-primary me-3">Order Now</a>
                    <a href="#about" class="btn btn-watu-outline smooth-scroll">Our Story</a>
                </div>
            </div>
            <div class="col-lg-6 mt-5 mt-lg-0 text-center">
                <div class="position-relative">
                    <img src="{{ asset('images/coffee-hero.jpg') }}" onerror="this.src='https://placehold.co/600x600/5f674d/FFF?text=Watu+Coffee'" alt="Watu Coffee Hero" class="img-fluid hero-img">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="section-padding bg-white position-relative overflow-hidden">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 mb-5 mb-lg-0">
                 <img src="{{ asset('images/roastery.jpg') }}" onerror="this.src='https://placehold.co/500x700/eaddcf/5f674d?text=Roastery'" alt="Our Roastery" class="img-fluid rounded-lg shadow-lg arca-illustration">
            </div>
            <div class="col-lg-6 offset-lg-1">
                <div class="form-card border-0 bg-transparent shadow-none">
                    <h6 class="text-olive text-uppercase letter-spacing-2 mb-3">Our Story</h6>
                    <h2 class="font-serif font-weight-bold mb-4">More Than Just Coffee</h2>
                    <p class="text-muted mb-4" style="line-height: 1.8;">
                        At Watu Coffee, we believe that every cup tells a story. From the farmers who cultivate the beans to our master roasters who bring out their unique flavors, we are dedicated to excellence at every step.
                    </p>
                    <p class="text-muted mb-4" style="line-height: 1.8;">
                        Our journey began with a simple passion: to find the perfect bean. Today, we share that passion with you through our carefully curated selection of single-origin blends and signature roasts.
                    </p>
                    <div class="row mt-5">
                        <div class="col-4 text-center">
                            <h3 class="font-serif text-olive display-4">100%</h3>
                            <small class="text-uppercase text-muted letter-spacing-1 font-weight-bold">Arabica</small>
                        </div>
                        <div class="col-4 text-center">
                            <h3 class="font-serif text-olive display-4">Daily</h3>
                            <small class="text-uppercase text-muted letter-spacing-1 font-weight-bold">Fresh Roast</small>
                        </div>
                        <div class="col-4 text-center">
                            <h3 class="font-serif text-olive display-4">Local</h3>
                            <small class="text-uppercase text-muted letter-spacing-1 font-weight-bold">Sourcing</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Section -->
<section class="section-padding" style="background-color: var(--color-cream);">
    <div class="container">
        <div class="text-center mb-5">
            <h6 class="text-olive text-uppercase letter-spacing-2 mb-2">Favorites</h6>
            <h2 class="font-serif font-weight-bold">Customer's Choice</h2>
        </div>
        
        <div class="row">
            <!-- Card 1 -->
            <div class="col-lg-4 mb-4">
                <div class="form-card h-100 text-center p-5 d-flex flex-column align-items-center transition-hover">
                    <div class="mb-4 icon-box">
                        <span style="font-size: 3.5rem;">☕</span>
                    </div>
                    <h4 class="font-serif mb-3">Signature Latte</h4>
                    <p class="text-muted mb-4">Creamy, rich, and perfectly balanced. Our house blend espresso with velvety steamed milk.</p>
                    <a href="{{ route('order') }}#pills-cafe" class="btn btn-watu-outline mt-auto w-100 rounded-pill">Order Now</a>
                </div>
            </div>
            
            <!-- Card 2 -->
            <div class="col-lg-4 mb-4">
                <div class="form-card h-100 text-center p-5 d-flex flex-column align-items-center transition-hover border-olive">
                    <div class="mb-4 icon-box">
                        <span style="font-size: 3.5rem;">🫘</span>
                    </div>
                    <h4 class="font-serif mb-3">Single Origin Roast</h4>
                    <p class="text-muted mb-4">Experience the distinct flavors of our weekly rotating single-origin beans.</p>
                    <a href="{{ route('order') }}#pills-beans" class="btn btn-watu-primary mt-auto w-100 rounded-pill">Buy Beans</a>
                </div>
            </div>
            
            <!-- Card 3 -->
            <div class="col-lg-4 mb-4">
                <div class="form-card h-100 text-center p-5 d-flex flex-column align-items-center transition-hover">
                    <div class="mb-4 icon-box">
                        <span style="font-size: 3.5rem;">🥐</span>
                    </div>
                    <h4 class="font-serif mb-3">Fresh Pastries</h4>
                    <p class="text-muted mb-4">The perfect companion to your coffee. Baked fresh every morning.</p>
                    <a href="{{ route('order') }}" class="btn btn-watu-outline mt-auto w-100 rounded-pill">View Menu</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 bg-white border-top">
    <div class="container text-center">
        <h2 class="font-serif mb-4">Ready to taste the difference?</h2>
        <a href="{{ route('order') }}" class="btn btn-lg btn-watu-gold px-5 shadow-lg">Order Online & Pickup</a>
    </div>
</section>
@endsection
