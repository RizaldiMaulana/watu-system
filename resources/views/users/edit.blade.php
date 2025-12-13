<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User') }}: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- ... (Name, Email, Role Fields) ... -->
                        <!-- NOTE: Keeping existing implementation, just targeting form tag first -->


                        <!-- Name -->
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-[#5f674d] focus:border-[#5f674d]">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-[#5f674d] focus:border-[#5f674d]">
                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Role -->
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Role / Jabatan</label>
                            <select name="role" required class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-[#5f674d] focus:border-[#5f674d]">
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin (Full Access)</option>
                                <option value="owner" {{ $user->role == 'owner' ? 'selected' : '' }}>Owner (Full Access)</option>
                                <option value="manager" {{ $user->role == 'manager' ? 'selected' : '' }}>Manager</option>
                                <option value="roaster" {{ $user->role == 'roaster' ? 'selected' : '' }}>Roaster</option>
                                <option value="barista" {{ $user->role == 'barista' ? 'selected' : '' }}>Barista</option>
                            </select>
                            @error('role') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Ext Profile -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Telepon</label>
                                <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-[#5f674d] focus:border-[#5f674d]">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">NIK / Employee ID</label>
                                <input type="text" name="employee_id" value="{{ old('employee_id', $user->employee_id) }}"
                                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-[#5f674d] focus:border-[#5f674d]">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Jabatan (Detail)</label>
                            <input type="text" name="position" value="{{ old('position', $user->position) }}" placeholder="Contoh: Senior Barista / Head of Kitchen"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-[#5f674d] focus:border-[#5f674d]">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Lengkap</label>
                            <textarea name="address" rows="2" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-[#5f674d] focus:border-[#5f674d]">{{ old('address', $user->address) }}</textarea>
                        </div>

                        <!-- Signature Field Removed (Moved to Profile) -->

                        <hr class="my-6 border-gray-200">
                        <h3 class="font-bold text-gray-600 mb-4">Ganti Password (Optional)</h3>

                        <!-- Password -->
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Password Baru</label>
                            <x-password-input name="password" placeholder="Biarkan kosong jika tidak ingin mengganti" class="w-full" />
                            @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-8">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Konfirmasi Password Baru</label>
                            <x-password-input name="password_confirmation" class="w-full" />
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-bold hover:bg-gray-200">
                                Batal
                            </a>
                            <button type="submit" class="px-6 py-2 bg-[#5f674d] text-white rounded-lg font-bold hover:bg-[#4a503b] shadow-md">
                                Update User
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
