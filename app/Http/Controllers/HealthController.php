<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class HealthController extends Controller
{
    public function check(): JsonResponse
    {
        $status = 'healthy';
        $components = [];

        try {
            DB::connection()->getPdo();
            $components['database'] = 'ok';
        } catch (\Exception $e) {
            $status = 'unhealthy';
            $components['database'] = 'error: ' . $e->getMessage();
        }

        try {
            Cache::store()->set('health_check', true, 10);
            $components['cache'] = 'ok';
        } catch (\Exception $e) {
            $status = 'degraded';
            $components['cache'] = 'error: ' . $e->getMessage();
        }

        return response()->json([
            'status' => $status,
            'timestamp' => now()->toIso8601String(),
            'components' => $components,
        ], $status === 'healthy' ? 200 : 503);
    }
}
