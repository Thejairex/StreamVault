<?php

namespace App\Livewire\Streamer;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class Dashboard extends Component
{
    public bool $keyVisible = false;
    public bool $keyRotated = false;

    public function mount(): void
    {
        if (! Auth::user()->streamer) {
            $this->redirect(route('streamer.register'));
        }
    }

    public function toggleKeyVisibility(): void
    {
        $this->keyVisible = ! $this->keyVisible;
    }

    public function rotateStreamKey(): void
    {
        Auth::user()->streamer->update([
            'stream_key' => (string) Str::uuid(),
        ]);

        $this->keyRotated = true;
        $this->keyVisible = false;
        $this->dispatch('key-rotated');
    }

    public function render()
    {
        $streamer = Auth::user()->streamer;

        return view('livewire.streamer.dashboard', [
            'streamer'       => $streamer,
            'streamKey'      => $streamer->stream_key,
            'totalFollowers' => $streamer->follows()->count(),
            'isLive'         => $streamer->is_live,
            'recentStreams'  => $streamer->streams()->latest('started_at')->take(5)->get(),
        ]);
    }
}
