@extends('reports.print.layout')

@section('content')
<div class="max-w-3xl mx-auto">
    
    <table class="w-full text-sm">
        <!-- OPERATING -->
        <tr>
            <td colspan="2" class="py-3 font-bold text-gray-700 bg-gray-50 uppercase tracking-wider text-xs px-2">Arus Kas dari Aktivitas Operasional</td>
        </tr>
        <tr class="border-b border-gray-100">
            <td class="py-2 pl-4">Penerimaan Kas dari Pelanggan</td>
            <td class="py-2 text-right text-green-600 font-medium">+ Rp {{ number_format($cashIn, 0, ',', '.') }}</td>
        </tr>
        <tr class="border-b border-gray-100">
            <td class="py-2 pl-4">Pengeluaran Kas untuk Operasional</td>
            <td class="py-2 text-right text-red-600 font-medium">- Rp {{ number_format($cashOut, 0, ',', '.') }}</td>
        </tr>
        <tr class="font-bold border-t border-gray-300">
            <td class="py-3 pl-4">Kas Bersih dari Operasional</td>
            <td class="py-2 text-right">Rp {{ number_format($netOperatingCash, 0, ',', '.') }}</td>
        </tr>

        <!-- SUMMARY -->
         <tr><td colspan="2" class="py-4"></td></tr> <!-- Spacer -->

        <tr class="bg-gray-100 font-bold border-t-2 border-gray-800">
            <td class="py-3 px-4">Kenaikan/Penurunan Bersih Kas</td>
            <td class="py-3 px-4 text-right">Rp {{ number_format($netChangeInCash, 0, ',', '.') }}</td>
        </tr>
        <tr class="border-b border-gray-200">
            <td class="py-3 px-4">Saldo Kas Awal Periode</td>
            <td class="py-3 px-4 text-right">Rp {{ number_format($beginningCash, 0, ',', '.') }}</td>
        </tr>
        <tr class="bg-gray-800 text-white font-black text-lg print:bg-white print:text-black print:border-y-2 print:border-gray-800">
            <td class="py-4 px-4">Saldo Kas Akhir Periode</td>
            <td class="py-4 px-4 text-right">Rp {{ number_format($endingCash, 0, ',', '.') }}</td>
        </tr>
    </table>

</div>
@endsection
