<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Analytics extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_type',
        'event_name',
        'event_data',
        'user_agent',
        'ip_address',
        'session_id',
        'user_id',
        'portfolio_id',
        'page_url',
        'referrer',
    ];

    protected $casts = [
        'event_data' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function portfolio()
    {
        return $this->belongsTo(PersonalInfo::class, 'portfolio_id');
    }

    // Scopes
    public function scopeByEventType($query, $eventType)
    {
        return $query->where('event_type', $eventType);
    }

    // Static Methods for Analytics
    public static function trackEvent($eventType, $eventName, $data = [])
    {
        return self::create([
            'event_type' => $eventType,
            'event_name' => $eventName,
            'event_data' => $data,
            'user_agent' => request()->userAgent(),
            'ip_address' => request()->ip(),
            'session_id' => session()->getId(),
            'user_id' => auth()->id(),
            'page_url' => request()->fullUrl(),
            'referrer' => request()->header('referer'),
        ]);
    }

    public static function trackPageView($pageName, $data = [])
    {
        return self::trackEvent('page_view', $pageName, $data);
    }

    public static function trackPortfolioView($portfolioId, $data = [])
    {
        return self::trackEvent('portfolio_view', 'portfolio_viewed', array_merge($data, ['portfolio_id' => $portfolioId]));
    }

    public static function trackDownload($portfolioId, $data = [])
    {
        return self::trackEvent('download', 'portfolio_downloaded', array_merge($data, ['portfolio_id' => $portfolioId]));
    }

    public static function trackSearch($query, $results = 0, $data = [])
    {
        return self::trackEvent('search', 'search_performed', array_merge($data, [
            'search_query' => $query,
            'results_count' => $results
        ]));
    }

    // Analytics Methods
    public static function getPageViews($days = 30)
    {
        return self::byEventType('page_view')
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->get()
            ->groupBy(function($item) {
                return $item->created_at->format('Y-m-d');
            })
            ->map(function($group, $date) {
                return (object) [
                    'date' => $date,
                    'count' => $group->count()
                ];
            })
            ->sortBy('date')
            ->values();
    }

    public static function getPopularPages($days = 30, $limit = 10)
    {
        return self::byEventType('page_view')
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->get()
            ->groupBy('event_name')
            ->map(function($group, $eventName) {
                return (object) [
                    'event_name' => $eventName,
                    'views' => $group->count()
                ];
            })
            ->sortByDesc('views')
            ->take($limit)
            ->values();
    }

    public static function getPopularPortfolios($days = 30, $limit = 10)
    {
        return self::byEventType('portfolio_view')
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->whereNotNull('portfolio_id')
            ->with('portfolio')
            ->get()
            ->groupBy('portfolio_id')
            ->map(function($group, $portfolioId) {
                return (object) [
                    'portfolio_id' => $portfolioId,
                    'views' => $group->count(),
                    'portfolio' => $group->first()->portfolio
                ];
            })
            ->sortByDesc('views')
            ->take($limit)
            ->values();
    }

    public static function getTopDownloads($days = 30, $limit = 10)
    {
        return self::byEventType('download')
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->whereNotNull('portfolio_id')
            ->with('portfolio')
            ->get()
            ->groupBy('portfolio_id')
            ->map(function($group, $portfolioId) {
                return (object) [
                    'portfolio_id' => $portfolioId,
                    'downloads' => $group->count(),
                    'portfolio' => $group->first()->portfolio
                ];
            })
            ->sortByDesc('downloads')
            ->take($limit)
            ->values();
    }

    public static function getSearchAnalytics($days = 30, $limit = 10)
    {
        return self::byEventType('search')
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->get()
            ->groupBy(function($item) {
                return $item->event_data['search_query'] ?? 'Unknown';
            })
            ->map(function($group) {
                return (object) [
                    'search_query' => $group->first()->event_data['search_query'] ?? 'Unknown',
                    'searches' => $group->count()
                ];
            })
            ->sortByDesc('searches')
            ->take($limit)
            ->values();
    }

    public static function getVisitorStats($days = 30)
    {
        $analytics = self::where('created_at', '>=', Carbon::now()->subDays($days))->get();
        
        $totalVisitors = $analytics->pluck('ip_address')->unique()->count();
        $uniqueVisitors = $analytics->whereNotNull('user_id')->pluck('user_id')->unique()->count();
        $anonymousVisitors = $totalVisitors - $uniqueVisitors;

        return [
            'total_visitors' => $totalVisitors,
            'unique_visitors' => $uniqueVisitors,
            'anonymous_visitors' => $anonymousVisitors,
        ];
    }

    public static function getTrafficSources($days = 30)
    {
        return self::where('created_at', '>=', Carbon::now()->subDays($days))
            ->whereNotNull('referrer')
            ->get()
            ->groupBy('referrer')
            ->map(function($group, $referrer) {
                return (object) [
                    'referrer' => $referrer,
                    'visits' => $group->count()
                ];
            })
            ->sortByDesc('visits')
            ->take(10)
            ->values();
    }

    public static function getDeviceStats($days = 30)
    {
        return self::where('created_at', '>=', Carbon::now()->subDays($days))
            ->whereNotNull('user_agent')
            ->get()
            ->groupBy(function($item) {
                $userAgent = $item->user_agent;
                if (strpos($userAgent, 'Mobile') !== false) {
                    return 'Mobile';
                } elseif (strpos($userAgent, 'Tablet') !== false) {
                    return 'Tablet';
                } else {
                    return 'Desktop';
                }
            })
            ->map(function($group, $deviceType) {
                return (object) [
                    'device_type' => $deviceType,
                    'count' => $group->count()
                ];
            })
            ->sortByDesc('count')
            ->values();
    }

    public static function getHourlyStats($days = 7)
    {
        return self::where('created_at', '>=', Carbon::now()->subDays($days))
            ->get()
            ->groupBy(function($item) {
                return $item->created_at->format('H');
            })
            ->map(function($group, $hour) {
                return (object) [
                    'hour' => (int)$hour,
                    'count' => $group->count()
                ];
            })
            ->sortBy('hour')
            ->values();
    }

    public static function getConversionRate($days = 30)
    {
        $totalViews = self::byEventType('portfolio_view')
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->count();

        $totalDownloads = self::byEventType('download')
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->count();

        $conversionRate = $totalViews > 0 ? ($totalDownloads / $totalViews) * 100 : 0;

        return [
            'total_views' => $totalViews,
            'total_downloads' => $totalDownloads,
            'conversion_rate' => round($conversionRate, 2),
        ];
    }
}
