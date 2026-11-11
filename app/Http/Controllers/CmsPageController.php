<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Services\Cms\PagePresenter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CmsPageController extends Controller
{
    /**
     * Reserved single-segment slugs that never resolve to CMS pages.
     */
    private const RESERVED_SLUGS = [
        'admin', 'login', 'logout', 'register', 'up', 'api', 'storage', 'vendor', 'build',
        'sitemap', 'robots', 'telescope', 'pulse', 'nightwatch',
    ];

    public function __construct(private PagePresenter $presenter)
    {
    }

    public function __invoke(Request $request): View|RedirectResponse
    {
        $path = $request->getPathInfo();

        // Keep the previous canonical behaviour: non-root paths with a
        // trailing slash redirect (301) to the canonical path.
        if ($path !== '/' && str_ends_with($path, '/')) {
            $canonicalPath = rtrim($path, '/');
            $queryString = $request->getQueryString();

            if ($queryString !== null) {
                $canonicalPath .= '?'.$queryString;
            }

            return redirect($canonicalPath, 301);
        }

        $slug = $path === '/' ? 'home' : ltrim($path, '/');

        if (in_array($slug, self::RESERVED_SLUGS, true)) {
            abort(404);
        }

        $page = Page::query()
            ->published()
            ->where('slug', $slug)
            ->first();

        if ($page === null) {
            abort(404);
        }

        return view('home', $this->presenter->toArray($page));
    }
}
