<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;
use Carbon\Carbon; // Untuk analisis tarikh

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Total Statistics
        $totalShipments = Shipment::count();
        $completedShipments = Shipment::where('status', 'Completed')->count();
        $pendingShipments = Shipment::where('status', 'Pending')->count();
        $totalContainers = Shipment::sum('number_of_containers');

        // Growth Calculation (last 30 days vs previous 30 days)
        $lastMonthCount = Shipment::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        $previousMonthCount = Shipment::whereBetween('created_at', [
            Carbon::now()->subDays(60),
            Carbon::now()->subDays(30)
        ])->count();
        $growthPercentage = $previousMonthCount > 0 
            ? (($lastMonthCount - $previousMonthCount) / $previousMonthCount) * 100 
            : 0;

        // Success Rate
        $successRate = $totalShipments > 0 
            ? ($completedShipments / $totalShipments) * 100 
            : 0;

        // This Month Statistics
        $thisMonthShipments = Shipment::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        
        $avgContainers = Shipment::avg('number_of_containers') ?? 0;

        // Monthly Chart Data (last 10 months)
        $monthlyChartData = collect();
        for ($i = 9; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Shipment::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $monthlyChartData->push([
                'month' => $date->format('M'),
                'count' => $count
            ]);
        }

        // Top Ports
        $topPorts = Shipment::select('port_origin')
            ->selectRaw('COUNT(*) as total')
            ->whereNotNull('port_origin')
            ->groupBy('port_origin')
            ->orderByDesc('total')
            ->limit(3)
            ->get();

        // Latest Shipments (last 10)
        $latestShipments = Shipment::latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalShipments',
            'completedShipments',
            'pendingShipments',
            'totalContainers',
            'growthPercentage',
            'successRate',
            'thisMonthShipments',
            'avgContainers',
            'monthlyChartData',
            'topPorts',
            'latestShipments'
        ));
    }
}
