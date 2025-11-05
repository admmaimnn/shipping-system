<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold">Confirm Shipment Details</h2>
        <p class="text-sm text-gray-500">Edit fields below then save.</p>
    </x-slot>

    <div class="p-6 max-w-3xl mx-auto bg-white rounded shadow">
        <form action="{{ route('shipments.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm">Vessel Name</label>
                <input type="text" name="vessel_name" value="{{ old('vessel_name', $parsedData['vessel_name'] ?? '') }}" class="w-full border p-2 rounded">
            </div>

            <div>
                <label class="block text-sm">Booking ID</label>
                <input type="text" name="booking_id" value="{{ old('booking_id', $parsedData['booking_id'] ?? '') }}" class="w-full border p-2 rounded">
            </div>

            <div>
                <label class="block text-sm">Shipment Date</label>
                <input type="date" name="shipment_date" value="{{ old('shipment_date', $parsedData['shipment_date'] ?? '') }}" class="w-full border p-2 rounded">
            </div>

            <div>
                <label class="block text-sm">Shipper Name</label>
                <input type="text" name="shipper_name" value="{{ old('shipper_name', $parsedData['shipper_name'] ?? '') }}" class="w-full border p-2 rounded">
            </div>

            <div>
                <label class="block text-sm">Bill of Lading Number</label>
                <input type="text" name="bill_of_lading_number" value="{{ old('bill_of_lading_number', $parsedData['bill_of_lading_number'] ?? '') }}" class="w-full border p-2 rounded">
            </div>

            <div>
                <label class="block text-sm">Number of Containers</label>
                <input type="number" name="number_of_containers" value="{{ old('number_of_containers', $parsedData['number_of_containers'] ?? '') }}" class="w-full border p-2 rounded">
            </div>

            <div>
                <label class="block text-sm">Raw OCR Text (debug)</label>
                <textarea class="w-full border p-2 rounded" rows="6" readonly>{{ $parsedData['raw_text'] ?? '' }}</textarea>
                <input type="hidden" name="raw_text" value="{{ $parsedData['raw_text'] ?? '' }}">
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('shipments.create') }}" class="px-3 py-2 bg-gray-100 rounded">Back</a>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Save Shipment</button>
            </div>
        </form>
    </div>
</x-app-layout>