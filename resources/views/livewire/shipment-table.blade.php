<div>
    {{-- Header Section --}}
    <div class="mb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center border-b pb-3 gap-3">
        <div class="w-full sm:w-auto hidden md:block">
            <p class="font-semibold text-lg sm:text-xl text-gray-900 leading-tight">
                {{ __('All Shipments') }}
            </p>
            <p class="text-xs sm:text-sm text-gray-500 mt-1 mb-1">
                {{ __('Always keep shipment information up to date for accurate tracking.') }}
                <span class="text-blue-500 font-semibold">"{{ $shipments->total() }}</span> shipments"
            </p>
        </div>

        <div class="flex items-center gap-3 w-full sm:max-w-md">
            {{-- Search Bar --}}
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <input wire:model.live="search" type="text"
                    placeholder="Search..."
                    class="pl-9 sm:pl-10 pr-3 py-2 border border-gray-300 text-xs sm:text-sm rounded-lg w-full
                        focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- Add New Shipment --}}
            <a href="{{ route('shipments.create') }}"
                class="p-2 border border-gray-300 rounded-lg text-gray-500 hover:text-blue-600 hover:border-blue-500 transition flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
            </a>
        </div>
    </div>

    {{-- Empty States --}}
    @if ($shipments->isEmpty() && empty($search))
        <div class="text-center py-12">
            <h3 class="mt-2 text-sm font-medium text-gray-900">No shipments</h3>
            <p class="mt-1 text-sm text-gray-500">Please create your first shipment.</p>
        </div>
    @elseif ($shipments->isEmpty() && !empty($search))
        <div class="text-center py-12">
            <h3 class="mt-2 text-sm font-medium text-gray-900">No Match Found</h3>
            <p class="mt-1 text-sm text-gray-500">No shipments match the search term "{{ $search }}".</p>
        </div>
    @else
        {{-- Main Table with Edit Modal --}}
        <div x-data="{
            showEditModal: false,
            showDeleteModal: false,
            shipment: {},
            deleteShipmentId: null,
            openEditModal(data) {
                this.shipment = data;
                this.showEditModal = true;
            },
            openDeleteModal(id) {
                this.deleteShipmentId = id;
                this.showDeleteModal = true;
            },
            confirmDelete() {
                document.getElementById('deleteForm-' + this.deleteShipmentId).submit();
            }
        }">
            {{-- Desktop Table View --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                No
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Agent
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Bill of Lading Number
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Shipper
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Invoice Number
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Date
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($shipments as $index => $shipment)
                            <tr class="hover:bg-blue-50 transition-colors duration-150 cursor-pointer"
                                x-on:click="openEditModal({
                                    id: {{ $shipment->id }},
                                    vessel_name: '{{ $shipment->vessel_name }}',
                                    bill_of_lading_number: '{{ $shipment->bill_of_lading_number }}',
                                    shipper_name: '{{ $shipment->shipper_name }}',
                                    port_origin: '{{ $shipment->port_origin }}',
                                    port_destination: '{{ $shipment->port_destination }}',
                                    shipment_date: '{{ $shipment->shipment_date }}',
                                    invoice_number: '{{ $shipment->invoice_number ?? '' }}',
                                    cost: '{{ $shipment->cost ?? 0 }}',
                                    sales: '{{ $shipment->sales ?? 0 }}',
                                    number_of_containers: '{{ $shipment->number_of_containers }}',
                                    attachment: '{{ $shipment->attachment }}'
                                })"
                            >
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center justify-center text-gray-700 rounded-full text-sm font-semibold">
                                        {{ $shipments->firstItem() + $index }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3 justify-center">
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $shipment->vessel_name }}</div>
                                            <div class="text-[11px] text-gray-500">Vessel</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-3 py-1 text-sm font-medium">
                                        {{ $shipment->bill_of_lading_number }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-center text-sm font-medium text-gray-900 max-w-xs truncate" title="{{ $shipment->shipper_name }}">
                                        {{ $shipment->shipper_name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-center text-sm font-medium text-gray-900 max-w-xs truncate" title="{{ $shipment->invoice_number ?? '' }}">
                                        {{ $shipment->invoice_number ?? '' }}
                                    </div>
                                </td>                               
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3 justify-center">
                                        <div>
                                            <div class="text-sm text-gray-900 font-medium">
                                                {{ \Carbon\Carbon::parse($shipment->shipment_date)->format('d M Y') }}
                                            </div>
                                            <div class="text-[11px] text-gray-500">
                                                {{ \Carbon\Carbon::parse($shipment->shipment_date)->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile Card View --}}
            <div class="md:hidden space-y-3">
                @foreach ($shipments as $index => $shipment)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow cursor-pointer"
                        x-on:click="openEditModal({
                            id: {{ $shipment->id }},
                            vessel_name: '{{ $shipment->vessel_name }}',
                            bill_of_lading_number: '{{ $shipment->bill_of_lading_number }}',
                            shipper_name: '{{ $shipment->shipper_name }}',
                            port_origin: '{{ $shipment->port_origin }}',
                            port_destination: '{{ $shipment->port_destination }}',
                            shipment_date: '{{ $shipment->shipment_date }}',
                            invoice_number: '{{ $shipment->invoice_number ?? '' }}',
                            cost: '{{ $shipment->cost ?? 0 }}',
                            sales: '{{ $shipment->sales ?? 0 }}',
                            number_of_containers: '{{ $shipment->number_of_containers }}',
                            attachment: '{{ $shipment->attachment }}'
                        })"
                    >
                        {{-- Header --}}
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <div class="text-sm font-bold text-gray-900">{{ $shipment->vessel_name }}</div>
                                <div class="text-xs text-gray-500">Vessel</div>
                            </div>
                            <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-2 py-1 rounded">
                                #{{ $shipments->firstItem() + $index }}
                            </span>
                        </div>

                        {{-- Details --}}
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between">
                                <span class="text-gray-500">B/L Number:</span>
                                <span class="font-medium text-gray-900">{{ $shipment->bill_of_lading_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Shipper:</span>
                                <span class="font-medium text-gray-900 truncate ml-2" title="{{ $shipment->shipper_name }}">
                                    {{ $shipment->shipper_name }}
                                </span>
                            </div>
                            @if($shipment->invoice_number)
                            <div class="flex justify-between">
                                <span class="text-gray-500">Invoice:</span>
                                <span class="font-medium text-gray-900 truncate ml-2" title="{{ $shipment->invoice_number }}">
                                    {{ $shipment->invoice_number }}
                                </span>
                            </div>
                            @endif
                            <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                                <span class="text-gray-500">Date:</span>
                                <div class="text-right">
                                    <div class="font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($shipment->shipment_date)->format('d M Y') }}
                                    </div>
                                    <div class="text-[10px] text-gray-500">
                                        {{ \Carbon\Carbon::parse($shipment->shipment_date)->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $shipments->links() }}
            </div>

            

            {{-- Edit Modal --}}
           <div x-show="showEditModal" x-cloak
                class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4"
                x-on:click.self="showEditModal = false"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
            >
                <div class="bg-white rounded-3xl shadow-lg w-full max-w-2xl relative overflow-hidden">
                    <form :action="`/shipments/${shipment.id}`" id="editShipmentForm" method="POST" class="h-full" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">

                        <div class="flex justify-between items-start p-8 pb-4">
                            <div>
                                <h2 class="text-lg font-black text-gray-900">Edit Shipment</h2>
                                <p class="text-xs text-gray-500">Update shipment details such as vessel, ID, and status.</p>
                            </div>
                            <button type="button" x-on:click="showEditModal = false" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        {{-- Scrollable Content --}}
                        <div class="max-h-[65vh] overflow-y-auto px-8">
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-sm font-bold text-gray-900 mb-3 flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Basic Information
                                    </h3>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-semibold mb-1 text-gray-700">Vessel Name</label>
                                            <input type="text" name="vessel_name" x-model="shipment.vessel_name"
                                                class="w-full text-xs py-2 border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900" required>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold mb-1 text-gray-700">Shipper Name</label>
                                            <input type="text" name="shipper_name" x-model="shipment.shipper_name"
                                                class="w-full text-xs py-2 border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900" required>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold mb-1 text-gray-700">Port of Origin</label>
                                            <input type="text" name="port_origin" x-model="shipment.port_origin"
                                                class="w-full text-xs py-2 border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900" required>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold mb-1 text-gray-700">Port of Destination</label>
                                            <input type="text" name="port_destination" x-model="shipment.port_destination"
                                                class="w-full text-xs py-2 border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900" required>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold mb-1 text-gray-700">Containers</label>
                                            <input type="number" name="number_of_containers" x-model="shipment.number_of_containers"
                                                class="w-full text-xs py-2 border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900" required>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold mb-1 text-gray-700">Shipment Date</label>
                                            <input type="date" name="shipment_date" x-model="shipment.shipment_date"
                                                class="w-full text-xs py-2 border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900" required>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-sm font-bold text-gray-900 mb-3 flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Bill & Documentation
                                    </h3>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-semibold mb-1 text-gray-700">Bill of Lading Number</label>
                                            <input type="text" name="bill_of_lading_number" x-model="shipment.bill_of_lading_number"
                                                class="w-full text-xs py-2 border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold mb-1 text-gray-700">Invoice Number</label>
                                            <input type="text" name="invoice_number" x-model="shipment.invoice_number"
                                                class="w-full text-xs py-2 border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900" required>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-sm font-bold text-gray-900 mb-3 flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Cost & Financial
                                    </h3>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-semibold mb-1 text-gray-700">Cost (RM)</label>
                                            <input type="number" name="cost" step="0.01" x-model="shipment.cost"
                                                class="w-full text-xs py-2 border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold mb-1 text-gray-700">Sales (RM)</label>
                                            <input type="number" name="sales" step="0.01" x-model="shipment.sales"
                                                class="w-full text-xs py-2 border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900">
                                        </div>
                                    </div>
                                </div>

                                {{-- Attachments Section --}}
                                <div x-data="{ 
                                    fileName: '', 
                                    clearFile() {
                                        this.fileName = ''; 
                                        this.$refs.fileInput.value = null; 
                                    } 
                                }" 
                                class="bg-gray-50 border border-gray-200 rounded-lg w-full p-4">
                                    <div class="flex items-baseline mb-4">
                                        <span class="text-xs font-semibold text-gray-900 mr-3">Attachments</span>
                                        <input type="file" name="attachment" x-ref="fileInput" id="attachmentInput" class="sr-only"
                                            x-on:change="fileName = $event.target.files.length ? $event.target.files[0].name : ''">
                                        <label for="attachmentInput"
                                            class="text-xs border-l pl-3 text-blue-600 font-medium cursor-pointer hover:text-blue-700">
                                            Upload file
                                        </label>
                                    </div>

                                    <template x-if="shipment.attachment && !fileName">
                                        <div class="flex items-center justify-between p-2 bg-white rounded-lg border border-gray-200">
                                            <div class="flex items-center space-x-2">
                                                <svg class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M21.44 11.05l-9.19 9.19a5 5 0 01-7.07-7.07l9.19-9.19a3 3 0 014.24 4.24L9.88 17.44a1 1 0 01-1.41-1.41l8.48-8.48" />
                                                </svg>
                                                <a :href="`/storage/${shipment.attachment}`" target="_blank"
                                                    class="text-gray-700 text-xs font-medium hover:underline"
                                                    x-text="shipment.attachment.split('/').pop()">
                                                </a>
                                            </div>
                                            <a :href="`/storage/${shipment.attachment}`" target="_blank" class="text-gray-400 hover:text-blue-500 p-1 rounded-full">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </template>

                                    <template x-if="fileName">
                                        <div class="flex items-center justify-between p-2 bg-gray-100 rounded-lg border border-gray-200">
                                            <div class="flex items-center space-x-2">
                                                <svg class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M21.44 11.05l-9.19 9.19a5 5 0 01-7.07-7.07l9.19-9.19a3 3 0 014.24 4.24L9.88 17.44a1 1 0 01-1.41-1.41l8.48-8.48" />
                                                </svg>
                                                <span class="text-gray-700 text-xs" x-text="fileName"></span>
                                            </div>
                                            <button type="button" x-on:click="clearFile()" class="text-gray-400 hover:text-red-500 p-1 rounded-full">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="flex justify-between items-end p-8 pt-4">
                        {{-- Delete Button - Opens confirmation modal --}}
                        <button type="button" 
                                x-on:click="openDeleteModal(shipment.id)"
                                class="text-xs font-bold px-3 py-2 border border-gray-300 text-red-600 rounded-md hover:bg-gray-50">
                            Delete
                        </button>

                        {{-- Cancel / Save --}}
                        <div class="flex space-x-3">
                            <button type="button" x-on:click="showEditModal = false"
                                class="text-xs font-bold px-3 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" form="editShipmentForm"
                                class="bg-blue-600 text-xs text-white px-3 py-2 font-bold rounded-md hover:bg-blue-700">
                                Save
                            </button>
                        </div>
                    </div>

                    {{-- Hidden Delete Forms for each shipment --}}
                    @foreach ($shipments as $shipment)
                        <form id="deleteForm-{{ $shipment->id }}" action="/shipments/{{ $shipment->id }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endforeach
                </div>
            </div>

            

            {{-- Delete Confirmation Modal --}}
            <div x-show="showDeleteModal" x-cloak
                class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
                x-on:click.self="showDeleteModal = false"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
            >
                <div x-show="showDeleteModal"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="bg-white rounded-2xl shadow-lg p-6 w-full max-w-sm text-center">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Delete this shipment?</h3>
                    <p class="text-xs text-gray-500 mb-6">This action cannot be undone.</p>
                    <div class="flex justify-center space-x-4">
                        <button x-on:click="showDeleteModal = false" 
                                class="px-3 py-2 text-xs font-bold text-gray-700 border border-gray-300 rounded-md hover:bg-gray-100">
                            Cancel
                        </button>
                        <button x-on:click="confirmDelete()" 
                                class="bg-blue-600 text-xs text-white px-3 py-2 font-bold rounded-md hover:bg-blue-700">
                            Yes, Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <style>
        [x-cloak] { display: none !important; }
    </style>
</div>