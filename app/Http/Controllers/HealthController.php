<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class HealthController extends Controller
{
    public function check()
    {
        $status = [
            'status' => 'healthy',
            'timestamp' => now()->toISOString(),
            'version' => '1.0.0',
            'checks' => []
        ];

        // Database check
        try {
            DB::connection()->getPdo();
            $status['checks']['database'] = 'healthy';
        } catch (\Exception $e) {
            $status['checks']['database'] = 'unhealthy';
            $status['status'] = 'unhealthy';
        }

        // Cache check
        try {
            Cache::put('health_check', 'ok', 60);
            $status['checks']['cache'] = 'healthy';
        } catch (\Exception $e) {
            $status['checks']['cache'] = 'unhealthy';
            $status['status'] = 'unhealthy';
        }

        // Storage check
        try {
            Storage::disk('public')->put('health_check.txt', 'ok');
            Storage::disk('public')->delete('health_check.txt');
            $status['checks']['storage'] = 'healthy';
        } catch (\Exception $e) {
            $status['checks']['storage'] = 'unhealthy';
            $status['status'] = 'unhealthy';
        }

        // Application metrics
        $status['metrics'] = [
            'users_count' => \App\Models\User::count(),
            'portfolios_count' => \App\Models\PersonalInfo::count(),
            'documents_count' => \App\Models\Document::count(),
            'pending_approvals' => \App\Models\PersonalInfo::where('status', 'pending')->count(),
        ];

        $httpCode = $status['status'] === 'healthy' ? 200 : 503;
        
        return response()->json($status, $httpCode);
    }

    public function detailed()
    {
        $details = [
            'system' => [
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'environment' => app()->environment(),
                'debug_mode' => config('app.debug'),
            ],
            'database' => [
                'connection' => config('database.default'),
                'driver' => config('database.connections.' . config('database.default') . '.driver'),
                'host' => config('database.connections.' . config('database.default') . '.host'),
                'database' => config('database.connections.' . config('database.default') . '.database'),
            ],
            'cache' => [
                'driver' => config('cache.default'),
                'prefix' => config('cache.prefix'),
            ],
            'storage' => [
                'default' => config('filesystems.default'),
                'public_disk' => config('filesystems.disks.public.driver'),
            ],
            'queue' => [
                'default' => config('queue.default'),
                'connections' => array_keys(config('queue.connections')),
            ],
        ];

        return response()->json($details);
    }
}
