<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Performance;
use App\Models\PersonalInfo;
use App\Models\Analytics;
use Illuminate\Http\Request;

class PerformanceController extends Controller
{
    function index(){
        // Track page view
        Analytics::trackPageView('homepage');

        // แสดงผลงานที่ได้รับการอนุมัติแล้วจาก PersonalInfo
        $approvedPortfolios = PersonalInfo::where('status', 'approved')
            ->with('ratings')
            ->orderByDesc('approved_at')
            ->orderByDesc('created_at')
            ->paginate(12);
        
        // สถิติตามคณะ
        $facultyStats = PersonalInfo::where('status', 'approved')
            ->selectRaw('faculty, COUNT(*) as count')
            ->groupBy('faculty')
            ->orderByDesc('count')
            ->get();
        
        // ผลงานที่อัพเดทล่าสุด
        $recentPortfolios = PersonalInfo::where('status', 'approved')
            ->with('ratings')
            ->orderByDesc('updated_at')
            ->take(6)
            ->get();
        
        // ยังคงแสดงข้อมูล Performance เดิมสำหรับการอ้างอิง
        $performances = Performance::active()
            ->orderByDesc('featured')
            ->orderByDesc('id')
            ->take(6)
            ->get();
        
        $categories = Performance::active()
            ->distinct()
            ->pluck('category');
            
        $featured = Performance::active()
            ->featured()
            ->take(3)
            ->get();
            
        return view('welcome', compact('approvedPortfolios', 'facultyStats', 'recentPortfolios', 'performances', 'categories', 'featured'));
    }
    
    function detail($id){
        $performance = Performance::findOrFail($id);
        
        // Increment views
        $performance->incrementViews();
        
        // Get related performances
        $related = Performance::active()
            ->where('id', '!=', $id)
            ->where('category', $performance->category)
            ->take(3)
            ->get();
            
        return view('detail', compact('performance', 'related'));
    }
    
    function category($category){
        $performances = Performance::active()
            ->byCategory($category)
            ->orderByDesc('featured')
            ->orderByDesc('id')
            ->paginate(12);
            
        return view('category', compact('performances', 'category'));
    }
    
    function search(Request $request){
        $query = $request->get('q');
        
        // Track search
        Analytics::trackSearch($query, 0);

        $performances = Performance::active()
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->orderByDesc('featured')
            ->orderByDesc('id')
            ->paginate(12);
            
        return view('search', compact('performances', 'query'));
    }
    
    function faculty($faculty){
        // แสดงผลงานที่ได้รับการอนุมัติแล้วตามคณะ (แสดง 9 ผลงานต่อหน้า)
        $portfolios = PersonalInfo::where('status', 'approved')
            ->where('faculty', $faculty)
            ->with('ratings')
            ->orderByDesc('approved_at')
            ->orderByDesc('created_at')
            ->paginate(9);
            
        return view('faculty', compact('portfolios', 'faculty'));
    }
}
