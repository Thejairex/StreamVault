<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    protected $fillable = [
        'streamer_id',
        'title',
        'description',
        'started_at',
        'ended_at',
        'viewer_count',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at'   => 'datetime',
    ];

    public function streamer()
    {
        return $this->belongsTo(Streamer::class);
    }

    public function isLive(): bool
    {
        return !is_null($this->started_at) && is_null($this->ended_at);
    }
}
