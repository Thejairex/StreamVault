<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StreamerEarning extends Model
{
    protected $fillable = [
        'streamer_id',
        'user_id',
        'amount',
        'period_month',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function streamer()
    {
        return $this->belongsTo(Streamer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
