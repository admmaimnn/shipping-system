<x-app-layout>
    <div class="sm:py-6 max-w-full mx-auto min-h-screen">
        <div class="bg-white shadow-sm rounded-lg p-6 space-y-6">
            <div>
                <p class="font-semibold text-xl text-gray-900 leading-tight">
                    {{ __('Financial Analytics') }} 
                </p>
                <p class="text-sm text-gray-500 mt-1 border-b pb-3">
                    {{ __('Monitor your profitability and financial performance in real time.') }}
                </p>
            </div>

            <!-- SECTION 1: FINANCIAL STATS -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 border-b pb-6">
                <!-- Total Profit -->
                <div class="bg-white p-6 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Total Profit</p>
                            <p class="text-3xl font-bold text-gray-900">RM {{ number_format($totalProfit, 2) }}</p>
                            <p class="text-xs text-green-600 mt-2 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                                All-time earnings
                            </p>
                        </div>
                    </div>
                </div>

                <!-- This Month Profit -->
                <div class="bg-white p-6 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">This Month Profit</p>
                            <p class="text-3xl font-bold text-gray-900">RM {{ number_format($thisMonthProfit, 2) }}</p>
                            <p class="text-xs text-gray-500 mt-2">{{ Carbon\Carbon::now()->format('M Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- This Year Profit -->
                <div class="bg-white p-6 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">This Year Profit</p>
                            <p class="text-3xl font-bold text-gray-900">RM {{ number_format($thisYearProfit, 2) }}</p>
                            <p class="text-xs text-green-600 mt-2">{{ Carbon\Carbon::now()->year }}</p>
                        </div>
                    </div>
                </div>

                <!-- Profit Margin -->
                <div class="bg-white p-6 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Profit Margin</p>
                            <p class="text-3xl font-bold text-gray-900">{{ number_format($thisYearMargin, 1) }}%</p>
                            <p class="text-xs text-blue-600 mt-2">Year-to-date</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: CHARTS & SIDEBAR -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6" x-data="{ chartType: 'monthly' }">           
                <!-- CHART AREA -->
                <div class="lg:col-span-3 bg-white p-6 rounded-lg border border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <p class="font-semibold text-xl text-gray-900 leading-tight">
                            {{ __('Financial Charts') }} 
                        </p>
                        <div class="text-sm text-gray-500">As of {{ now()->format('M Y') }}</div>
                    </div>

                    <!-- Chart Type Toggle -->
                    <div class="space-y-6">
                        <div class="flex gap-3 mb-6">
                            <button @click="chartType = 'monthly'" :class="chartType === 'monthly' ? 'bg-indigo-50 text-indigo-600 border-indigo-200' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50'" class="px-4 py-2 rounded-lg border font-medium text-sm transition-all duration-200">
                                Monthly
                            </button>
                            <button @click="chartType = 'yearly'" :class="chartType === 'yearly' ? 'bg-indigo-50 text-indigo-600 border-indigo-200' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50'" class="px-4 py-2 rounded-lg border font-medium text-sm transition-all duration-200">
                                Yearly
                            </button>
                        </div>

                        <!-- Monthly Profit Chart -->
                        <div x-show="chartType === 'monthly'" style="height: 280px;">
                            <canvas id="monthlyProfitChart"></canvas>
                        </div>

                        <!-- Yearly Profit Chart -->
                        <div x-show="chartType === 'yearly'" style="height: 280px;">
                            <canvas id="yearlyProfitChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- SIDEBAR -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Quick Stats -->
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-xl shadow-sm text-white">
                        <h4 class="font-bold mb-4">This Month</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm opacity-90">Total Sales</span>
                                <span class="text-lg font-bold">RM {{ number_format($thisMonthSales, 0) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm opacity-90">Total Cost</span>
                                <span class="text-lg font-bold">RM {{ number_format($thisMonthCost, 0) }}</span>
                            </div>
                            <div class="border-t border-blue-400 pt-3 mt-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm opacity-90">Margin</span>
                                    <span class="text-lg font-bold">{{ number_format($thisMonthMargin, 1) }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg border border-gray-200">
                        <h4 class="font-semibold text-gray-900 mb-4">Year Summary</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center pb-3 border-b">
                                <span class="text-sm text-gray-600">Total Sales</span>
                                <span class="text-sm font-semibold text-gray-900">RM {{ number_format($thisYearSales, 0) }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-3 border-b">
                                <span class="text-sm text-gray-600">Total Cost</span>
                                <span class="text-sm font-semibold text-gray-900">RM {{ number_format($thisYearCost, 0) }}</span>
                            </div>
                            <div class="flex justify-between items-center pt-3">
                                <span class="text-sm text-gray-600 font-medium">Profit</span>
                                <span class="text-sm font-bold text-green-600">RM {{ number_format($thisYearProfit, 0) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: SALES VS COST CHARTS -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Monthly Sales vs Cost -->
                <div class="bg-white p-6 rounded-lg border border-gray-200">
                    <p class="font-semibold text-lg text-gray-900 leading-tight mb-4">
                        {{ __('Monthly Sales vs Cost') }}
                    </p>
                    <div style="height: 280px;">
                        <canvas id="monthlySalesChart"></canvas>
                    </div>
                </div>

                <!-- Yearly Sales vs Cost -->
                <div class="bg-white p-6 rounded-lg border border-gray-200">
                    <p class="font-semibold text-lg text-gray-900 leading-tight mb-4">
                        {{ __('Yearly Sales vs Cost') }}
                    </p>
                    <div style="height: 280px;">
                        <canvas id="yearlySalesChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- SECTION 4: PROFIT BY VESSEL -->
            <div class="bg-white p-6 rounded-lg border border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <p class="font-semibold text-xl text-gray-900 leading-tight">
                        {{ __('Top Performing Vessels (by Profit)') }}
                    </p>
                </div>

                @if($profitByVessel->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vessel Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shipments</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Sales</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Cost</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Profit</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Avg Profit</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Margin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($profitByVessel as $vessel)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $vessel->vessel_name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $vessel->total_shipments }}</td>
                                    <td class="px-6 py-4 text-sm text-right text-gray-900">RM {{ number_format($vessel->total_sales ?? 0, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-right text-gray-900">RM {{ number_format($vessel->total_cost ?? 0, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-right font-semibold text-green-600">
                                        RM {{ number_format($vessel->total_profit ?? 0, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right text-gray-600">RM {{ number_format($vessel->avg_profit ?? 0, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-right">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ ($vessel->profit_margin ?? 0) >= 20 ? 'bg-green-100 text-green-800' : (($vessel->profit_margin ?? 0) >= 10 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ number_format($vessel->profit_margin ?? 0, 1) }}%
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <p>No vessel data available yet</p>
                    </div>
                @endif
            </div>

            <!-- SECTION 5: PROFITABILITY BY ROUTE -->
            <div class="bg-white p-6 rounded-lg border border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <p class="font-semibold text-xl text-gray-900 leading-tight">
                        {{ __('Top Routes by Profitability') }}
                    </p>
                </div>

                @if($profitabilityByRoute->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Route</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shipments</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Profit</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Avg Profit</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($profitabilityByRoute as $route)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                        <div class="flex items-center gap-2">
                                            <span>{{ $route->port_origin ?? 'N/A' }}</span>
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                            </svg>
                                            <span>{{ $route->port_destination ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $route->total_shipments }}</td>
                                    <td class="px-6 py-4 text-sm text-right font-semibold text-green-600">RM {{ number_format($route->total_profit ?? 0, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-right text-gray-600">RM {{ number_format($route->avg_profit ?? 0, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <p>No route data available yet</p>
                    </div>
                @endif
            </div>
        </div>        
    </div>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const monthlyData = @json($monthlyChartData);
            const yearlyData = @json($yearlyProfitData);

            // Monthly Profit Chart
            const monthlyProfitCtx = document.getElementById('monthlyProfitChart');
            new Chart(monthlyProfitCtx, {
                type: 'line',
                data: {
                    labels: monthlyData.map(d => d.month),
                    datasets: [{
                        label: 'Profit',
                        data: monthlyData.map(d => d.profit),
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.15)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0,
                        pointHoverRadius: 0,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { 
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: { label: ctx => 'RM ' + ctx.parsed.y.toLocaleString('en-MY', {minimumFractionDigits: 0}) }
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                            ticks: { callback: v => v > 0 ? v : '' }
                        },
                        x: { 
                            grid: { display: false },
                            ticks: { color: 'rgba(0,0,0,0.5)' }
                        }
                    }
                }
            });

            // Yearly Profit Chart
            const yearlyProfitCtx = document.getElementById('yearlyProfitChart');
            new Chart(yearlyProfitCtx, {
                type: 'line',
                data: {
                    labels: yearlyData.map(d => d.year),
                    datasets: [{
                        label: 'Profit',
                        data: yearlyData.map(d => d.profit),
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.15)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0,
                        pointHoverRadius: 0,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { 
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: { label: ctx => 'RM ' + ctx.parsed.y.toLocaleString('en-MY', {minimumFractionDigits: 0}) }
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                            ticks: { callback: v => v > 0 ? v : '' }
                        },
                        x: { 
                            grid: { display: false },
                            ticks: { color: 'rgba(0,0,0,0.5)' }
                        }
                    }
                }
            });

            // Monthly Sales vs Cost Chart
            const monthlySalesCtx = document.getElementById('monthlySalesChart');
            new Chart(monthlySalesCtx, {
                type: 'line',
                data: {
                    labels: monthlyData.map(d => d.month),
                    datasets: [
                        {
                            label: 'Sales',
                            data: monthlyData.map(d => d.sales),
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        },
                        {
                            label: 'Cost',
                            data: monthlyData.map(d => d.cost),
                            borderColor: '#EF4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
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
                        legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 8, padding: 20 } },
                        tooltip: { callbacks: { label: ctx => ctx.dataset.label + ': RM ' + ctx.parsed.y.toLocaleString() } }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { callback: v => 'RM ' + v.toLocaleString() } },
                        x: { grid: { display: false } }
                    }
                }
            });

            // Yearly Sales vs Cost Chart
            const yearlySalesCtx = document.getElementById('yearlySalesChart');
            new Chart(yearlySalesCtx, {
                type: 'bar',
                data: {
                    labels: yearlyData.map(d => d.year),
                    datasets: [
                        {
                            label: 'Sales',
                            data: yearlyData.map(d => d.sales),
                            backgroundColor: 'rgba(59, 130, 246, 0.7)',
                            borderColor: '#3B82F6',
                            borderWidth: 2,
                            borderRadius: 4
                        },
                        {
                            label: 'Cost',
                            data: yearlyData.map(d => d.cost),
                            backgroundColor: 'rgba(239, 68, 68, 0.7)',
                            borderColor: '#EF4444',
                            borderWidth: 2,
                            borderRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 8, padding: 20 } },
                        tooltip: { callbacks: { label: ctx => ctx.dataset.label + ': RM ' + ctx.parsed.y.toLocaleString() } }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { callback: v => 'RM ' + v.toLocaleString() } },
                        x: { grid: { display: false } }
                    }
                }
            });
        });
    </script>
</x-app-layout>