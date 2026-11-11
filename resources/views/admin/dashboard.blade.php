
@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="mb-8 flex flex-col justify-between gap-3 sm:flex-row sm:items-end">
    <div>
        <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-blue-600">
            Overview
        </p>

        <h1 class="text-2xl font-bold tracking-tight text-slate-900">
            Selamat datang, {{ auth()->user()->name }}
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            Kelola konten dan informasi website Sewa AC dari satu tempat.
        </p>
    </div>

    <span class="text-xs text-slate-500">
        {{ now()->translatedFormat('l, d F Y') }}
    </span>
</div>

<div class="mb-7 rounded-xl border border-blue-100 bg-blue-50/70 p-5">
    <div class="flex items-start gap-3">
        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-700">
            ✓
        </div>

        <div>
            <h3 class="text-sm font-semibold text-blue-900">
                Sistem administrator aktif
            </h3>
            <p class="mt-1 text-xs leading-5 text-blue-800">
                Anda berhasil masuk ke panel administrator. Selanjutnya, fitur pengelolaan website dapat ditambahkan secara bertahap.
            </p>
        </div>
    </div>
</div>

<div class="mb-8">
    <h2 class="mb-4 text-sm font-bold text-slate-900">
        Akses cepat
    </h2>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">

        <a href="#"
           class="group rounded-xl border border-slate-200 bg-white p-5 transition hover:border-blue-300 hover:shadow-sm">
            <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-lg text-blue-700">
                ▤
            </div>
            <h3 class="text-sm font-semibold text-slate-900">
                Halaman Website
            </h3>
            <p class="mt-1 text-xs leading-5 text-slate-500">
                Kelola halaman utama dan halaman layanan.
            </p>
            <p class="mt-4 text-xs font-semibold text-blue-600">
                Buka pengelolaan →
            </p>
        </a>

        <a href="#"
           class="group rounded-xl border border-slate-200 bg-white p-5 transition hover:border-blue-300 hover:shadow-sm">
            <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-violet-50 text-lg text-violet-700">
                ▧
            </div>
            <h3 class="text-sm font-semibold text-slate-900">
                Media Library
            </h3>
            <p class="mt-1 text-xs leading-5 text-slate-500">
                Kelola foto dan aset gambar website.
            </p>
            <p class="mt-4 text-xs font-semibold text-blue-600">
                Buka pengelolaan →
            </p>
        </a>

        <a href="#"
           class="group rounded-xl border border-slate-200 bg-white p-5 transition hover:border-blue-300 hover:shadow-sm">
            <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 text-lg text-emerald-700">
                ⚙
            </div>
            <h3 class="text-sm font-semibold text-slate-900">
                Pengaturan Website
            </h3>
            <p class="mt-1 text-xs leading-5 text-slate-500">
                Pengaturan informasi dan konfigurasi website.
            </p>
            <p class="mt-4 text-xs font-semibold text-blue-600">
                Buka pengelolaan →
            </p>
        </a>

    </div>
</div>

@endsection
