<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

#[Fillable(['name', 'email', 'password', 'avatar', 'role'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    // ─── Relaciones StreamVault ───────────────────────────────────────────────

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function streamer()
    {
        return $this->hasOne(Streamer::class);
    }

    public function follows()
    {
        return $this->hasMany(Follow::class);
    }

    public function earnings()
    {
        return $this->hasMany(StreamerEarning::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function isSubscribed(): bool
    {
        return $this->subscription?->isActive() ?? false;
    }

    public function isStreamer(): bool
    {
        return $this->role === 'streamer';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function availableFollowSlots(): int
    {
        $max = config('streamvault.max_follows', 5);
        return max(0, $max - $this->follows()->count());
    }
}
