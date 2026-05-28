<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        $invoiceQuery = Invoice::where('user_id', $userId);

        if ($fromDate) {
            $invoiceQuery->whereDate('created_at', '>=', $fromDate);
        }

        if ($toDate) {
            $invoiceQuery->whereDate('created_at', '<=', $toDate);
        }

        $totalSales = (clone $invoiceQuery)->sum('final_amount');

        $periodSales = (clone $invoiceQuery)->sum('final_amount');

        $totalInvoices = (clone $invoiceQuery)->count();

        $latestInvoices = (clone $invoiceQuery)
            ->with('customer')
            ->latest()
            ->take(5)
            ->get();

        $totalProducts = Product::where('user_id', $userId)->count();

        $totalCustomers = Customer::where('user_id', $userId)->count();

        $lowStockProducts = Product::where('user_id', $userId)
            ->where('stock_quantity', '<=', 5)
            ->count();

        $chartStartDate = $fromDate ? Carbon::parse($fromDate) : now()->subDays(6);
        $chartEndDate = $toDate ? Carbon::parse($toDate) : now();

        $salesChartLabels = collect();
        $salesChartData = collect();

        $currentDate = $chartStartDate->copy();

        while ($currentDate->lte($chartEndDate)) {
            $salesChartLabels->push($currentDate->format('d M'));

            $salesChartData->push(
                Invoice::where('user_id', $userId)
                    ->whereDate('created_at', $currentDate->format('Y-m-d'))
                    ->sum('final_amount')
            );

            $currentDate->addDay();
        }

        return view('dashboard', compact(
            'totalSales',
            'periodSales',
            'totalProducts',
            'totalCustomers',
            'totalInvoices',
            'lowStockProducts',
            'latestInvoices',
            'salesChartLabels',
            'salesChartData'
        ));
    }
    public function index_28_05_2026()
    {
        $userId = Auth::id();

        $totalSales = Invoice::where('user_id', $userId)->sum('final_amount');

        $todaySales = Invoice::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->sum('final_amount');

        $totalInvoices = Invoice::where('user_id', $userId)->count();

        $totalCustomers = Customer::where('user_id', $userId)->count();

        $totalProducts = Product::where('user_id', $userId)->count();

        $lowStockProducts = Product::where('user_id', $userId)
            ->where('stock_quantity', '<=', 5)
            ->count();

        $latestInvoices = Invoice::where('user_id', $userId)
            ->with('customer')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalSales',
            'todaySales',
            'totalInvoices',
            'totalCustomers',
            'totalProducts',
            'lowStockProducts',
            'latestInvoices'
        ));
    }
}