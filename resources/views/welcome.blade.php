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
                <!-- Dynamic Hero Text could be added to DB later, keeping static for now or using first slider title -->
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
            
            <!-- Dynamic Hero Slider -->
            <div class="col-lg-6 mt-5 mt-lg-0 text-center">
                <div class="position-relative">
                    @if($heroSliders->count() > 0)
                        <div id="heroCarousel" class="carousel slide hero-img shadow-lg" data-bs-ride="carousel" data-bs-interval="3000">
                            <div class="carousel-inner h-100 rounded-3 overflow-hidden">
                                @foreach($heroSliders as $key => $slider)
                                    <div class="carousel-item h-100 {{ $key == 0 ? 'active' : '' }}">
                                        <img src="{{ asset($slider->image_path) }}" class="d-block w-100 h-100 object-fit-cover" alt="{{ $slider->title }}">
                                    </div>
                                @endforeach
                            </div>
                            <!-- Indicators (Optional) -->
                            <div class="carousel-indicators">
                                @foreach($heroSliders as $key => $slider)
                                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}" aria-current="true"></button>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <!-- Fallback Static Image -->
                        <img src="{{ asset('images/coffee-hero.jpg') }}" onerror="this.src='https://placehold.co/600x600/5f674d/FFF?text=Watu+Coffee'" alt="Watu Coffee Hero" class="img-fluid hero-img">
                    @endif
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
                 <!-- Dynamic About Slider -->
                 @if($aboutSliders->count() > 0)
                     <div id="aboutCarousel" class="carousel slide arca-illustration shadow-lg rounded-lg overflow-hidden" data-bs-ride="carousel" data-bs-interval="3000">
                        <div class="carousel-inner h-100">
                            @foreach($aboutSliders as $key => $slider)
                                <div class="carousel-item active h-100 {{ $key == 0 ? 'active' : '' }}">
                                    <img src="{{ asset($slider->image_path) }}" class="d-block w-100 h-100 object-fit-cover" alt="{{ $slider->title }}">
                                </div>
                            @endforeach
                        </div>
                     </div>
                 @else
                     <!-- Fallback Static -->
                     <img src="{{ asset('images/roastery.jpg') }}" onerror="this.src='https://placehold.co/500x700/eaddcf/5f674d?text=Roastery'" alt="Our Roastery" class="img-fluid rounded-lg shadow-lg arca-illustration">
                 @endif
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
                            <small class="text-uppercase text-muted letter-spacing-1 font-weight-bold">Authentic</small>
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
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-end mb-5 px-2">
            <div class="text-center text-md-left mb-3 mb-md-0 mx-auto mx-md-0">
                <h6 class="text-olive text-uppercase letter-spacing-2 mb-2">Favorites</h6>
                <h2 class="font-serif font-weight-bold display-4">Customer's Choice</h2>
            </div>
            <!-- Desktop Button Position -->
             <a href="{{ route('order') }}" class="btn btn-watu-outline rounded-pill d-none d-md-inline-block hover-lift">View Full Menu</a>
        </div>
        
        <!-- Horizontal Slider with Snap -->
        <div class="horizontal-scroll-container pb-4" id="scrollContainer">
            <div class="row flex-nowrap m-0 p-0" id="featuredSlider">
                
                @forelse($topProducts as $product)
                <!-- Dynamic Card -->
                <div class="col-10 col-md-4 mb-4 px-2 scroll-item">
                    <div class="form-card h-100 text-center p-4 d-flex flex-column align-items-center transition-hover shadow-sm border-0">
                        <div class="mb-4 icon-box bg-olive-light-opacity rounded-circle p-3">
                            <span style="font-size: 3rem;">
                                {{ $product->is_drink ? '☕' : ($product->category_id == 2 ? '🫘' : '🥐') }}
                            </span>
                        </div>
                        <h4 class="font-serif mb-2">{{ $product->name }}</h4>
                        <!-- Using dummy description or category as subtitle since description might be long/missing -->
                        <p class="text-muted mb-3 small">{{ Str::limit($product->description ?? 'Delicious authentic flavor.', 60) }}</p>
                        
                        @if($product->category_id == 2) <!-- Roastery -->
                             <a href="{{ route('order') }}#pills-beans" class="btn btn-sm btn-watu-primary w-100 rounded-pill mt-auto">Buy</a>
                        @else
                             <a href="{{ route('order') }}" class="btn btn-sm btn-watu-outline w-100 rounded-pill mt-auto">Order Now</a>
                        @endif
                    </div>
                </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">No featured products yet.</p>
                    </div>
                @endforelse

            </div>
        </div>

        <div class="text-center mt-3 d-md-none">
            <a href="{{ route('order') }}" class="btn btn-watu-outline rounded-pill px-5 shadow-sm">View Full Menu</a>
        </div>
    </div>
</section>

<!-- Auto Slide Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('scrollContainer');
        let scrollAmount = 0;
        let isHovered = false;

        // Pause on hover
        container.addEventListener('mouseenter', () => isHovered = true);
        container.addEventListener('mouseleave', () => isHovered = false);

        function autoScroll() {
            if (!isHovered && container) {
                if (container.scrollLeft >= (container.scrollWidth - container.clientWidth)) {
                     container.scrollTo({left: 0, behavior: 'smooth'});
                } else {
                    container.scrollLeft += 2;
                }
            }
        }
        
        setInterval(autoScroll, 50);
    });
</script>

<!-- Call to Action -->
<section class="py-5 bg-white border-top">
    <div class="container text-center">
        <h2 class="font-serif mb-4">Ready to taste the difference?</h2>
        <a href="{{ route('order') }}" class="btn btn-lg btn-watu-gold px-5 shadow-lg">Order Online & Pickup</a>
    </div>
</section>
@endsection
