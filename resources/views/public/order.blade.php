@extends('public.layout')

@section('content')
<style>
    /* --- Header & Layout --- */
    .page-header {
        padding-top: 140px;
        padding-bottom: 60px;
        text-align: center;
        background-color: var(--color-cream);
    }
    
    /* --- Tabs --- */
    .nav-pills .nav-link {
        background: transparent;
        color: #aaa;
        font-family: 'Playfair Display', serif;
        font-size: 18px;
        font-weight: 700;
        border-radius: 0;
        margin: 0 15px;
        padding: 10px 5px;
        border-bottom: 3px solid transparent;
        transition: 0.3s;
    }
    .nav-pills .nav-link:hover { color: var(--color-olive); }
    .nav-pills .nav-link.active {
        background: transparent;
        color: var(--color-olive);
        border-bottom: 3px solid var(--color-olive);
    }

    /* --- Cafe Menu List Style --- */
    .menu-section {
        background: #fff;
        padding: 50px;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        margin-bottom: 40px;
        border: 1px solid #eee;
    }
    .menu-category-title {
        font-family: 'Playfair Display', serif;
        color: var(--color-olive);
        font-weight: 700;
        font-size: 28px;
        margin-bottom: 40px;
        text-transform: uppercase;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 10px;
        display: inline-block;
    }
    .menu-item-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 15px;
    }
    .menu-item-name {
        font-weight: 600;
        font-size: 16px;
        color: var(--color-dark);
        background: #fff;
        padding-right: 10px;
        z-index: 2;
    }
    .menu-item-dots {
        flex-grow: 1;
        border-bottom: 2px dotted #ddd;
        position: relative;
        bottom: 5px;
    }
    .menu-item-price {
        font-weight: 700;
        color: var(--color-olive);
        font-size: 16px;
        background: #fff;
        padding-left: 10px;
        z-index: 2;
    }
    .menu-item-desc {
        font-size: 13px;
        color: #888;
        margin-top: -10px;
        margin-bottom: 25px;
        font-style: italic;
        padding-left: 25px; /* Indent agar rapi di bawah checkbox */
    }

    /* --- Beans Table --- */
    .beans-table {
        width: 100%;
        table-layout: fixed; /* Memaksa tabel tetap di dalam container */
    }
    
    .beans-table th, .beans-table td {
        white-space: normal; /* Text boleh wrap ke bawah */
        word-wrap: break-word;
    }

    /* Kolom Harga & Aksi di tabel jangan terlalu lebar */
    .beans-table th:nth-child(4), 
    .beans-table td:nth-child(4) { width: 15%; }
    
    .beans-table th:nth-child(5), 
    .beans-table td:nth-child(5) { width: 10%; text-align: center; }

   /* Fix Layout Overflow */
    .container, .row {
        max-width: 100%;
        overflow-x: hidden; /* Mencegah scroll horizontal */
    }

    /* Style Input Jumlah yang Rapi */
    .qty-input {
        width: 50px;
        height: 35px;
        text-align: center;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-weight: bold;
        color: var(--color-olive);
        background-color: #fff;
    }
    
    .qty-input:focus {
        border-color: var(--color-olive);
        outline: none;
        background-color: #f9f9f9;
    }

    /* Hilangkan panah spinner di input number */
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }

    /* --- Reservation Box --- */
    .reservation-box {
        background-color: var(--color-olive-dark);
        padding: 40px;
        border-radius: 15px;
        color: #fff;
    }
    .form-control-dark {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        color: #fff;
        border-radius: 8px;
        padding: 12px;
    }
    .form-control-dark:focus {
        background: rgba(255,255,255,0.2);
        color: #fff;
        border-color: #fff;
        box-shadow: none;
    }

    /* --- Buttons --- */
    .btn-watu-gold {
        background-color: var(--watu-gold, #d4a056);
        color: #fff;
        border: none;
        font-weight: bold;
        padding: 12px 30px;
        border-radius: 50px;
        transition: 0.3s;
    }
    .btn-watu-gold:hover { background-color: #c29048; color: #fff; }
    
    .btn-watu-primary {
        background-color: var(--color-olive);
        color: #fff;
        border-radius: 50px;
        padding: 10px 30px;
    }

    .payment-option { transition: all 0.2s; color: #888; border-color: #ddd !important; }
    .peer:checked + .payment-option {
        border-color: var(--color-olive) !important;
        background-color: #f4f7f2 !important;
        color: var(--color-olive) !important;
        box-shadow: 0 4px 10px rgba(95, 103, 77, 0.15);
        font-weight: bold;
    }
</style>

<div class="container">
    
    @if(session('success'))
        <div class="alert alert-success text-center mt-5" style="background-color: var(--color-olive); color: white; border: none;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger text-center mt-5">
            {{ session('error') }}
        </div>
    @endif

    <div class="page-header">
        <h6 class="text-olive text-uppercase" style="letter-spacing: 3px;">Our Selections</h6>
        <h1 class="display-4 font-serif font-weight-bold">Menu & Reservation</h1>
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
                                <div class="col-md-6 mb-4"> <div class="menu-item-row align-items-center"> <div class="me-3">
                                            <input type="number" name="quantities[{{ $menu->id }}]" class="qty-input" min="0" value="" placeholder="0">
                                        </div>

                                        <div class="menu-item-name flex-grow-1">
                                            <span style="font-weight: 600; font-size: 16px;">{{ $menu->name }}</span>
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
                                    <div class="form-card"> <h5 class="text-center font-serif mb-4" style="color: var(--color-olive-dark);">Complete Your Order</h5>
                                        
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
                                                        <label class="w-100" style="cursor: pointer;">
                                                            <input type="radio" name="payment_method" value="QRIS" class="d-none peer" required checked>
                                                            <div class="payment-option p-3 text-center border rounded bg-white h-100">
                                                                <i class="fa fa-qrcode mb-2" style="font-size: 20px;"></i><br>
                                                                <span class="small fw-bold">QRIS</span>
                                                            </div>
                                                        </label>
                                                    </div>
                                                    <div class="col-4">
                                                        <label class="w-100" style="cursor: pointer;">
                                                            <input type="radio" name="payment_method" value="Cash" class="d-none peer">
                                                            <div class="payment-option p-3 text-center border rounded bg-white h-100">
                                                                <i class="fa fa-money mb-2" style="font-size: 20px;"></i><br>
                                                                <span class="small fw-bold">Tunai</span>
                                                            </div>
                                                        </label>
                                                    </div>
                                                    <div class="col-4">
                                                        <label class="w-100" style="cursor: pointer;">
                                                            <input type="radio" name="payment_method" value="Debit/Credit" class="d-none peer">
                                                            <div class="payment-option p-3 text-center border rounded bg-white h-100">
                                                                <i class="fa fa-credit-card mb-2" style="font-size: 20px;"></i><br>
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
                        <div class="bg-white p-0 rounded shadow-sm overflow-hidden">
                            <div class="table-responsive">
                                <table class="table beans-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Bean Name</th>
                                            <th>Varietal/<br>Process</th>
                                            <th>Tasting Notes</th>
                                            <th class="text-right">Price (200g)</th>
                                            <th class="text-center" style="width: 100px;">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($roast_beans as $bean)
                                            <tr>
                                                <td>
                                                    <strong style="font-size:16px; color:var(--color-dark);">{{ $bean->name }}</strong>
                                                    <br><small class="text-muted">Single Origin</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark border">Full Wash</span>
                                                    <span class="badge bg-light text-dark border">Arabica</span>
                                                </td>
                                                <td style="font-style:italic; color:#666;">
                                                    Citrus, Brown Sugar, Floral Finish
                                                </td>
                                                <td class="text-right font-weight-bold text-olive">
                                                    Rp {{ number_format($bean->price, 0, ',', '.') }}
                                                </td>
                                                <td class="text-center align-middle">
                                                    <input type="number" name="quantities[{{ $bean->id }}]" class="qty-input mx-auto d-block" min="0" value="" placeholder="0">
                                                </td>
                                            </tr>
                                            @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

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
                                    <div class="form-card"> <h5 class="text-center font-serif mb-4" style="color: var(--color-olive-dark);">Complete Your Order</h5>
                                        
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
                                                        <label class="w-100" style="cursor: pointer;">
                                                            <input type="radio" name="payment_method" value="QRIS" class="d-none peer" required checked>
                                                            <div class="payment-option p-3 text-center border rounded bg-white h-100">
                                                                <i class="fa fa-qrcode mb-2" style="font-size: 20px;"></i><br>
                                                                <span class="small fw-bold">QRIS</span>
                                                            </div>
                                                        </label>
                                                    </div>
                                                    <div class="col-4">
                                                        <label class="w-100" style="cursor: pointer;">
                                                            <input type="radio" name="payment_method" value="Cash" class="d-none peer">
                                                            <div class="payment-option p-3 text-center border rounded bg-white h-100">
                                                                <i class="fa fa-money mb-2" style="font-size: 20px;"></i><br>
                                                                <span class="small fw-bold">Tunai</span>
                                                            </div>
                                                        </label>
                                                    </div>
                                                    <div class="col-4">
                                                        <label class="w-100" style="cursor: pointer;">
                                                            <input type="radio" name="payment_method" value="Debit/Credit" class="d-none peer">
                                                            <div class="payment-option p-3 text-center border rounded bg-white h-100">
                                                                <i class="fa fa-credit-card mb-2" style="font-size: 20px;"></i><br>
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
                            <p style="opacity: 0.8;">Book a spot to enjoy the vibe.</p>
                        </div>
                        <form action="{{ route('public.reservation.store') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="small text-uppercase font-weight-bold" style="opacity:0.7">Your Name</label>
                                <input type="text" name="name" class="form-control form-control-dark" required>
                            </div>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="small text-uppercase font-weight-bold" style="opacity:0.7">Date</label>
                                    <input type="date" name="date" class="form-control form-control-dark" required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="small text-uppercase font-weight-bold" style="opacity:0.7">Time</label>
                                    <input type="time" name="time" class="form-control form-control-dark" required>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label class="small text-uppercase font-weight-bold" style="opacity:0.7">Guest Count</label>
                                <input type="number" name="pax" class="form-control form-control-dark" min="1" value="2" required>
                            </div>
                            <button type="submit" class="btn btn-watu-gold w-100 py-3 text-uppercase" style="letter-spacing: 2px;">Confirm Booking</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection