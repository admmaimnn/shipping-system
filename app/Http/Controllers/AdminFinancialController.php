<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminFinancialController extends Controller
{
    public function index()
    {
        // PROFIT STATISTICS
        $totalProfit = Shipment::sum('profit') ?? 0;
        $thisMonthProfit = Shipment::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('profit') ?? 0;
        $thisYearProfit = Shipment::whereYear('created_at', Carbon::now()->year)
            ->sum('profit') ?? 0;

        // Total Sales & Cost for this month
        $thisMonthSales = Shipment::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('sales') ?? 0;
        
        $thisMonthCost = Shipment::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('cost') ?? 0;

        // Total Sales & Cost for this year
        $thisYearSales = Shipment::whereYear('created_at', Carbon::now()->year)
            ->sum('sales') ?? 0;
        
        $thisYearCost = Shipment::whereYear('created_at', Carbon::now()->year)
            ->sum('cost') ?? 0;

        // Profit Margin Calculation
        $thisMonthMargin = $thisMonthSales > 0 
            ? (($thisMonthProfit / $thisMonthSales) * 100) 
            : 0;
        
        $thisYearMargin = $thisYearSales > 0 
            ? (($thisYearProfit / $thisYearSales) * 100) 
            : 0;

        // Monthly Chart Data (last 10 months) - dengan profit, sales, cost
        $monthlyChartData = collect();
        for ($i = 9; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            
            $profit = Shipment::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('profit') ?? 0;
            
            $sales = Shipment::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('sales') ?? 0;
            
            $cost = Shipment::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('cost') ?? 0;
            
            $monthlyChartData->push([
                'month' => $date->format('M'),
                'profit' => $profit,
                'sales' => $sales,
                'cost' => $cost
            ]);
        }

        // Yearly Profit Data (last 5 years)
        $yearlyProfitData = collect();
        for ($i = 4; $i >= 0; $i--) {
            $year = Carbon::now()->subYears($i)->year;
            
            $profit = Shipment::whereYear('created_at', $year)
                ->sum('profit') ?? 0;
            
            $sales = Shipment::whereYear('created_at', $year)
                ->sum('sales') ?? 0;
            
            $cost = Shipment::whereYear('created_at', $year)
                ->sum('cost') ?? 0;
            
            $yearlyProfitData->push([
                'year' => $year,
                'profit' => $profit,
                'sales' => $sales,
                'cost' => $cost
            ]);
        }

        // Profit by Vessel (Top 10) - dengan safety check untuk division by zero
        $profitByVessel = Shipment::select('vessel_name')
            ->selectRaw('COUNT(*) as total_shipments')
            ->selectRaw('SUM(profit) as total_profit')
            ->selectRaw('SUM(sales) as total_sales')
            ->selectRaw('SUM(cost) as total_cost')
            ->selectRaw('AVG(profit) as avg_profit')
            ->selectRaw('CASE WHEN SUM(sales) > 0 THEN (SUM(profit) / SUM(sales) * 100) ELSE 0 END as profit_margin')
            ->whereNotNull('vessel_name')
            ->groupBy('vessel_name')
            ->orderByDesc('total_profit')
            ->limit(10)
            ->get();

        // Profitability by Route
        $profitabilityByRoute = Shipment::select('port_origin', 'port_destination')
            ->selectRaw('COUNT(*) as total_shipments')
            ->selectRaw('SUM(profit) as total_profit')
            ->selectRaw('AVG(profit) as avg_profit')
            ->whereNotNull('port_origin')
            ->whereNotNull('port_destination')
            ->groupBy('port_origin', 'port_destination')
            ->orderByDesc('total_profit')
            ->limit(10)
            ->get();

        // Recent Shipments with Financial Details (last 15)
        $recentFinancials = Shipment::selectRaw('booking_id, vessel_name, shipper_name, port_origin, port_destination, sales, cost, profit, shipment_date')
            ->latest()
            ->limit(15)
            ->get();

        return view('admin.financial', compact(
            'totalProfit',
            'thisMonthProfit',
            'thisYearProfit',
            'thisMonthSales',
            'thisMonthCost',
            'thisYearSales',
            'thisYearCost',
            'thisMonthMargin',
            'thisYearMargin',
            'monthlyChartData',
            'yearlyProfitData',
            'profitByVessel',
            'profitabilityByRoute',
            'recentFinancials'
        ));
    }
}