<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RateLimitMiddleware
{
    public function handle(Request $request, Closure $next, string $type = 'default'): Response
    {
        $key = $this->resolveRequestSignature($request, $type);
        
        $limits = [
            'auth' => ['max_attempts' => 5, 'decay_minutes' => 1],
            'api' => ['max_attempts' => 60, 'decay_minutes' => 1],
            'upload' => ['max_attempts' => 10, 'decay_minutes' => 1],
            'search' => ['max_attempts' => 30, 'decay_minutes' => 1],
            'default' => ['max_attempts' => 100, 'decay_minutes' => 1],
        ];

        $limit = $limits[$type] ?? $limits['default'];

        if (RateLimiter::tooManyAttempts($key, $limit['max_attempts'])) {
            $seconds = RateLimiter::availableIn($key);
            
            Log::warning('Rate limit exceeded', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'type' => $type,
                'key' => $key,
                'seconds_remaining' => $seconds,
            ]);

            return response()->json([
                'message' => 'Too many requests. Please try again later.',
                'retry_after' => $seconds,
            ], 429)->header('Retry-After', $seconds);
        }

        RateLimiter::hit($key, $limit['decay_minutes'] * 60);

        $response = $next($request);

        return $response->header('X-RateLimit-Limit', $limit['max_attempts'])
                       ->header('X-RateLimit-Remaining', RateLimiter::remaining($key, $limit['max_attempts']));
    }

    protected function resolveRequestSignature(Request $request, string $type): string
    {
        $user = $request->user();
        $ip = $request->ip();
        
        if ($user) {
            return sha1($type . '|' . $user->id . '|' . $ip);
        }
        
        return sha1($type . '|' . $ip);
    }
}
