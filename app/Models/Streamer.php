<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Streamer extends Model
{
    protected $fillable = [
        'user_id',
        'slug',
        'bio',
        'banner',
        'stream_key',
        'is_live',
        'last_stream_started_at',
    ];

    protected $casts = [
        'is_live'                => 'boolean',
        'last_stream_started_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Streamer $streamer) {
            $streamer->stream_key = (string) Str::uuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function follows()
    {
        return $this->hasMany(Follow::class);
    }

    public function streams()
    {
        return $this->hasMany(Stream::class);
    }

    public function earnings()
    {
        return $this->hasMany(StreamerEarning::class);
    }

    public function scopeLive($query)
    {
        return $query->where('is_live', true);
    }
}
