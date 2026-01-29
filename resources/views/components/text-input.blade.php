@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-black bg-white text-gray-900 focus:border-[#FF3B30] focus:ring-[#FF3B30] rounded-none shadow-sm']) }}>
