<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Reservasi') }}
            </h2>
            <a href="{{ route('reservations.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg text-sm hover:bg-gray-600">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('reservations.update', $reservation->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Nama -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Pelanggan</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $reservation->name) }}" class="w-full rounded-lg border-gray-300 focus:ring-[#5f674d] focus:border-[#5f674d]" required>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">No. WhatsApp</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $reservation->phone) }}" class="w-full rounded-lg border-gray-300 focus:ring-[#5f674d] focus:border-[#5f674d]" required>
                            </div>

                            <!-- Date -->
                            <div>
                                <label for="booking_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                                <input type="date" name="booking_date" id="booking_date" value="{{ old('booking_date', $reservation->booking_date->format('Y-m-d')) }}" class="w-full rounded-lg border-gray-300 focus:ring-[#5f674d] focus:border-[#5f674d]" required>
                            </div>

                            <!-- Time -->
                            <div>
                                <label for="booking_time" class="block text-sm font-medium text-gray-700 mb-2">Jam</label>
                                <input type="time" name="booking_time" id="booking_time" value="{{ old('booking_time', date('H:i', strtotime($reservation->booking_time))) }}" class="w-full rounded-lg border-gray-300 focus:ring-[#5f674d] focus:border-[#5f674d]" required>
                            </div>

                            <!-- Pax -->
                            <div>
                                <label for="pax" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Orang (Pax)</label>
                                <input type="number" name="pax" id="pax" value="{{ old('pax', $reservation->pax) }}" min="1" class="w-full rounded-lg border-gray-300 focus:ring-[#5f674d] focus:border-[#5f674d]" required>
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select name="status" id="status" class="w-full rounded-lg border-gray-300 focus:ring-[#5f674d] focus:border-[#5f674d]">
                                    <option value="Pending" {{ $reservation->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Confirmed" {{ $reservation->status == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="Completed" {{ $reservation->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="Cancelled" {{ $reservation->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-6">
                            <label for="special_note" class="block text-sm font-medium text-gray-700 mb-2">Catatan Khusus</label>
                            <textarea name="special_note" id="special_note" rows="3" class="w-full rounded-lg border-gray-300 focus:ring-[#5f674d] focus:border-[#5f674d]">{{ old('special_note', $reservation->special_note) }}</textarea>
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="submit" class="px-6 py-2.5 bg-[#5f674d] text-white font-bold rounded-lg hover:bg-[#4b523d] transition shadow-lg">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
