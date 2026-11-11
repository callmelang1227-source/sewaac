
<header class="sticky top-0 z-30 flex h-20 items-center justify-between border-b border-slate-200 bg-white/95 px-5 backdrop-blur sm:px-7 lg:px-9">

    <div>
        <p class="text-xs text-slate-500">Administrator</p>
        <h2 class="text-sm font-bold text-slate-900">Dashboard Overview</h2>
    </div>

    <div class="flex items-center gap-4">

        <div class="hidden text-right sm:block">
            <p class="text-sm font-semibold text-slate-800">
                {{ auth()->user()->name }}
            </p>
            <p class="text-xs text-slate-500">Administrator</p>
        </div>

        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-700">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                    class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 hover:border-red-200 hover:bg-red-50 hover:text-red-600">
                Keluar
            </button>
        </form>

    </div>
</header>
