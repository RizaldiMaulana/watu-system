@extends('public.layout')

@section('content')
<div class="container py-5 mt-20 md:mt-24">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5">
                <h6 class="text-olive text-uppercase tracking-[3px]">Almost Done</h6>
                <h1 class="display-4 font-serif font-weight-bold">Complete Your Reservation</h1>
                <p class="text-muted mt-3">
                    Hi <strong>{{ $reservation->name }}</strong>, we have saved your table for <br>
                    <strong>{{ \Carbon\Carbon::parse($reservation->booking_date)->format('d M Y') }}</strong> at 
                    <strong>{{ date('H:i', strtotime($reservation->booking_time)) }}</strong> for 
                    <strong>{{ $reservation->pax }} people</strong>.
                </p>
                <div class="alert bg-gray-50 border mt-3 d-inline-block px-4 py-2">
                    <i class="fa fa-info-circle text-olive me-2"></i> Only one step left!
                </div>
            </div>

            <form action="{{ route('public.reservation.pre-order.store', $reservation->id) }}" method="POST">
                @csrf
                
                {{-- SPECIAL NOTES --}}
                <div class="bg-white p-4 rounded-[20px] shadow-sm mb-5 border border-gray-100">
                    <h4 class="font-serif font-bold mb-3">Special Requests</h4>
                    <p class="text-muted small mb-3">Is it a birthday, anniversary, or do you have any dietary restrictions? Let us know!</p>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Example: It's my wife's birthday, please prepare a small candle..."></textarea>
                </div>

                {{-- PRE-ORDER MENU --}}
                <div class="bg-white p-4 rounded-[20px] shadow-sm mb-5 border border-gray-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="font-serif font-bold mb-1">Pre-order Menu (Optional)</h4>
                            <p class="text-muted small mb-0">Skip the wait! Order now and we'll prepare it upon your arrival.</p>
                        </div>
                        <span class="badge bg-watu-olive text-white">Recommended</span>
                    </div>

                    @foreach($products as $category => $items)
                        <h5 class="text-olive text-uppercase tracking-[1px] font-bold mt-4 mb-3 border-b pb-2">
                            {{ ucwords(str_replace('_', ' ', $category)) }}
                        </h5>
                        
                        <div class="row">
                        @foreach($items as $menu)
                        <div class="col-md-6 mb-4">
                            <div class="menu-item-row align-items-center d-flex p-2 rounded hover:bg-gray-50 transition" data-price="{{ $menu->price }}"> 
                                
                                {{-- Mobile Friendly Qty Control --}}
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

                                <div class="menu-item-dots mx-2 border-b border-dotted border-gray-300 flex-grow-1 h-[20px]"></div>

                                <div class="menu-item-price text-nowrap font-bold text-[#5f674d]">
                                    {{ number_format($menu->price / 1000, 0) }}k
                                </div>
                            </div>
                            
                            <div class="menu-item-desc ms-5 ps-2 text-xs text-gray-400 font-serif italic"> 
                                {{ $menu->description }}

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
                        </div>
                    @endforeach
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-watu-primary btn-lg px-5 py-3 shadow-lg rounded-pill w-full md:w-auto" id="btn-confirm">
                        Confirm Reservation
                    </button>
                    <p class="mt-3 text-muted small">You can pay at the cashier later.</p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function adjustQty(btn, change) {
        const wrapper = btn.closest('.d-flex');
        const input = wrapper.querySelector('.qty-input');
        let val = parseInt(input.value) || 0;
        val += change;
        if(val < 0) val = 0;
        input.value = val > 0 ? val : '';
        
        // Trigger input event for validation logic
        input.dispatchEvent(new Event('input', { bubbles: true }));
    }

    // Simple Total Calculation & Visual Feedback
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('input', function() {
            const val = parseInt(this.value) || 0;
            const btn = document.getElementById('btn-confirm');
            
            // Visual feedback
            if(val > 0) {
                this.closest('.menu-item-row').classList.add('bg-gray-50', 'rounded');
            } else {
                this.closest('.menu-item-row').classList.remove('bg-gray-50', 'rounded');
            }

            // Check if any items ordered
            let hasItems = false;
            document.querySelectorAll('.qty-input').forEach(i => {
                if((parseInt(i.value)||0) > 0) hasItems = true;
            });

            if(hasItems) {
                btn.innerHTML = "Confirm Reservation & Pre-Order";
            } else {
                btn.innerHTML = "Confirm Reservation";
            }
        });
    });
</script>
@endsection
