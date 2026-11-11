<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use App\Http\Controllers\Admin\DashboardController;


Route::middleware('guest')->group(function (): void {
    Route::get('/admin/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/admin/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:admin-login')
        ->name('login.store');
});

Route::post('/admin/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'admin'])->group(function (): void {
    Route::get('/admin', function () {
        return redirect()->route('admin.dashboard');
    })->name('admin.home');

    Route::get('/admin/dashboard', DashboardController::class)
        ->name('admin.dashboard');
});

foreach (config('seo.pages') as $routeName => $page) {
    Route::get($page['path'], function (Request $request) use ($page): Response|View {
        $requestPath = $request->getPathInfo();

        if ($page['path'] !== '/' && str_ends_with($requestPath, '/')) {
            $canonicalPath = rtrim($requestPath, '/');
            $queryString = $request->getQueryString();

            if ($queryString !== null) {
                $canonicalPath .= '?'.$queryString;
            }

            return response('', 301, ['Location' => $canonicalPath]);
        }

        return view('home', [
            'serviceArea' => $page['service_area'],
            'areaIntro' => $page['area_intro'],
            'pageTitle' => $page['title'],
            'pageDescription' => $page['description'],
            'breadcrumbLabel' => $page['breadcrumb_label'],
            'canonicalUrl' => config('seo.site_url').($page['path'] === '/' ? '/' : $page['path']),
        ]);
    })->name($routeName);
}

Route::get('/sitemap.xml', function () {
    $urls = collect(config('seo.pages'))
        ->map(function (array $page): string {
            $url = config('seo.site_url').($page['path'] === '/' ? '/' : $page['path']);

            return '    <url><loc>'.htmlspecialchars($url, ENT_XML1 | ENT_QUOTES, 'UTF-8').'</loc></url>';
        })
        ->implode("\n");

    return response(
        '<?xml version="1.0" encoding="UTF-8"?>'."\n"
            .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n"
            .$urls."\n"
            .'</urlset>',
        200,
        ['Content-Type' => 'application/xml; charset=UTF-8'],
    );
})->name('sitemap');

Route::get('/robots.txt', function () {
    return response(
        "User-agent: *\nAllow: /\nSitemap: ".config('seo.site_url')."/sitemap.xml\n",
        200,
        ['Content-Type' => 'text/plain; charset=UTF-8'],
    );
})->name('robots');
