<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Analytics;
use App\Models\Document;
use App\Models\PersonalInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GuestController extends Controller
{
    /**
     * แสดงหน้า landing page สำหรับผู้เข้าชมทั่วไป
     */
    public function landing()
    {
        return view('guest-landing');
    }

    /**
     * แสดงสถิติสาธารณะ (ไม่ต้องล็อกอิน)
     */
    public function stat(Request $request)
    {
        $period = $request->get('period', 30); // Default 30 days
        $startDate = Carbon::now()->subDays($period);
        $endDate = Carbon::now();

        // Basic Stats (only approved portfolios)
        $stats = [
            'total_portfolios' => PersonalInfo::where('status', 'approved')->count(),
            'approved_portfolios' => PersonalInfo::where('status', 'approved')->count(),
            'pending_portfolios' => 0, // Don't show pending count to guests
            'rejected_portfolios' => 0, // Don't show rejected count to guests
            'total_users' => User::count(),
        ];

        // Faculty Stats (only approved)
        $facultyStats = PersonalInfo::where('status', 'approved')
            ->selectRaw('faculty, COUNT(*) as count')
            ->groupBy('faculty')
            ->orderByDesc('count')
            ->get();

        // Major Stats (only approved)
        $majorStats = PersonalInfo::where('status', 'approved')
            ->selectRaw('major, COUNT(*) as count')
            ->groupBy('major')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Chart Data for Portfolio Trend (only approved)
        $chartData = $this->generateChartData($startDate, $endDate);

        return view('guest-stat', compact(
            'stats',
            'facultyStats',
            'majorStats',
            'chartData',
            'period'
        ));
    }

    /**
     * Generate chart data for portfolio trend (only approved portfolios)
     */
    private function generateChartData($startDate, $endDate)
    {
        $labels = [];
        $data = [];
        
        // Generate date labels for the period
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $labels[] = $currentDate->format('d/m');
            $data[] = PersonalInfo::where('status', 'approved')
                ->whereDate('created_at', $currentDate)
                ->count();
            $currentDate->addDay();
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
}
