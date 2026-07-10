<x-filament-widgets::widget class="fi-account-widget">
    <x-filament::section>
        <div style="display: flex; align-items: center; gap: 16px;">
            @if (filament()->auth()->user()->avatar_url)
                <img style="height: 40px; width: 40px; border-radius: 9999px; object-fit: cover;" src="{{ filament()->auth()->user()->avatar_url }}" alt="Avatar" />
            @else
                <div style="height: 40px; width: 40px; border-radius: 9999px; background-color: #ffe4e6; display: flex; align-items: center; justify-content: center; color: #f43f5e; font-weight: bold;">
                    {{ strtoupper(substr(filament()->auth()->user()->name ?? filament()->auth()->user()->email ?? 'U', 0, 2)) }}
                </div>
            @endif

            <div style="flex: 1 1 0%;">
                <h2 style="font-size: 1rem; font-weight: 600; line-height: 1.5rem; color: #030712;">
                    Welcome
                </h2>

                <p style="font-size: 0.875rem; color: #6b7280; font-weight: 300;">
                    {{ filament()->auth()->user()->name ?? filament()->auth()->user()->email }}
                </p>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
