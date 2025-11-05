<x-app-layout>
    <div class="p-2 hidden md:block">
        <div class="rounded-lg max-w-sm mx-auto space-y-6">
            <div class="flex items-center justify-center">
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </div>
                    <span class="text-xs mt-1.5 font-medium text-blue-600">Create</span>
                </div>

                <div class="flex-grow border-t-2 border-gray-300 -mt-4 mx-2"></div>

                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center font-bold text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>
                    <span class="text-xs mt-1.5 text-gray-500">Successful</span>
                </div>
            </div>
        </div>
    </div>

    <div class="sm:p-3">
        <div class="p-7 bg-white shadow-md overflow-hidden rounded-lg max-w-3xl mx-auto relative">

            {{-- OCR PIN BUTTON --}}
            <button type="button" 
                    x-data 
                    @click="$dispatch('open-ocr-modal')"
                    class="absolute top-4 right-4 z-10 w-10 h-10 bg-blue-600 hover:bg-blue-700 text-white rounded-full flex items-center justify-center shadow-lg transition-all duration-200 hover:scale-110"
                    title="Auto-Fill with Document (OCR)">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                </svg>
            </button>

            <p class="text-xl font-bold text-gray-900 leading-tight">
                {{ __('Fill Up') }}
            </p>
            <p class="text-sm text-gray-500 mt-1 mb-6">
                {{ __('Enter shipment details from a document.') }}
            </p>

            {{-- MAIN FORM WITH ALPINE VALIDATION --}}
            <form id="shipment-form" action="{{ route('shipments.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8"
                x-data="{
                    // State to hold form data and errors
                    formData: {
                        vessel_name: '{{ old('vessel_name', $parsedData['vessel_name'] ?? '') }}',
                        shipper_name: '{{ old('shipper_name', $parsedData['shipper_name'] ?? '') }}',
                        port_origin: '{{ old('port_origin', $parsedData['port_origin'] ?? '') }}',
                        port_destination: '{{ old('port_destination', $parsedData['port_destination'] ?? '') }}',
                        number_of_containers: '{{ old('number_of_containers', $parsedData['number_of_containers'] ?? '') }}',
                        shipment_date: '{{ old('shipment_date', $parsedData['shipment_date'] ?? '') }}',
                        bill_of_lading_number: '{{ old('bill_of_lading_number', $parsedData['bill_of_lading_number'] ?? '') }}',
                        invoice_number: '{{ old('invoice_number', $parsedData['invoice_number'] ?? '') }}',
                        cost: '{{ old('cost', $parsedData['cost'] ?? '') }}',
                        sales: '{{ old('sales', $parsedData['sales'] ?? '') }}',
                    },
                    errors: {},
                    
                    // Function to validate a single field for emptiness
                    validateField(field) {
                        if (!this.formData[field] || String(this.formData[field]).trim() === '') {
                            this.errors[field] = 'This field is required.';
                            return false;
                        }
                        this.errors[field] = null;
                        return true;
                    },

                    // Function to validate the entire form on submission
                    validateForm(event) {
                        const fields = Object.keys(this.formData);
                        let isValid = true;
                        
                        // Run validation on all fields
                        fields.forEach(field => {
                            if (!this.validateField(field)) {
                                isValid = false;
                            }
                        });

                        if (!isValid) {
                            event.preventDefault(); // Stop form submission
                            // Optional: Scroll to the first error or show a notification
                            return false; 
                        }
                        return true;
                    }
                }"
                @submit.prevent="validateForm($event) && $el.submit()">
                
                @csrf

                {{-- SECTION 1: Basic Information --}}
                <div class="space-y-4">
                    <div class="flex items-center space-x-2 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h2 class="text-base font-semibold text-gray-900">Basic Information</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Vessel Name --}}
                        <div>
                            <label for="vessel_name" class="block text-sm font-semibold text-gray-900 mb-1.5">Vessel Name</label>
                            <input name="vessel_name" id="vessel_name" placeholder="" 
                                x-model="formData.vessel_name" 
                                @blur="validateField('vessel_name')"
                                :class="{ 'border-red-500': errors.vessel_name, 'focus:border-red-500': errors.vessel_name }"
                                class="block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p x-show="errors.vessel_name" class="text-xs text-red-500 mt-1" x-text="errors.vessel_name"></p>
                        </div>
                        {{-- Shipper Name --}}
                        <div>
                            <label for="shipper_name" class="block text-sm font-semibold text-gray-900 mb-1.5">Shipper Name</label>
                            <input name="shipper_name" id="shipper_name" placeholder="" 
                                x-model="formData.shipper_name" 
                                @blur="validateField('shipper_name')"
                                :class="{ 'border-red-500': errors.shipper_name, 'focus:border-red-500': errors.shipper_name }"
                                class="block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p x-show="errors.shipper_name" class="text-xs text-red-500 mt-1" x-text="errors.shipper_name"></p>
                        </div>
                        
                        {{-- Port of Origin --}}
                        <div>
                            <label for="port_origin" class="block text-sm font-semibold text-gray-900 mb-1.5">Port of Origin</label>
                            <input name="port_origin" id="port_origin" placeholder="" 
                                x-model="formData.port_origin" 
                                @blur="validateField('port_origin')"
                                :class="{ 'border-red-500': errors.port_origin, 'focus:border-red-500': errors.port_origin }"
                                class="block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p x-show="errors.port_origin" class="text-xs text-red-500 mt-1" x-text="errors.port_origin"></p>
                        </div>
                        {{-- Port of Destination --}}
                        <div>
                            <label for="port_destination" class="block text-sm font-semibold text-gray-900 mb-1.5">Port of Destination</label>
                            <input name="port_destination" id="port_destination" placeholder="" 
                                x-model="formData.port_destination" 
                                @blur="validateField('port_destination')"
                                :class="{ 'border-red-500': errors.port_destination, 'focus:border-red-500': errors.port_destination }"
                                class="block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p x-show="errors.port_destination" class="text-xs text-red-500 mt-1" x-text="errors.port_destination"></p>
                        </div>
                        
                        {{-- Number of Containers --}}
                        <div>
                            <label for="number_of_containers" class="block text-sm font-semibold text-gray-900 mb-1.5">Number of Containers</label>
                            <input name="number_of_containers" id="number_of_containers" type="number" placeholder="" 
                                x-model="formData.number_of_containers" 
                                @blur="validateField('number_of_containers')"
                                :class="{ 'border-red-500': errors.number_of_containers, 'focus:border-red-500': errors.number_of_containers }"
                                class="block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p x-show="errors.number_of_containers" class="text-xs text-red-500 mt-1" x-text="errors.number_of_containers"></p>
                        </div>
                        {{-- Shipment Date --}}
                        <div>
                            <label for="shipment_date" class="block text-sm font-semibold text-gray-900 mb-1.5">Shipment Date</label>
                            <input name="shipment_date" id="shipment_date" type="date" 
                                x-model="formData.shipment_date" 
                                @blur="validateField('shipment_date')"
                                :class="{ 'border-red-500': errors.shipment_date, 'focus:border-red-500': errors.shipment_date }"
                                class="block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p x-show="errors.shipment_date" class="text-xs text-red-500 mt-1" x-text="errors.shipment_date"></p>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: Bill & Documentation --}}
                <div class="space-y-4 pt-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h2 class="text-base font-semibold text-gray-900">Bill & Documentation</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Bill of Lading Number --}}
                        <div>
                            <label for="bill_of_lading_number" class="block text-sm font-semibold text-gray-900 mb-1.5">Bill of Lading Number</label>
                            <input name="bill_of_lading_number" id="bill_of_lading_number" placeholder="" 
                                x-model="formData.bill_of_lading_number" 
                                @blur="validateField('bill_of_lading_number')"
                                :class="{ 'border-red-500': errors.bill_of_lading_number, 'focus:border-red-500': errors.bill_of_lading_number }"
                                class="block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p x-show="errors.bill_of_lading_number" class="text-xs text-red-500 mt-1" x-text="errors.bill_of_lading_number"></p>
                        </div>
                        {{-- Invoice Number --}}
                        <div>
                            <label for="invoice_number" class="block text-sm font-semibold text-gray-900 mb-1.5">Invoice Number</label>
                            <input name="invoice_number" id="invoice_number" type="text" placeholder="" 
                                x-model="formData.invoice_number" 
                                @blur="validateField('invoice_number')"
                                :class="{ 'border-red-500': errors.invoice_number, 'focus:border-red-500': errors.invoice_number }"
                                class="block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p x-show="errors.invoice_number" class="text-xs text-red-500 mt-1" x-text="errors.invoice_number"></p>
                        </div>
                    </div>
                </div>

                {{-- ATTACHMENTS SECTION (No change needed here for basic validation) --}}
                <div class="">
                    <div x-data="{ 
                                fileName: '', 
                                clearFile() {
                                    this.fileName = ''; 
                                    this.$refs.fileInput.value = null; 
                                } 
                            }" class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        
                        <div class="flex items-center mb-4">
                            <span class="text-xs font-semibold text-gray-900 mr-3">Attachments</span>
                            <input type="file" name="attachment" x-ref="fileInput" id="attachmentInput" class="sr-only"
                                x-on:change="fileName = $event.target.files.length ? $event.target.files[0].name : ''">
                            <label for="attachmentInput"
                                class="text-xs border-l pl-3 text-blue-600 font-medium cursor-pointer hover:text-blue-700 transition">
                                Upload file
                            </label>
                        </div>

                        <template x-if="fileName">
                            <div class="flex items-center justify-between p-2.5 bg-white rounded-lg border border-gray-200">
                                <div class="flex items-center space-x-2">
                                    <svg class="h-4 w-4 text-blue-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21.44 11.05l-9.19 9.19a5 5 0 01-7.07-7.07l9.19-9.19a3 3 0 014.24 4.24L9.88 17.44a1 1 0 01-1.41-1.41l8.48-8.48" />
                                    </svg>
                                    <span class="text-gray-700 text-xs truncate" x-text="fileName"></span>
                                </div>
                                <button type="button" x-on:click="clearFile()" class="text-gray-400 hover:text-red-500 p-1 rounded-full transition flex-shrink-0">
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

                {{-- SECTION 3: Cost & Financial --}}
                <div class="space-y-4 pt-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h2 class="text-base font-semibold text-gray-900">Cost & Financial</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Cost (RM) --}}
                        <div>
                            <label for="cost" class="block text-sm font-semibold text-gray-900 mb-2">Cost (RM)</label>
                            <input name="cost" id="cost" type="text" placeholder="" 
                                x-model="formData.cost" 
                                @blur="validateField('cost')"
                                :class="{ 'border-red-500': errors.cost, 'focus:border-red-500': errors.cost }"
                                class="block w-full border border-gray-300 rounded-md shadow-sm p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p x-show="errors.cost" class="text-xs text-red-500 mt-1" x-text="errors.cost"></p>
                        </div>
                        {{-- Sales (RM) --}}
                        <div>
                            <label for="sales" class="block text-sm font-semibold text-gray-900 mb-2">Sales (RM)</label>
                            <input name="sales" id="sales" type="text" placeholder="" 
                                x-model="formData.sales" 
                                @blur="validateField('sales')"
                                :class="{ 'border-red-500': errors.sales, 'focus:border-red-500': errors.sales }"
                                class="block w-full border border-gray-300 rounded-md shadow-sm p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p x-show="errors.sales" class="text-xs text-red-500 mt-1" x-text="errors.sales"></p>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="raw_text" value="{{ $parsedData['raw_text'] ?? 'Manual Entry' }}">
                
                <div class="flex justify-end pt-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-md w-full transition duration-300">
                        Confirm →
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- OCR MODAL (No changes needed) --}}
    <div x-data="{ open: false }" 
        @open-ocr-modal.window="open = true"
        x-show="open" 
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto" 
        style="display: none;">
        
        {{-- Backdrop --}}
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center p-0">
            <div x-show="open" 
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="open = false"
                class="fixed inset-0 transition-opacity bg-black bg-opacity-50">
            </div>

            <span class="inline-block align-middle h-screen">&#8203;</span>

            {{-- Modal Content --}}
            <div x-show="open"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                class="inline-block align-middle bg-white rounded-xl px-6 pt-6 pb-4 text-left overflow-hidden shadow-2xl transform transition-all max-w-lg w-full">
                
                <form action="{{ route('shipments.create') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="flex items-start">
                        <div class="mt-0 ml-0 text-center w-full">
                            <h2 class="text-lg font-black text-gray-900 mb-1">
                                Auto Fill with Document
                            </h2>
                            <p class="text-xs text-gray-500">
                                Upload a document (PDF, JPG, PNG).
                            </p>
                            
                            <div x-data="{ fileName: '' }" class="mt-4">
                                <label for="document-upload" class="block w-full cursor-pointer">
                                    <div class="flex items-center justify-center w-full h-32 px-4 transition bg-gray-50 border border-gray-200 rounded-lg appearance-none hover:border-blue-500 hover:bg-gray-100 focus:outline-none">
                                        <div class="flex flex-col items-center space-y-2">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                            <span class="text-sm text-gray-600 font-medium" x-text="fileName || 'Click to select file or drag and drop'"></span>
                                            <span class="text-xs text-gray-500">PDF, JPG, PNG only</span>
                                        </div>
                                    </div>
                                    <input type="file" 
                                            name="document" 
                                            id="document-upload" 
                                            accept=".jpg,.jpeg,.png,.pdf" 
                                            class="hidden"
                                            @change="fileName = $event.target.files[0]?.name || ''"
                                            required> {{-- Kept required here for the file upload --}}
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex flex-row-reverse gap-3">
                        <button type="submit" class="bg-blue-600 text-xs text-white px-3 py-2 font-bold rounded-md hover:bg-blue-700">Extract</button>
                        <button type="button" @click="open = false" class="text-xs font-bold px-3 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">Cancel</button>  
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>