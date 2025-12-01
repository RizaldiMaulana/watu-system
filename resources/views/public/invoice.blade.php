@extends('public.layout')

@section('content')
<style>
    .invoice-container {
        padding-top: 120px;
        padding-bottom: 80px;
        background-color: var(--color-cream);
        min-height: 100vh;
    }

    .invoice-card {
        background: #fff;
        max-width: 600px;
        margin: 0 auto;
        padding: 40px;
        box-shadow: 0 15px 30px rgba(0,0,0,0.08);
        border-top: 8px solid var(--color-olive);
        position: relative;
    }

    /* CSS KHUSUS PRINT (CETAK) */
    @media print {
        /* Sembunyikan elemen navigasi website */
        .header, .footer, .btn-print, .btn-home, .page-header, body::before {
            display: none !important;
        }
        
        /* Reset Padding & Background */
        .invoice-container { 
            padding: 0 !important; 
            background: #fff !important; 
            min-height: auto !important;
        }
        
        /* Atur Invoice Card agar pas di kertas A4 */
        .invoice-card {
            box-shadow: none !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }

        /* Pastikan warna tercetak */
        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    }
    /* END PRINT CSS */

    .invoice-header {
        text-align: center;
        border-bottom: 2px dashed #eee;
        padding-bottom: 20px;
        margin-bottom: 20px;
    }

    .invoice-title {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        font-size: 28px;
        color: var(--color-dark);
        text-transform: uppercase;
        margin-top: 10px;
    }

    .table-invoice th {
        font-size: 12px; text-transform: uppercase; color: #aaa; border-bottom: 1px solid #eee;
    }

    .table-invoice td {
        padding: 15px 0; border-bottom: 1px solid #f9f9f9; color: var(--color-dark);
    }

    .total-row td {
        border-top: 2px solid var(--color-dark) !important;
        font-weight: 700; font-size: 18px; color: var(--color-olive); padding-top: 20px !important;
    }

    .status-badge {
        display: inline-block; padding: 5px 15px; border-radius: 50px; font-size: 12px; font-weight: bold; text-transform: uppercase;
    }
    .status-unpaid { background-color: #ffeeba; color: #856404; }
    .status-paid { background-color: #d4edda; color: #155724; }

</style>

<div class="invoice-container">
    <div class="container">
        
        <div class="invoice-card">
            
            <div class="invoice-header">
                <img src="{{ asset('images/LOGO Produk.png') }}" alt="Logo" style="width: 80px;">
                <h2 class="invoice-title">Watu Coffee</h2>
                <p class="text-muted small mb-0">Jl. Srikandi Raya No. 1, Lerep, Ungaran Barat</p>
                <p class="text-muted small">Kab. Semarang, Jawa Tengah</p>
            </div>

            <div class="d-flex justify-content-between mb-4 small text-muted">
                <div>
                    <strong class="text-dark">Invoice:</strong> #{{ $transaction->invoice_number }}<br>
                    <strong>Date:</strong> {{ $transaction->created_at->format('d M Y, H:i') }}<br>
                    <strong>Method:</strong> <span class="text-uppercase text-olive fw-bold">{{ $transaction->payment_method }}</span>
                </div>
                <div class="text-end">
                    <strong class="text-dark">Customer:</strong><br>
                    {{ $transaction->customer_name }}
                </div>
            </div>

            <table class="table table-borderless table-invoice w-100">
                <thead>
                    <tr>
                        <th style="width: 50%">Menu Item</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->items as $item)
                    <tr>
                        <td>
                            <span class="fw-bold">{{ $item->product->name }}</span>
                        </td>
                        <td class="text-center">x{{ $item->quantity }}</td>
                        <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    
                    <tr class="total-row">
                        <td colspan="2">TOTAL TAGIHAN</td>
                        <td class="text-end">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="text-center mt-4 p-3" style="background-color: #f8f9fa; border-radius: 10px;">
                <p class="mb-2 small text-uppercase fw-bold text-muted">Status Pembayaran</p>
                @if($transaction->payment_status == 'Paid')
                    <span class="status-badge status-paid">LUNAS</span>
                @else
                    <span class="status-badge status-unpaid">BELUM DIBAYAR</span>
                    <p class="small mt-2 text-muted mb-0">
                        Mohon selesaikan pembayaran di kasir atau transfer.<br>
                        Tunjukkan invoice ini kepada Kasir.
                    </p>
                @endif
            </div>

            <div class="text-center mt-5 d-print-none">
                <button onclick="window.print()" class="btn btn-watu-outline me-2">
                    <i class="fa fa-print"></i> Cetak / Simpan PDF
                </button>
                <a href="{{ route('home') }}" class="btn btn-watu-primary">
                    Kembali ke Menu
                </a>
            </div>

        </div>
        
        <div class="text-center mt-4 d-print-none">
            <p class="small text-muted">Terima kasih telah memesan di Watu Coffee.</p>
        </div>

    </div>
</div>
@endsection