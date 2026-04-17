<?php

namespace App\Livewire\Streamer;

use App\Models\Streamer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Register extends Component
{
    use WithFileUploads;

    public string $slug = '';
    public string $bio = '';
    public $banner = null;
    public bool $success = false;

    protected function rules(): array
    {
        return [
            'slug'   => ['required', 'string', 'min:3', 'max:32', 'alpha_dash', 'unique:streamers,slug'],
            'bio'    => ['nullable', 'string', 'max:300'],
            'banner' => ['nullable', 'image', 'max:2048'],
        ];
    }

    protected $messages = [
        'slug.required'   => 'El nombre de canal es obligatorio.',
        'slug.alpha_dash' => 'Solo letras, números, guiones y guiones bajos.',
        'slug.unique'     => 'Ese nombre de canal ya está tomado.',
        'banner.image'    => 'El banner debe ser una imagen.',
        'banner.max'      => 'El banner no puede superar los 2MB.',
    ];

    public function mount(): void
    {
        // Si ya es streamer, redirigir al dashboard
        if (Auth::user()->streamer) {
            $this->redirect(route('streamer.dashboard'));
        }

        // Si no tiene suscripción activa, redirigir al checkout
        if (! Auth::user()->isSubscribed()) {
            $this->redirect(route('subscription.checkout'));
        }
    }

    public function save(): void
    {
        $this->validate();

        $bannerPath = null;
        if ($this->banner) {
            $bannerPath = $this->banner->store('banners', 'public');
        }

        Streamer::create([
            'user_id'    => Auth::id(),
            'slug'       => Str::lower($this->slug),
            'bio'        => $this->bio,
            'banner'     => $bannerPath,
            'stream_key' => (string) Str::uuid(),
        ]);

        // Actualizar rol del usuario
        Auth::user()->update(['role' => 'streamer']);

        $this->success = true;
        $this->redirect(route('streamer.dashboard'));
    }

    public function render()
    {
        return view('livewire.streamer.register');
    }
}
