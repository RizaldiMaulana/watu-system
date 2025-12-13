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
        <li class="nav-item">
            <a class="nav-link {{ (isset($activeTab) && $activeTab == 'cafe') ? 'active' : ((!isset($activeTab)) ? 'active' : '') }}" id="pills-cafe-tab" data-toggle="pill" href="#pills-cafe" role="tab">Cafe Menu</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ (isset($activeTab) && $activeTab == 'beans') ? 'active' : '' }}" id="pills-beans-tab" data-toggle="pill" href="#pills-beans" role="tab">Roast Beans</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pills-table-tab" data-toggle="pill" href="#pills-table" role="tab">Reservation</a>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
        
        <div class="tab-pane fade {{ (isset($activeTab) && $activeTab == 'cafe') ? 'show active' : ((!isset($activeTab)) ? 'show active' : '') }}" id="pills-cafe" role="tabpanel">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <form action="{{ route('public.cafe.store') }}" method="POST">
                        @csrf
                        <div class="menu-section">
                            <div class="row">

                                @if(count($cafe_menu) > 0)
                                    @foreach($cafe_menu as $categoryLabel => $items)
                                        <div class="col-md-12 text-center mt-5 mb-3">
                                            <h3 class="menu-category-title text-uppercase">{{ ucwords(str_replace('_', ' ', $categoryLabel)) }}</h3>
                                        </div>
                                        
                                        @foreach($items as $menu)
                                        <div class="col-md-6 mb-4"> 
                                            <div class="menu-item-row align-items-center d-flex px-4 py-3 rounded hover:bg-gray-50 transition" data-price="{{ $menu->price }}"> 
                                                
                                                <div class="d-flex align-items-center bg-gray-50 rounded-full px-1 py-1 me-3 shrink-0">
                                                    <button type="button" class="btn-qty-minus w-7 h-7 flex items-center justify-center bg-white text-gray-400 hover:text-gray-600 shadow-sm border border-gray-200 focus:outline-none transition active:scale-95" style="border-radius: 50% !important;" onclick="adjustQty(this, -1)">
                                                        <i class="fa fa-minus text-[8px]"></i>
                                                    </button>
                                                    <input type="number" name="quantities[{{ $menu->id }}]" class="qty-input form-control text-center mx-1 border-0 bg-transparent p-0 font-bold text-gray-700 w-8 text-sm" min="0" value="" placeholder="0" readonly>
                                                    <button type="button" class="btn-qty-plus w-7 h-7 flex items-center justify-center bg-[#5f674d] text-black shadow-md hover:bg-[#4a503a] focus:outline-none transition active:scale-95" style="border-radius: 50% !important;" onclick="adjustQty(this, 1)">
                                                        <i class="fa fa-plus text-[8px]"></i>
                                                    </button>
                                                </div>

                                                <div class="menu-item-name flex-grow-1">
                                                    <span class="font-semibold text-base block text-gray-800">{{ $menu->name }}</span>
                                                </div>

                                                <div class="menu-item-dots mx-2"></div>

                                                <div class="menu-item-price text-nowrap">
                                                    {{ number_format($menu->price / 1000, 0) }}k
                                                </div>
                                            </div>
                                            
                                            <div class="menu-item-desc ms-5 ps-2"> {{ $menu->description ?? 'Premium blend with authentic taste.' }}
                                                {{-- OPTIONS DISPLAY --}}
                                                @if(!empty($menu->options) && is_array($menu->options))
                                                    <div class="mt-3 space-y-2">
                                                        @foreach($menu->options as $opt)
                                                            <div class="d-flex align-items-center gap-3">
                                                                <label class="text-[10px] uppercase font-bold text-gray-500 min-w-[60px] tracking-wider">{{ $opt['name'] }}</label>
                                                                <div class="w-full max-w-[180px]">
                                                                    <select name="product_options[{{ $menu->id }}][{{ $opt['name'] }}]" class="w-full text-xs font-bold text-gray-700 bg-gray-50 border border-gray-200 hover:border-watu-olive px-4 py-2 focus:outline-none focus:ring-1 focus:ring-watu-olive focus:border-watu-olive transition shadow-sm cursor-pointer" style="border-radius: 9999px !important;">
                                                                        @foreach(explode(',', $opt['values']) as $val)
                                                                            <option value="{{ trim($val) }}">{{ trim($val) }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    @endforeach
                                @else
                                    <div class="col-12 text-center py-5">
                                        <p class="text-muted">Menu belum tersedia saat ini.</p>
                                    </div>
                                @endif

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

       <div class="tab-pane fade {{ (isset($activeTab) && $activeTab == 'beans') ? 'show active' : '' }}" id="pills-beans" role="tabpanel">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    
                    <form action="{{ route('public.cafe.store') }}" method="POST">
                        @csrf
                        <div class="menu-section">
                            <div class="row">

                                @if(is_iterable($roast_beans) && count($roast_beans) > 0)
                                    @foreach($roast_beans as $categoryLabel => $items)
                                        <div class="col-md-12 text-center mt-5 mb-4">
                                            <h3 class="menu-category-title text-uppercase">{{ ucwords(str_replace('_', ' ', $categoryLabel)) }}</h3>
                                        </div>

                                        @foreach($items as $bean)
                                        <div class="col-lg-4 col-md-6 mb-5">
                                            <div class="roast-card-alder">
                                                <div class="roast-card-image">
                                                    @if($bean->image)
                                                        <img src="{{ asset('storage/' . $bean->image) }}" alt="{{ $bean->name }}">
                                                    @else
                                                        <img src="{{ asset('images/coffee-bag-mockup.png') }}" alt="{{ $bean->name }}" style="opacity:0.5"> 
                                                    @endif
                                                </div>
                                                
                                                <div class="text-center mb-3">
                                                    <div class="roast-subtitle">{{ $bean->varietal ?? 'Single Origin' }}</div>
                                                    <h3 class="roast-title">{{ $bean->name }}</h3>
                                                    <p class="roast-desc">
                                                        {{ $bean->description }}<br>
                                                        @if($bean->process)
                                                            <span class="badge bg-light text-dark border mt-2">{{ $bean->process }}</span>
                                                        @endif
                                                        <span class="badge bg-light text-dark border mt-2">Arabica</span>
                                                    </p>
                                                </div>

                                                <div class="roast-options-box">
                                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                                        <span class="roast-option-title mb-0 pt-1">Price</span>
                                                        <div class="text-end">
                                                            <span class="roast-price-tag d-block">Rp {{ number_format($bean->price, 0, ',', '.') }} / {{ $bean->unit }}</span>
                                                            <small class="text-muted text-[10px] d-block mt-0">Available Stock: {{ $bean->stock }}</small>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group mb-0">
                                                        <label class="roast-option-title text-center w-100 d-block mb-2">Quantity</label>
                                                        <div class="d-flex justify-content-center align-items-center">
                                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle" style="width:30px;height:30px;" onclick="adjustQty(this, -1)">-</button>
                                                            <div class="bean-row d-none" data-price="{{ $bean->price }}"></div>
                                                            <input type="number" name="quantities[{{ $bean->id }}]" class="qty-input form-control text-center mx-2 border-0 bg-transparent font-weight-bold" style="width:60px; font-size:1.2rem;" min="0" value="0" placeholder="0">
                                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle" style="width:30px;height:30px;" onclick="adjustQty(this, 1)">+</button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        @endforeach
                                    @endforeach
                                @else
                                    <div class="col-12 text-center py-5">
                                        <p class="text-muted">Menu Roast Bean belum tersedia.</p>
                                    </div>
                                @endif

                            </div>
                            
                            <script>
                                function adjustQty(btn, change) {
                                    // Try to find input sibling directly or in parent wrapper
                                    let wrapper = btn.closest('.d-flex');
                                    let input = wrapper.querySelector('.qty-input');
                                    
                                    let current = parseInt(input.value) || 0;
                                    let newVal = current + change;
                                    if(newVal < 0) newVal = 0;
                                    
                                    // Update value (empty if 0 for cleaner look)
                                    input.value = newVal > 0 ? newVal : '';
                                    
                                    // Trigger input event to update totals
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
                            <div class="form-group mb-3">
                                <label class="small text-uppercase font-weight-bold opacity-70">WhatsApp / Phone</label>
                                <input type="number" name="phone" class="form-control form-control-dark" placeholder="08..." required>
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