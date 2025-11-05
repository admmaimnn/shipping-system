<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold">Shipment Status</h2>
    </x-slot>

    <div class="hidden md:block rounded-lg p-2 max-w-sm mx-auto">
        <div class="flex items-center justify-center">
            {{-- Step 1 (Create) --}}
            <div class="flex flex-col items-center">
                <div class="w-8 h-8 bg-gray-300 text-gray-700 rounded-full flex items-center justify-center font-bold text-sm">1</div>
                <span class="text-xs mt-1.5 text-gray-500">Create</span>
            </div>
            
            {{-- Progress Line --}}
            <div class="flex-grow border-t-2 border-gray-300 -mt-4 mx-2"></div>
            
            {{-- Step 2 (Successful) --}}
            <div class="flex flex-col items-center">
                <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-sm">2</div>
                <span class="text-xs mt-1.5 text-blue-500">Successful</span>
            </div>
        </div>
    </div>

    <div class="sm:p-6 max-w-2xl mx-auto space-y-6">
        <div class="bg-white p-8 rounded-lg shadow-xl text-center">
            <div class="mb-6">
                {{-- Checkmark Icon --}}
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-green-100 text-green-600">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>

            <h1 class="text-3xl font-bold text-gray-800 mb-2">Shipment Created Successfully!</h1>
            <p class="text-gray-600 mb-8">
                Your shipment information has been successfully saved.
                You can review the shipment details below.
            </p>

            {{-- Display some shipment details --}}
            @if(isset($shipment))
                <div class="bg-gray-100 p-6 rounded-lg shadow-sm mb-8">
                    <div class="grid grid-cols-2 gap-y-2">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Name Vessel</p>
                        </div>
                        <div>
                            <p class="mt-1 text-sm text-gray-900">{{ $shipment['vessel_name'] }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Shipper Name</p>
                        </div>
                        <div>
                            <p class="mt-1 text-sm text-gray-900">{{ $shipment['shipper_name'] }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Bill of Lading Number</p>
                        </div>
                        <div>
                            <p class="mt-1 text-sm text-gray-900">{{ $shipment['bill_of_lading_number'] }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Containers</p>
                        </div>
                        <div>
                            <p class="mt-1 text-sm text-gray-900">{{ $shipment['number_of_containers'] }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Date</p>
                        </div>
                        <div>
                            <p class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($shipment['shipment_date'])->format('Y-m-d') }}</p>                        
                        </div>
                    </div>
                </div>
            @endif

            <div class="space-y-4">
                <a href="{{ route('shipments.index') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-md transition duration-300">
                    Return to Home
                </a>
                <a href="{{ route('shipments.create') }}" class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2.5 px-6 rounded-lg shadow-md transition duration-300">
                    Create Another Shipment
                </a>
            </div>
        </div>
    </div>
</x-app-layout>