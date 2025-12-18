<?php

namespace App\Http\Controllers;

use App\Models\BirthRecord;
use App\Models\Doctor;
use App\Models\Skl;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Stats
        $totalBirths = BirthRecord::count();
        $totalSklIssued = Skl::count();
        $totalDoctors = Doctor::count();

        // Gender Stats
        $boys = BirthRecord::where('gender', 'Laki-laki')->count();
        $girls = BirthRecord::where('gender', 'Perempuan')->count();
        
        // Avoid division by zero
        $boysPercentage = $totalBirths > 0 ? round(($boys / $totalBirths) * 100) : 0;
        $girlsPercentage = $totalBirths > 0 ? round(($girls / $totalBirths) * 100) : 0;

        // Delivery Method Stats
        $deliveryStats = BirthRecord::select('delivery_method', DB::raw('count(*) as total'))
            ->groupBy('delivery_method')
            ->get()
            ->map(function($item) use ($totalBirths) {
                return [
                    'label' => $item->delivery_method,
                    'total' => $item->total,
                    'percentage' => $totalBirths > 0 ? round(($item->total / $totalBirths) * 100) : 0
                ];
            });

        // Monthly Stats (Last 6 Months) - DB Agnostic
        $monthlyStats = collect(range(5, 0))->map(function($i) {
            $date = \Carbon\Carbon::now()->subMonths($i);
            $count = BirthRecord::whereMonth('birth_date', $date->month)
                                ->whereYear('birth_date', $date->year)
                                ->count();
            return [
                'month' => $date->format('M'),
                'full_date' => $date->format('Y-m'),
                'count' => $count
            ];
        })->values();

        // Growth (Current Month vs Last Month)
        $currentMonthCount = BirthRecord::whereMonth('birth_date', now()->month)->whereYear('birth_date', now()->year)->count();
        $lastMonthCount = BirthRecord::whereMonth('birth_date', now()->subMonth()->month)->whereYear('birth_date', now()->subMonth()->year)->count();
        
        $growthPercentage = 0;
        if ($lastMonthCount > 0) {
            $growthPercentage = round((($currentMonthCount - $lastMonthCount) / $lastMonthCount) * 100);
        } elseif ($currentMonthCount > 0) {
            $growthPercentage = 100; // 100% growth if prev month was 0
        }

        // Recent Activity (Last 5 records)
        $recentRecords = BirthRecord::with('skl')->latest()->take(5)->get();

        return Inertia::render('Dashboard', [
            'stats' => [
                'total_births' => $totalBirths,
                'total_skl' => $totalSklIssued,
                'total_doctors' => $totalDoctors,
                'growth' => $growthPercentage,
                'monthly_stats' => $monthlyStats,
                'gender' => [
                    'boys' => $boys,
                    'girls' => $girls,
                    'boys_percentage' => $boysPercentage,
                    'girls_percentage' => $girlsPercentage,
                ],
                'delivery_methods' => $deliveryStats
            ],
            'recent_records' => $recentRecords
        ]);
    }
}
