<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <meta name="theme-color" content="#071b20">
    <title>Masuk Admin | Sewa AC Standing</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-[#eff5f1] font-sans text-slate-800 antialiased">
    <main class="mx-auto flex min-h-screen max-w-md items-center px-5 py-10 sm:px-8">
        <section class="w-full border border-emerald-950/10 bg-white p-6 shadow-xl shadow-slate-950/10 sm:p-8" aria-labelledby="login-title">
            <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-emerald-700">Sewa AC Standing</p>
            <h1 id="login-title" class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950">Masuk administrator</h1>
            <p class="mt-2 text-sm leading-6 text-slate-600">Gunakan akun administrator yang telah dibuat melalui sistem.</p>

            <form method="POST" action="{{ route('login.store') }}" class="mt-7 grid gap-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-bold text-slate-800">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="mt-2 block w-full border border-slate-300 bg-white px-3 py-3 text-sm text-slate-950 outline-none transition focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 @error('email') border-red-600 @enderror">
                    @error('email')
                        <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-bold text-slate-800">Kata sandi</label>
                    <input id="password" name="password" type="password" required autocomplete="current-password" class="mt-2 block w-full border border-slate-300 bg-white px-3 py-3 text-sm text-slate-950 outline-none transition focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 @error('password') border-red-600 @enderror">
                    @error('password')
                        <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p>
                    @enderror
                </div>

                <label class="flex items-center gap-2 text-sm text-slate-700">
                    <input name="remember" type="checkbox" value="1" class="size-4 border-slate-300 text-emerald-700 focus:ring-emerald-700">
                    Ingat sesi ini
                </label>

                <button type="submit" class="interactive-button inline-flex min-h-12 items-center justify-center bg-emerald-700 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-emerald-800">Masuk</button>
            </form>
        </section>
    </main>
</body>
</html>
