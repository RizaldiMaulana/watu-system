@extends('public.layout')

@section('content')
@section('content')
<!-- Page specific styles handled by public/css/style.css -->

<div class="container">
    
    @if(session('success'))
        <div class="alert alert-success text-center mt-5 bg-watu-olive text-white border-none">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger text-center mt-5">
            {{ session('error') }}
        </div>
    @endif

    <div class="page-header position-relative overflow-hidden">
        <div class="position-relative z-20">
            <h6 class="text-olive text-uppercase tracking-[3px]">Our Selections</h6>
            <h1 class="display-4 font-serif font-weight-bold">Menu & Reservation</h1>
        </div>
    </div>

    <ul class="nav nav-pills justify-content-center mb-5" id="pills-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="pills-cafe-tab" data-toggle="pill" href="#pills-cafe" role="tab">Cafe Menu</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pills-beans-tab" data-toggle="pill" href="#pills-beans" role="tab">Roast Beans</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pills-table-tab" data-toggle="pill" href="#pills-table" role="tab">Reservation</a>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
        
        <div class="tab-pane fade show active" id="pills-cafe" role="tabpanel">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <form action="{{ route('public.cafe.store') }}" method="POST">
                        @csrf
                        <div class="menu-section">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <h3 class="menu-category-title">Signature & Classic</h3>
                                </div>
                                
                                @foreach($cafe_products as $menu)
                                <div class="col-md-6 mb-4"> <div class="menu-item-row align-items-center" data-price="{{ $menu->price }}"> <div class="me-3">
                                            <input type="number" name="quantities[{{ $menu->id }}]" class="qty-input" min="0" value="" placeholder="0">
                                        </div>

                                        <div class="menu-item-name flex-grow-1">
                                            <span class="font-semibold text-base">{{ $menu->name }}</span>
                                        </div>

                                        <div class="menu-item-dots mx-2"></div>

                                        <div class="menu-item-price text-nowrap">
                                            {{ number_format($menu->price / 1000, 0) }}k
                                        </div>
                                    </div>
                                    
                                    <div class="menu-item-desc ms-5 ps-2"> Premium blend with authentic taste.
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="row mt-4">
                                <div class="col-12 text-center">
                                    <p class="text-muted small mb-3">Select items above, then proceed to checkout.</p>
                                    <button type="button" data-toggle="collapse" data-target="#orderFormCafe" class="btn btn-watu-primary">
                                        Proceed to Order
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="collapse" id="orderFormCafe">
                            <div class="row justify-content-center mt-4">
                                <div class="col-md-8">
                                    <div class="form-card"> <h5 class="text-center font-serif mb-4 text-watu-olive-dark">Complete Your Order</h5>
                                        
                                        <div class="row mb-4">
                                            <div class="col-6">
                                                <label class="small text-muted mb-1">Nama Anda</label>
                                                <input type="text" name="customer_name" class="form-control" placeholder="Contoh: Budi" required>
                                            </div>
                                            <div class="col-6">
                                                <label class="small text-muted mb-1">WhatsApp</label>
                                                <input type="number" name="whatsapp" class="form-control" placeholder="0812..." required>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="small text-muted mb-2 d-block">Metode Pembayaran</label>
                                            <div class="payment-box"> <div class="row g-3">
                                                    <div class="col-4">
                                                        <label class="w-100 cursor-pointer">
                                                            <input type="radio" name="payment_method" value="QRIS" class="d-none peer" required checked>
                                                            <div class="payment-option p-3 text-center border rounded bg-white h-100">
                                                                <i class="fa fa-qrcode mb-2 text-[20px]"></i><br>
                                                                <span class="small fw-bold">QRIS</span>
                                                            </div>
                                                        </label>
                                                    </div>
                                                    <div class="col-4">
                                                        <label class="w-100 cursor-pointer">
                                                            <input type="radio" name="payment_method" value="Cash" class="d-none peer">
                                                            <div class="payment-option p-3 text-center border rounded bg-white h-100">
                                                                <i class="fa fa-money mb-2 text-[20px]"></i><br>
                                                                <span class="small fw-bold">Tunai</span>
                                                            </div>
                                                        </label>
                                                    </div>
                                                    <div class="col-4">
                                                        <label class="w-100 cursor-pointer">
                                                            <input type="radio" name="payment_method" value="Debit/Credit" class="d-none peer">
                                                            <div class="payment-option p-3 text-center border rounded bg-white h-100">
                                                                <i class="fa fa-credit-card mb-2 text-[20px]"></i><br>
                                                                <span class="small fw-bold">Debit/CC</span>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center mt-4">
                                            <button type="submit" class="btn btn-watu-primary w-100 py-3 shadow">
                                                Konfirmasi & Bayar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

       <div class="tab-pane fade" id="pills-beans" role="tabpanel">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    
                    <form action="{{ route('public.cafe.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            @foreach($roast_beans as $bean)
                            <div class="col-lg-4 col-md-6 mb-5">
                                <div class="roast-card-alder">
                                    <div class="roast-card-image">
                                        <!-- Placeholder if no image, or logic to show default -->
                                        @if($bean->image)
                                            <img src="{{ asset('storage/' . $bean->image) }}" alt="{{ $bean->name }}">
                                        @else
                                            <img src="{{ asset('images/coffee-bag-mockup.png') }}" alt="{{ $bean->name }}" style="opacity:0.5"> 
                                            <!-- Note: Ensure you have a fallback or handle this gracefully. For now reusing generic logic or leaving distinct -->
                                        @endif
                                    </div>
                                    
                                    <div class="text-center mb-3">
                                        <div class="roast-subtitle">Single Origin</div>
                                        <h3 class="roast-title">{{ $bean->name }}</h3>
                                        <p class="roast-desc">
                                            Notes: Citrus, Brown Sugar, Floral Finish<br>
                                            <span class="badge bg-light text-dark border mt-2">Full Wash</span>
                                            <span class="badge bg-light text-dark border mt-2">Arabica</span>
                                        </p>
                                    </div>

                                    <div class="roast-options-box">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="roast-option-title mb-0">Price (200g)</span>
                                            <span class="roast-price-tag">Rp {{ number_format($bean->price, 0, ',', '.') }}</span>
                                        </div>
                                        
                                        <div class="form-group mb-0">
                                            <label class="roast-option-title text-center w-100 d-block mb-2">Quantity</label>
                                            <div class="d-flex justify-content-center align-items-center">
                                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle" style="width:30px;height:30px;" onclick="adjustQty(this, -1)">-</button>
                                                <!-- Hidden 'bean-row' equivalent for JS calc logic -->
                                                <div class="bean-row d-none" data-price="{{ $bean->price }}"></div>
                                                <input type="number" name="quantities[{{ $bean->id }}]" class="qty-input form-control text-center mx-2 border-0 bg-transparent font-weight-bold" style="width:60px; font-size:1.2rem;" min="0" value="0" placeholder="0">
                                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle" style="width:30px;height:30px;" onclick="adjustQty(this, 1)">+</button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <!-- Inline JS script for the +/- buttons since we just added them -->
                        <script>
                            function adjustQty(btn, val) {
                                const input = btn.parentElement.querySelector('input');
                                let current = parseInt(input.value) || 0;
                                let newVal = current + val;
                                if(newVal < 0) newVal = 0;
                                input.value = newVal;
                                // Trigger input event for the global calculator
                                input.dispatchEvent(new Event('input', { bubbles: true }));
                            }
                        </script>

                        <div class="row mt-4">
                            <div class="col-12 text-center">
                                <p class="text-muted small mb-3">Pilih beans di atas, lalu isi data pengiriman.</p>
                                <button type="button" data-toggle="collapse" data-target="#orderFormBeans" class="btn btn-watu-primary">
                                    Proceed to Checkout
                                </button>
                            </div>
                        </div>

                        <div class="collapse" id="orderFormBeans">
                            <div class="row justify-content-center mt-4">
                                <div class="col-md-8">
                                    <div class="form-card"> <h5 class="text-center font-serif mb-4 text-watu-olive-dark">Complete Your Order</h5>
                                        
                                        <div class="row mb-4">
                                            <div class="col-6">
                                                <label class="small text-muted mb-1">Nama Anda</label>
                                                <input type="text" name="customer_name" class="form-control" placeholder="Contoh: Budi" required>
                                            </div>
                                            <div class="col-6">
                                                <label class="small text-muted mb-1">WhatsApp</label>
                                                <input type="number" name="whatsapp" class="form-control" placeholder="0812..." required>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="small text-muted mb-2 d-block">Metode Pembayaran</label>
                                            <div class="payment-box"> <div class="row g-3">
                                                    <div class="col-4">
                                                        <label class="w-100 cursor-pointer">
                                                            <input type="radio" name="payment_method" value="QRIS" class="d-none peer" required checked>
                                                            <div class="payment-option p-3 text-center border rounded bg-white h-100">
                                                                <i class="fa fa-qrcode mb-2 text-[20px]"></i><br>
                                                                <span class="small fw-bold">QRIS</span>
                                                            </div>
                                                        </label>
                                                    </div>
                                                    <div class="col-4">
                                                        <label class="w-100 cursor-pointer">
                                                            <input type="radio" name="payment_method" value="Cash" class="d-none peer">
                                                            <div class="payment-option p-3 text-center border rounded bg-white h-100">
                                                                <i class="fa fa-money mb-2 text-[20px]"></i><br>
                                                                <span class="small fw-bold">Tunai</span>
                                                            </div>
                                                        </label>
                                                    </div>
                                                    <div class="col-4">
                                                        <label class="w-100 cursor-pointer">
                                                            <input type="radio" name="payment_method" value="Debit/Credit" class="d-none peer">
                                                            <div class="payment-option p-3 text-center border rounded bg-white h-100">
                                                                <i class="fa fa-credit-card mb-2 text-[20px]"></i><br>
                                                                <span class="small fw-bold">Debit/CC</span>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center mt-4">
                                            <button type="submit" class="btn btn-watu-primary w-100 py-3 shadow">
                                                Konfirmasi & Bayar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                    
                    <div class="text-center mt-4">
                        <p class="text-muted">Beans are roasted fresh weekly. Contact us for bulk orders.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="pills-table" role="tabpanel">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="reservation-box shadow-lg">
                        <div class="text-center mb-4">
                            <h3 class="font-serif mb-2">Table Reservation</h3>
                            <p class="opacity-80">Book a spot to enjoy the vibe.</p>
                        </div>
                        <form action="{{ route('public.reservation.store') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="small text-uppercase font-weight-bold opacity-70">Your Name</label>
                                <input type="text" name="name" class="form-control form-control-dark" required>
                            </div>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="small text-uppercase font-weight-bold opacity-70">Date</label>
                                    <input type="date" name="date" class="form-control form-control-dark" required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="small text-uppercase font-weight-bold opacity-70">Time</label>
                                    <input type="time" name="time" class="form-control form-control-dark" required>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label class="small text-uppercase font-weight-bold opacity-70">Guest Count</label>
                                <input type="number" name="pax" class="form-control form-control-dark" min="1" value="2" required>
                            </div>
                            <button type="submit" class="btn btn-watu-gold w-100 py-3 text-uppercase tracking-[2px]">Confirm Booking</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    // Format IDR currency
    const formatIDR = (number) => {
        return new Intl.NumberFormat('id-ID', { 
            style: 'currency', 
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(number);
    };

    // Calculate Totals
    const updateTotals = (containerId, buttonId) => {
        let totalQty = 0;
        let totalPrice = 0;

        $(containerId).find('.qty-input').each(function() {
            const qty = parseInt($(this).val()) || 0;
            // For Cafe items, price is on .menu-item-row. For Beans, we put it on .bean-row logic below
            let price = 0;
            
            const menuRow = $(this).closest('.menu-item-row');
            const beanRow = $(this).closest('tr').find('.bean-row');

            if (menuRow.length) {
                price = parseFloat(menuRow.data('price')) || 0;
            } else if (beanRow.length) {
                price = parseFloat(beanRow.data('price')) || 0;
            }

            if (qty > 0) {
                totalQty += qty;
                totalPrice += (qty * price);
            }
        });

        const btn = $(buttonId);
        if (totalQty > 0) {
            btn.html(`Proceed to Order (${totalQty} items - ${formatIDR(totalPrice)})`);
            btn.removeClass('btn-watu-primary').addClass('btn-watu-gold shadow-lg');
            
            // Highlight the button with a pulse if it just changed state to active
            if (!btn.hasClass('has-items')) {
                btn.addClass('has-items pulse-animation');
            }
        } else {
            btn.html('Proceed to Order');
            btn.addClass('btn-watu-primary').removeClass('btn-watu-gold shadow-lg has-items pulse-animation');
        }
    };

    // Event Listeners
    $('#pills-cafe .qty-input').on('input', function() {
        updateTotals('#pills-cafe', '#pills-cafe [data-target="#orderFormCafe"]');
        
        // Visual feedback on row
        const row = $(this).closest('.menu-item-row');
        if ($(this).val() > 0) {
            row.addClass('bg-watu-olive/10');
        } else {
            row.removeClass('bg-watu-olive/10');
        }
    });

    $('#pills-beans .qty-input').on('input', function() {
        updateTotals('#pills-beans', '#pills-beans [data-target="#orderFormBeans"]');
         // Visual feedback row
         const row = $(this).closest('tr');
         if ($(this).val() > 0) {
             row.addClass('bg-gray-50');
         } else {
             row.removeClass('bg-gray-50');
         }
    });
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a.smooth-scroll').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Animate tab content on switch
    $('.nav-pills a').on('shown.bs.tab', function (e) {
        const targetPane = $($(e.target).attr('href'));
        targetPane.css('opacity', 0).animate({opacity: 1}, 300);
    });

    // Hash based tab switching
    var url = document.location.toString();
    if (url.match('#')) {
        $('.nav-pills a[href="#' + url.split('#')[1] + '"]').tab('show');
    } 

    // Add animation to form reveal
    $('[data-toggle="collapse"]').on('click', function() {
        const target = $($(this).data('target'));
        if (!target.hasClass('show')) {
            setTimeout(() => {
                target.find('.form-card').css('opacity', 0).animate({opacity: 1}, 400);
            }, 100);
        }
    });
    
    // Payment option selection animation
    $('input[name="payment_method"]').on('change', function() {
        $('.payment-option').each(function() {
            $(this).removeClass('scale-105 border-watu-olive bg-gray-50');
        });
        const selected = $(this).next('.payment-option');
        selected.addClass('scale-105');
        // Add active states if needed, but styling seems handled by peer-checked in CSS
    });
</script>
@endsection