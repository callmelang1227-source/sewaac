
<aside class="hidden lg:fixed lg:inset-y-0 lg:z-40 lg:flex lg:w-64 lg:flex-col border-r border-slate-200 bg-white">

    <div class="flex h-20 items-center gap-3 border-b border-slate-100 px-6">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-lg font-bold text-white">
            S
        </div>

        <div>
            <h1 class="text-sm font-bold text-slate-900">Sewa AC</h1>
            <p class="text-xs text-slate-500">Administrator Panel</p>
        </div>
    </div>

    <nav class="flex-1 space-y-7 overflow-y-auto px-4 py-6">

        <div>
            <p class="mb-3 px-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                Workspace
            </p>

            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 rounded-lg bg-blue-50 px-3 py-2.5 text-sm font-semibold text-blue-700">
                <span>▦</span>
                Dashboard
            </a>
        </div>

        <div>
            <p class="mb-3 px-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                Website Management
            </p>

            <div class="space-y-1">
                @php
                    $menus = [
                        ['icon' => '▤', 'label' => 'Halaman Website'],
                        ['icon' => '✎', 'label' => 'Konten Website'],
                        ['icon' => '▧', 'label' => 'Media Library'],
                        ['icon' => '▷', 'label' => 'Video'],
                        ['icon' => '□', 'label' => 'Produk & Layanan'],
                    ];
                @endphp

                @foreach ($menus as $menu)
                    <a href="#"
                       class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-600 transition hover:bg-slate-50 hover:text-blue-700">
                        <span class="w-5 text-center">{{ $menu['icon'] }}</span>
                        {{ $menu['label'] }}
                    </a>
                @endforeach
            </div>
        </div>

        <div>
            <p class="mb-3 px-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                Configuration
            </p>

            <div class="space-y-1">
                <a href="#"
                   class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-600 transition hover:bg-slate-50 hover:text-blue-700">
                    <span class="w-5 text-center">⌕</span>
                    SEO Management
                </a>

                <a href="#"
                   class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-600 transition hover:bg-slate-50 hover:text-blue-700">
                    <span class="w-5 text-center">⚙</span>
                    Pengaturan
                </a>
            </div>
        </div>

    </nav>

    <div class="border-t border-slate-100 p-4">
        <a href="/"
           target="_blank"
           class="flex items-center justify-center gap-2 rounded-lg border border-slate-200 px-4 py-2.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">
            ↗ Lihat Website
        </a>
    </div>

</aside>
