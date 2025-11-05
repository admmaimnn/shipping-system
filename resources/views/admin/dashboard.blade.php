<x-app-layout>
    <div class="sm:py-6 max-w-full mx-auto ">
        <div class="bg-white shadow-sm rounded-lg p-6 space-y-6">
            <div>
                <p class="font-semibold text-xl text-gray-900 leading-tight">
                    {{ __('Dashboard Panel') }} 
                </p>
                <p class="text-sm text-gray-500 mt-1 border-b pb-3">
                    {{ __('Monitor shipment performance and logistics activity in real time.') }}
                </p>
            </div>
            <!-- SECTION 1: KAD STATS -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 border-b pb-6">
                <!-- Total Shipments -->
                <div class="bg-white p-6 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Total Shipments</p>
                            <p class="text-3xl font-bold text-gray-900">{{ number_format($totalShipments) }}</p>
                            <p class="text-xs text-green-600 mt-2 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                                +{{ number_format($growthPercentage, 1) }}% from last month
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Pending Shipments -->
                <div class="bg-white p-6 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Pending</p>
                            <p class="text-3xl font-bold text-gray-900">{{ number_format($pendingShipments) }}</p>
                            <p class="text-xs text-gray-500 mt-2">Awaiting processing</p>
                        </div>
                    </div>
                </div>

                <!-- Completed Shipments -->
                <div class="bg-white p-6 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Completed</p>
                            <p class="text-3xl font-bold text-gray-900">{{ number_format($completedShipments) }}</p>
                            <p class="text-xs text-green-600 mt-2">{{ number_format($successRate, 1) }}% success rate</p>
                        </div>
                    </div>
                </div>

                <!-- Total Containers -->
                <div class="bg-white p-6 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Total Containers</p>
                            <p class="text-3xl font-bold text-gray-900">{{ number_format($totalContainers) }}</p>
                            <p class="text-xs text-blue-600 mt-2">Active shipments</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: CHARTS & SIDEBAR -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">           
                <!-- CHART AREA -->
                <div class="lg:col-span-3 bg-white p-6 rounded-lg border border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                    <p class="font-semibold text-xl text-gray-900 leading-tight">
                        {{ __('Shipment Overview') }} 
                    </p>
                    <div class="text-sm text-gray-500">As of {{ now()->format('M Y') }}</div>
                </div>

                <div style="height: 320px;">
                    <canvas id="shipmentChart"></canvas>
                </div>

                    <!-- Top Ports Section -->
                    <div class="mt-8 pt-6 border-t">
                        <p class="font-semibold text-xl text-gray-900 leading-tight mb-4">
                            {{ __('Top Origin Ports') }} 
                        </p>
                        @if($topPorts->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach($topPorts as $port)
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-700">{{ $port->port_origin ?? 'Unknown' }}</span>
                                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">{{ $port->total }} shipments</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $totalShipments > 0 ? ($port->total / $totalShipments) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <p>No port data available yet</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- SIDEBAR -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Quick Stats -->
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-xl shadow-sm text-white">
                        <h4 class="font-bold mb-4">This Month</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm opacity-90">New Shipments</span>
                                <span class="text-2xl font-bold">{{ $thisMonthShipments }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm opacity-90">Avg. Containers</span>
                                <span class="text-2xl font-bold">{{ number_format($avgContainers, 1) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: RECENT ACTIVITY TABLE -->
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-2xl font-semibold text-gray-800">Recent Activity</h3>
                    <a href="{{ route('shipments.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                        View All →
                    </a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="shipmentsTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vessel</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shipper</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Route</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Containers</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($latestShipments as $shipment)
                            <tr class="hover:bg-gray-50 transition-colors shipment-row" 
                                data-booking="{{ strtolower($shipment->booking_id ?? '') }}"
                                data-shipper="{{ strtolower($shipment->shipper_name ?? '') }}"
                                data-vessel="{{ strtolower($shipment->vessel_name ?? '') }}">
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $shipment->booking_id }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $shipment->vessel_name ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-600">
                                    {{ $shipment->shipper_name }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-600">
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500">From: {{ $shipment->port_origin ?? 'N/A' }}</span>
                                        <span class="text-xs text-gray-500">To: {{ $shipment->port_destination ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $shipment->number_of_containers ?? 0 }} containers
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $shipment->shipment_date ? \Carbon\Carbon::parse($shipment->shipment_date)->format('Y-m-d') : 'N/A' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    <p class="text-gray-500">No shipments found</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>        
    </div>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('shipmentChart');

            const monthlyData = @json($monthlyChartData);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: monthlyData.map(d => d.month),
                    datasets: [
                        {
                            label: 'Shipments',
                            data: monthlyData.map(d => d.count),
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        },
                        {
                            label: 'Completed',
                            data: monthlyData.map(d => d.completed ?? Math.floor(Math.random() * d.count)),
                            borderColor: '#FACC15',
                            backgroundColor: 'rgba(250, 204, 21, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8,
                                padding: 20
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            ticks: { precision: 0 }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>