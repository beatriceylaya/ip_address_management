<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IpAddress extends Model
{
    protected $fillable = [
        'ip_address',
        'label',
        'comment'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
