<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>Admin | Sewa AC Standing</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-[#eff5f1] font-sans text-slate-800 antialiased">
    <main class="mx-auto flex min-h-screen max-w-3xl items-center px-5 py-10 sm:px-8">
        <section class="w-full border border-emerald-950/10 bg-white p-6 shadow-xl shadow-slate-950/10 sm:p-8">
            <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-emerald-700">Akses berhasil</p>
            <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950">Administrator terautentikasi</h1>
            <p class="mt-2 text-sm leading-6 text-slate-600">Fondasi akun administrator sudah aktif. Dashboard CMS akan ditambahkan pada tahap berikutnya.</p>

            <form method="POST" action="{{ route('logout') }}" class="mt-7">
                @csrf
                <button type="submit" class="interactive-button inline-flex min-h-11 items-center justify-center border border-emerald-700 px-4 py-2 text-sm font-bold text-emerald-800 transition hover:bg-emerald-50">Keluar</button>
            </form>
        </section>
    </main>
</body>
</html>
