<div class="min-h-screen p-6" style="background: #131315; color: #e5e1e4;">

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="font-bold text-xl" style="letter-spacing: -0.03em;">Dashboard &mdash; {{ $streamer->slug }}</h1>
            <p class="text-sm" style="color: #cdc2d8;">{{ $totalFollowers }} seguidores</p>
        </div>
        <div class="flex items-center gap-2">
            @if ($isLive)
                <span class="flex items-center gap-1 text-xs font-bold uppercase tracking-widest" style="color: #9147ff;">
                    <span style="width:8px; height:8px; background:#9147ff; display:inline-block; animation: pulse 1.5s infinite;"></span>
                    EN VIVO
                </span>
            @else
                <span class="text-xs uppercase tracking-widest" style="color: #4b4455;">Offline</span>
            @endif
        </div>
    </div>

    {{-- Stream Key --}}
    <div class="mb-8 p-6" style="background: #201f21;">
        <h2 class="text-sm font-bold uppercase tracking-widest mb-4" style="color: #cdc2d8;">Stream Key</h2>

        @if ($keyRotated)
            <p class="mb-3 text-xs" style="color: #9147ff;">&#10003; Stream key rotada correctamente.</p>
        @endif

        <div class="flex items-center gap-3 mb-4">
            <div class="flex-1 px-3 py-2 font-mono text-sm" style="background: #0e0e10; color: #e5e1e4;">
                {{ $keyVisible ? $streamKey : str_repeat('*', 36) }}
            </div>
            <button
                wire:click="toggleKeyVisibility"
                class="px-4 py-2 text-xs uppercase tracking-widest"
                style="background: transparent; border: 1px solid #4b4455; color: #cdc2d8;"
            >
                {{ $keyVisible ? 'Ocultar' : 'Mostrar' }}
            </button>
            <button
                onclick="navigator.clipboard.writeText('{{ $streamKey }}').then(() => alert('Copiado!'))"
                class="px-4 py-2 text-xs uppercase tracking-widest"
                style="background: transparent; border: 1px solid #4b4455; color: #cdc2d8;"
            >
                Copiar
            </button>
        </div>

        <div class="flex items-center gap-4">
            <div>
                <p class="text-xs uppercase tracking-widest mb-1" style="color: #4b4455;">Server URL</p>
                <code class="text-xs" style="color: #9147ff;">rtmp://{{ request()->getHost() }}/live</code>
            </div>
        </div>

        <button
            wire:click="rotateStreamKey"
            wire:confirm="¿Estás seguro? Tu OBS va a dejar de funcionar hasta que actualices la key."
            class="mt-4 px-4 py-2 text-xs uppercase tracking-widest"
            style="background: transparent; border: 1px solid #ff6b6b; color: #ff6b6b;"
        >
            Rotar stream key
        </button>
    </div>

    {{-- Instrucciones OBS --}}
    <div class="mb-8 p-6" style="background: #201f21;">
        <h2 class="text-sm font-bold uppercase tracking-widest mb-4" style="color: #cdc2d8;">Cómo configurar OBS</h2>
        <ol class="space-y-2 text-sm" style="color: #cdc2d8;">
            <li><span style="color: #9147ff;">01.</span> Abrí OBS &rarr; Configuración &rarr; Emisiones</li>
            <li><span style="color: #9147ff;">02.</span> Servicio: <strong>Personalizado</strong></li>
            <li><span style="color: #9147ff;">03.</span> URL del servidor: <code style="color: #9147ff;">rtmp://{{ request()->getHost() }}/live</code></li>
            <li><span style="color: #9147ff;">04.</span> Clave de emisiones: tu stream key (hacela visible y copála)</li>
            <li><span style="color: #9147ff;">05.</span> Guardar y hacer clic en "Iniciar transmisión"</li>
        </ol>
    </div>

    {{-- Últimas transmisiones --}}
    @if ($recentStreams->isNotEmpty())
    <div class="p-6" style="background: #201f21;">
        <h2 class="text-sm font-bold uppercase tracking-widest mb-4" style="color: #cdc2d8;">Últimas transmisiones</h2>
        <div class="space-y-3">
            @foreach ($recentStreams as $stream)
                <div class="flex items-center justify-between py-3" style="border-bottom: 1px solid #4b4455;">
                    <div>
                        <p class="text-sm font-medium">{{ $stream->title }}</p>
                        <p class="text-xs" style="color: #4b4455;">{{ $stream->started_at?->format('d/m/Y H:i') }}</p>
                    </div>
                    <span class="text-xs" style="color: #cdc2d8;">{{ $stream->viewer_count }} viewers</span>
                </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
