<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class Audit extends Activity
{
    public function scopeFilter(Builder $query, Request $request): Builder
    {
        return $query
            ->when($request->input('causer_id'), function ($q, $causerId) {
                $q->where('causer_id', $causerId);
            })
            ->when($request->input('ip_address'), function ($q, $ip) {
                $q ->where('subject_type', IpAddress::class)
                    ->where('subject_id', $ip);
            })
            ->when($request->input('session_id'), function ($q, $sessionId) {
                $q->where('session_id', $sessionId);
            });
    }
    
    public function scopeFilterSessionOptions(Builder $query, Request $request): Builder
    {
        return $query
            ->when($request->input('causer_id') ?? null, function ($q, $userId) {
                $q->where('causer_id', $userId);
            })
            ->when($request->input('ip_address') ?? null, function ($q, $ip) {
                $q->where('subject_type', IpAddress::class)
                    ->where('subject_id', $ip);
            })
            ->when($request->input('session_id') ?? null, function ($q, $sessionId) {
                $q->where('session_id', $sessionId);
            });
    }
}
