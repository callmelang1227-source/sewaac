
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') | Admin Sewa AC</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">

    <div class="min-h-screen lg:flex">
        @include('admin.partials.sidebar')

        <div class="min-w-0 flex-1 lg:pl-64">
            @include('admin.partials.topbar')

            <main class="mx-auto w-full max-w-7xl px-5 py-7 sm:px-7 lg:px-9">
                @yield('content')
            </main>

            <footer class="border-t border-slate-200 px-5 py-5 text-center text-xs text-slate-500 sm:px-7">
                © {{ date('Y') }} Admin Sewa AC. All rights reserved.
            </footer>
        </div>
    </div>

</body>
</html>
