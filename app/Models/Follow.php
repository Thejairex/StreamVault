<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $fillable = [
        'user_id',
        'streamer_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function streamer()
    {
        return $this->belongsTo(Streamer::class);
    }
}
