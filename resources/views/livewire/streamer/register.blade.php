<div class="min-h-screen flex items-center justify-center" style="background: #131315;">
    <div class="w-full max-w-lg p-8" style="background: #201f21;">

        <div class="mb-8">
            <h1 class="text-white font-bold text-2xl" style="letter-spacing: -0.03em;">Crear canal de streamer</h1>
            <p class="mt-1 text-sm" style="color: #cdc2d8;">Configurá tu espacio en StreamVault.</p>
        </div>

        <form wire:submit="save">

            {{-- Slug --}}
            <div class="mb-5">
                <label class="block mb-1 text-xs uppercase tracking-widest" style="color: #cdc2d8;">Nombre de canal</label>
                <div class="flex items-center" style="background: #0e0e10; border-bottom: 1px solid #4b4455;">
                    <span class="px-3 py-2 text-sm" style="color: #4b4455;">streamvault.tv/</span>
                    <input
                        type="text"
                        wire:model.live="slug"
                        placeholder="mi-canal"
                        class="flex-1 px-2 py-2 text-sm bg-transparent outline-none"
                        style="color: #e5e1e4;"
                    />
                </div>
                @error('slug')
                    <p class="mt-1 text-xs" style="color: #ff6b6b;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Bio --}}
            <div class="mb-5">
                <label class="block mb-1 text-xs uppercase tracking-widest" style="color: #cdc2d8;">Bio <span style="color: #4b4455;">(opcional)</span></label>
                <textarea
                    wire:model="bio"
                    rows="3"
                    placeholder="Contá de qué se trata tu canal..."
                    class="w-full px-3 py-2 text-sm outline-none resize-none"
                    style="background: #0e0e10; color: #e5e1e4; border-bottom: 1px solid #4b4455;"
                ></textarea>
                @error('bio')
                    <p class="mt-1 text-xs" style="color: #ff6b6b;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Banner --}}
            <div class="mb-8">
                <label class="block mb-1 text-xs uppercase tracking-widest" style="color: #cdc2d8;">Banner <span style="color: #4b4455;">(opcional, máx 2MB)</span></label>
                <input
                    type="file"
                    wire:model="banner"
                    accept="image/*"
                    class="w-full text-sm"
                    style="color: #cdc2d8;"
                />
                @error('banner')
                    <p class="mt-1 text-xs" style="color: #ff6b6b;">{{ $message }}</p>
                @enderror
            </div>

            <button
                type="submit"
                class="w-full py-3 text-sm font-bold uppercase tracking-widest"
                style="background: linear-gradient(135deg, #9147ff, #5e00c1); color: #fffcff;"
            >
                Crear mi canal
            </button>
        </form>
    </div>
</div>
