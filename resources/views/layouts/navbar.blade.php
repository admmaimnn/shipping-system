<nav 
    x-data="{ open: false, lastScroll: 0, hidden: false }" 
    x-init="
        window.addEventListener('scroll', () => {
            let current = window.scrollY;
            hidden = current > lastScroll && current > 80; // sembunyi bila scroll bawah lebih 80px
            lastScroll = current;
        });
    "
    x-bind:class="hidden ? '-translate-y-full opacity-0' : 'translate-y-0 opacity-100'"
    class="font-poppins bg-[#0066FF] text-white backdrop-blur-sm fixed top-0 left-0 w-full z-50 transition-all duration-500 ease-in-out"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            
            <!-- Logo -->
            <div class="flex-shrink-0">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-36 h-auto">
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex space-x-8 text-[13px] font-medium">
                <x-nav-page-link href="/" :active="request()->is('/')">Home</x-nav-page-link>
                <x-nav-page-link href="/about" :active="request()->is('about')">About</x-nav-page-link>
                <x-nav-page-link href="/services" :active="request()->is('services')">Services</x-nav-page-link>
                <x-nav-page-link href="/customer" :active="request()->is('customer')">Customer Care</x-nav-page-link>
            </div>

            <!-- Account (Desktop) -->
            <div class="hidden md:flex items-center space-x-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/shipments') }}" 
                            class="px-5 py-1.5 border rounded-sm text-sm leading-normal hover:bg-white/20 transition">
                            Main App
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                            class="px-5 py-1.5 border rounded-sm text-sm leading-normal hover:bg-white/20 transition">
                            Log in
                        </a>
                        {{--@if (Route::has('register'))
                            <a href="{{ route('register') }}" 
                                class="px-5 py-1.5 border rounded-sm text-sm leading-normal hover:bg-white/20 transition">
                                Register
                            </a>
                        @endif--}}
                    @endauth
                @endif
            </div>

            <!-- Mobile Hamburger -->
            <div class="md:hidden flex items-center">
                <button @click="open = !open" class="focus:outline-none transition-transform duration-300"
                    :class="{ 'rotate-90': open }">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-300" 
        x-transition:enter-start="opacity-0 transform -translate-y-4" 
        x-transition:enter-end="opacity-100 transform translate-y-0" 
        x-transition:leave="transition ease-in duration-200" 
        x-transition:leave-start="opacity-100 transform translate-y-0" 
        x-transition:leave-end="opacity-0 transform -translate-y-4" 
        class="md:hidden px-4 pb-3 space-y-2 backdrop-blur-sm bg-[#0066FF]/90"
    >
        <a href="{{ url('/') }}" 
            class="block hover:underline underline-offset-4 {{ request()->is('/') ? 'underline underline-offset-4 decoration-1' : '' }}">Home</a>
        <a href="{{ url('/about') }}" 
            class="block hover:underline underline-offset-4 {{ request()->is('about') ? 'underline underline-offset-4 decoration-1' : '' }}">About</a>
        <a href="{{ url('/services') }}" 
            class="block hover:underline underline-offset-4 {{ request()->is('services') ? 'underline underline-offset-4 decoration-1' : '' }}">Services</a>
        <a href="{{ url('/customer') }}" 
            class="block hover:underline underline-offset-4 {{ request()->is('customer-care') ? 'underline underline-offset-4 decoration-1' : '' }}">Customer Care</a>

        <!-- Account (Mobile) -->
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" class="block hover:underline">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="block hover:underline">Log in</a>
            @endauth
        @endif
    </div>
</nav>
