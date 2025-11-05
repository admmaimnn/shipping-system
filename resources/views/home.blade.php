<x-page-layout>
    {{-- Hero Section --}}
    <div class="relative w-full h-[70vh] bg-cover bg-center" style="background-image: url('{{ asset('images/welcome.jpg') }}');">
        <div class="absolute inset-0 "></div>
        
        <div class="relative h-full flex flex-col items-center justify-center text-center px-4 bg-black/20">
            <div class="mb-2">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-36 h-auto">
            </div>
            <h1 class="text-white text-5xl md:text-6xl font-bold mb-6">Logistics Services</h1>
            <p class="text-white/90 text-base max-w-2xl leading-relaxed">
                Our comprehensive logistics solution is designed to soon to optimise your supply chain and
                enhance your business operations.
            </p>
        </div>
    </div>

    {{-- Stats Section --}}
    <div class="max-w-7xl mx-auto px-4 py-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            {{-- Left Content --}}
            <div>
                <h2 class="text-4xl font-bold text-gray-900 mb-2">
                    Join a <span class="text-blue-500">Growing<br>Community</span> of Business<br>and Logistics Partners
                </h2>
                
                <p class="text-gray-500 text-base mt-6 mb-8 leading-relaxed">
                    Join a dynamic community of businesses and logistics partners,<br>
                    collaborating with skilled drivers and client growth.
                </p>
                
                <a href="/customer" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-8 py-3 rounded-lg transition-colors">
                    Contact Us
                </a>
            </div>

            {{-- Right Stats Grid --}}
            <div class="grid grid-cols-2 gap-6">
                {{-- Stat 1 --}}
                <div class="bg-white rounded-2xl border-2 border-gray-200 p-8 text-center hover:shadow-lg transition-shadow">
                    <p class="text-blue-500 text-6xl font-bold mb-2">25</p>
                    <p class="text-gray-400 text-sm">Years of Industry<br>Experience</p>
                </div>

                {{-- Stat 2 --}}
                <div class="bg-white rounded-2xl border-2 border-gray-200 p-8 text-center hover:shadow-lg transition-shadow">
                    <p class="text-blue-500 text-6xl font-bold mb-2">300+</p>
                    <p class="text-gray-400 text-sm">Totals of Delivery<br>Done</p>
                </div>

                {{-- Stat 3 --}}
                <div class="bg-white rounded-2xl border-2 border-gray-200 p-8 text-center hover:shadow-lg transition-shadow">
                    <p class="text-blue-500 text-6xl font-bold mb-2">500+</p>
                    <p class="text-gray-400 text-sm">Satisfied Clients<br>Worldwide</p>
                </div>

                {{-- Stat 4 --}}
                <div class="bg-white rounded-2xl border-2 border-gray-200 p-8 text-center hover:shadow-lg transition-shadow">
                    <p class="text-blue-500 text-6xl font-bold mb-2">99%</p>
                    <p class="text-gray-400 text-sm">On-Time Delivery<br>Rate</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Solutions Section --}}
    <div class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">
                Comprehensive <span class="text-blue-500">Shipping Solutions</span> Tailored to Meet<br>Your <span class="text-blue-500">Needs</span>
            </h2>
            
            <p class="text-gray-500 text-base max-w-4xl mx-auto leading-relaxed mt-6">
                Our vision is to be a trusted logistics partner, recognized for excellence in shipping management, innovation, and customer satisfaction.<br>
                We are committed to delivering reliable and competitive pricing and ensuring long-term success for our clients worldwide.
            </p>
        </div>
    </div>
</x-page-layout>