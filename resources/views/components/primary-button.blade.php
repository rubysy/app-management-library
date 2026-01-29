<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-[#FF3B30] border border-black rounded-none font-bold text-sm text-black uppercase tracking-widest hover:bg-black hover:text-white focus:bg-black focus:text-white active:bg-black active:text-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
