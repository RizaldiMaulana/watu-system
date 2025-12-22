@extends('reports.print.layout')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- REVENUE -->
    <h3 class="font-bold text-lg border-b-2 border-gray-800 mb-4 pb-1 text-green-700">Pendapatan (Revenue)</h3>
    <table class="w-full text-sm mb-8">
        @foreach($revenues as $account)
        <tr class="border-b border-gray-100">
            <td class="py-2 pl-4">{{ $account->code }} - {{ $account->name }}</td>
            <td class="py-2 text-right">Rp {{ number_format($account->balance, 0, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr class="font-bold bg-green-50 border-t-2 border-green-200">
            <td class="py-3">Total Pendapatan</td>
            <td class="py-3 text-right">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
        </tr>
    </table>

    <!-- EXPENSES -->
    <h3 class="font-bold text-lg border-b-2 border-gray-800 mb-4 pb-1 text-red-700">Beban & Biaya (Expense)</h3>
    <table class="w-full text-sm mb-8">
        @foreach($expenses as $account)
        <tr class="border-b border-gray-100">
            <td class="py-2 pl-4">{{ $account->code }} - {{ $account->name }}</td>
            <td class="py-2 text-right">Rp {{ number_format($account->balance, 0, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr class="font-bold bg-red-50 border-t-2 border-red-200">
            <td class="py-3">Total Beban</td>
            <td class="py-3 text-right">Rp {{ number_format($totalExpense, 0, ',', '.') }}</td>
        </tr>
    </table>

    <!-- NET INCOME -->
    <div class="bg-gray-800 text-white p-4 rounded-lg flex justify-between items-center shadow-md print:bg-white print:text-black print:border-2 print:border-gray-800">
        <div>
            <h2 class="text-xl font-bold">Laba / Rugi Bersih</h2>
            <p class="text-sm opacity-80 decoration-none">Net Income</p>
        </div>
        <div class="text-2xl font-black">
            Rp {{ number_format($netIncome, 0, ',', '.') }}
        </div>
    </div>
</div>
@endsection
