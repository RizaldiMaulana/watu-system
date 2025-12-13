@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-[#5f674d] focus:ring-[#5f674d] rounded-lg shadow-sm w-full']) }}>
