@extends('reports.print.layout')

@section('content')
<div class="grid grid-cols-2 gap-8">
    <!-- ASSETS -->
    <div>
        <h3 class="font-bold text-lg border-b-2 border-gray-800 mb-4 pb-1">Asset (Aktiva)</h3>
        <table class="w-full text-sm">
            @foreach($assets as $account)
            <tr class="border-b border-gray-100">
                <td class="py-2">{{ $account->code }} - {{ $account->name }}</td>
                <td class="py-2 text-right">Rp {{ number_format($account->balance, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="font-bold bg-gray-50 border-t-2 border-gray-300">
                <td class="py-3">Total Asset</td>
                <td class="py-3 text-right">Rp {{ number_format($assets->sum('balance'), 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <!-- LIABILITIES & EQUITY -->
    <div>
        <h3 class="font-bold text-lg border-b-2 border-gray-800 mb-4 pb-1">Kewajiban & Modal</h3>
        
        <!-- Liabilities -->
        <h4 class="font-bold text-gray-600 mb-2 uppercase text-xs">Kewajiban (Liabilitas)</h4>
        <table class="w-full text-sm mb-6">
            @foreach($liabilities as $account)
            <tr class="border-b border-gray-100">
                <td class="py-2">{{ $account->code }} - {{ $account->name }}</td>
                <td class="py-2 text-right">Rp {{ number_format($account->balance, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="font-bold bg-gray-50">
                <td class="py-2">Total Kewajiban</td>
                <td class="py-2 text-right">Rp {{ number_format($liabilities->sum('balance'), 0, ',', '.') }}</td>
            </tr>
        </table>

        <!-- Equity -->
        <h4 class="font-bold text-gray-600 mb-2 uppercase text-xs">Modal (Ekuitas)</h4>
        <table class="w-full text-sm">
            @foreach($equity as $account)
            <tr class="border-b border-gray-100">
                <td class="py-2">{{ $account->code }} - {{ $account->name }}</td>
                <td class="py-2 text-right">Rp {{ number_format($account->balance, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            
            <!-- Retained Earnings -->
            <tr class="border-b border-gray-100">
                <td class="py-2 italic text-gray-600">Laba/Rugi Periode Berjalan</td>
                <td class="py-2 text-right font-medium {{ $netIncome < 0 ? 'text-red-600' : '' }}">
                    Rp {{ number_format($netIncome, 0, ',', '.') }}
                </td>
            </tr>

            <tr class="font-bold bg-gray-50 border-t-2 border-gray-300">
                <td class="py-3">Total Modal</td>
                <td class="py-3 text-right">
                    Rp {{ number_format($equity->sum('balance') + $netIncome, 0, ',', '.') }}
                </td>
            </tr>
        </table>

        <div class="mt-4 pt-2 border-t-2 border-gray-800 flex justify-between font-bold text-lg">
            <span>Total Pasiva</span>
            <span>Rp {{ number_format($liabilities->sum('balance') + $equity->sum('balance') + $netIncome, 0, ',', '.') }}</span>
        </div>
    </div>
</div>
@endsection
