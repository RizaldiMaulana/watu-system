<!DOCTYPE html>
<html>
<head>
    <title>Struk #{{ $transaction->invoice_number }}</title>
    <style>
        body { font-family: 'Courier New', monospace; font-size: 12px; width: 58mm; margin: 0 auto; padding: 5px; color: #000; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .border-bottom { border-bottom: 1px dashed #000; margin: 5px 0; }
        .flex { display: flex; justify-content: space-between; }
        .bold { font-weight: bold; }
        @media print {
            @page { margin: 0; }
            body { margin: 0; }
        }
    </style>
</head>
<body onload="window.print()">
    
    <div class="text-center">
        <img src="{{ asset('images/LOGO Produk.png') }}" style="max-width: 20mm; margin-bottom: 5px;">
        <div class="bold" style="font-size: 14px;">WATU COFFEE</div>
        Jl. Srikandi Raya No. 1, Lerep<br>
        Kab. Semarang
    </div>

    <div class="border-bottom"></div>

    <div>
        Inv: {{ $transaction->invoice_number }}<br>
        Tgl: {{ $transaction->created_at->format('d/m/y H:i') }}<br>
        Cust: {{ $transaction->customer_name }}
    </div>

    <div class="border-bottom"></div>

    @foreach($transaction->items as $item)
    <div style="margin-bottom: 3px;">
        {{ $item->product->name }}
        <div class="flex">
            <span>{{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}</span>
            <span>{{ number_format($item->subtotal, 0, ',', '.') }}</span>
        </div>
    </div>
    @endforeach

    <div class="border-bottom"></div>

    <div class="flex" style="font-size: 11px;">
        <span>Subtotal</span>
        <span>{{ number_format($transaction->subtotal_amount, 0, ',', '.') }}</span>
    </div>

    @if($transaction->discount_amount > 0)
    <div class="flex" style="font-size: 11px;">
        <span>Disc {{ $transaction->discount_reason ? "({$transaction->discount_reason})" : '' }}</span>
        <span>-{{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
    </div>
    @endif

    @if($transaction->service_charge_amount > 0)
    <div class="flex" style="font-size: 11px;">
        <span>Service Charge</span>
        <span>+{{ number_format($transaction->service_charge_amount, 0, ',', '.') }}</span>
    </div>
    @endif

    @if($transaction->tax_amount > 0)
    <div class="flex" style="font-size: 11px;">
        <span>PB1 ({{ $transaction->tax_rate + 0 }}%)</span>
        <span>+{{ number_format($transaction->tax_amount, 0, ',', '.') }}</span>
    </div>
    @endif

    <div class="border-bottom"></div>

    <div class="flex bold" style="font-size: 13px;">
        <span>TOTAL</span>
        <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
    </div>

    <div class="border-bottom"></div>

    <div class="flex">
        <span>Bayar</span>
        <span>{{ $transaction->payment_method }}</span>
    </div>
    
    <div class="flex">
        <span>Status</span>
        <span>{{ $transaction->payment_status }}</span>
    </div>
    <div class="text-center" style="margin-top: 10px;">
        Terima Kasih!<br>
        #WatuCoffee
    </div>

</body>
</html>