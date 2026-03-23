<?php

namespace App\Models;

use App\ResolvesJti;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class IpAddress extends Model
{
    use LogsActivity, ResolvesJti;

    protected $fillable = [
        'ip_address',
        'label',
        'comment'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('ip_address');
    }

    public function tapActivity(Audit $audit, string $eventName): void
    {
        $audit->ip_address = request()->ip();
        $audit->session_id = $this->resolveJti();
    }
}
