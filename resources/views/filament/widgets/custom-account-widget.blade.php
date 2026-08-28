<x-filament-widgets::widget class="fi-account-widget">
    @php
        $user = filament()->auth()->user();
        $userName = $user->profile?->name ?? $user->name ?? $user->email;
        $userRole = $user->profile?->role ?? 'admin';
        
        $roleBadge = match($userRole) {
            'admin' => ['label' => 'Admin', 'bg' => '#fee2e2', 'text' => '#dc2626'],
            'kasir' => ['label' => 'Kasir', 'bg' => '#fef9c3', 'text' => '#a16207'],
            'stok', 'store' => ['label' => 'Stok', 'bg' => '#dbeafe', 'text' => '#1d4ed8'],
            'owner' => ['label' => 'Owner', 'bg' => '#f3e8ff', 'text' => '#7e22ce'],
            default => ['label' => ucfirst($userRole), 'bg' => '#f3f4f6', 'text' => '#374151'],
        };
    @endphp

    <x-filament::section>
        <div style="display: flex; align-items: center; gap: 16px;">
            @if ($user->avatar_url)
                <img style="height: 44px; width: 44px; border-radius: 9999px; object-fit: cover; border: 2px solid #fda4af;" src="{{ $user->avatar_url }}" alt="Avatar" />
            @else
                <div style="height: 44px; width: 44px; border-radius: 9999px; background: linear-gradient(135deg, #fb7185, #e11d48); display: flex; align-items: center; justify-content: center; color: #ffffff; font-weight: 800; font-size: 1rem;">
                    {{ strtoupper(substr($userName, 0, 2)) }}
                </div>
            @endif

            <div>
                <h2 style="font-size: 1.125rem; font-weight: 700; color: #111827; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                    <span>Selamat Datang kembali, <strong>{{ $userName }}</strong>!</span>
                    <span style="background-color: {{ $roleBadge['bg'] }}; color: {{ $roleBadge['text'] }}; font-size: 0.75rem; font-weight: 700; padding: 2px 10px; border-radius: 9999px; white-space: nowrap;">
                        Role: {{ $roleBadge['label'] }}
                    </span>
                    <span>👋</span>
                </h2>
                <p style="font-size: 0.875rem; color: #6b7280; font-weight: 400; margin-top: 2px;">
                    {{ $user->email }}
                </p>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
