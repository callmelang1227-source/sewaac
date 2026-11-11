<?php

namespace App\Services\Cms;

use App\Models\Media;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Support\Collection;

/**
 * Builds the view payload for a public CMS page: published sections, products,
 * videos, area pages, the resolved media map, and the SEO variables consumed
 * by the site layout.
 */
class PagePresenter
{
    public function __construct(private SiteSettings $settings)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(Page $page): array
    {
        $page->load([
            'publishedSections' => fn ($query) => $query->orderBy('sort_order')->orderBy('id'),
            'publishedVideos.media',
            'publishedVideos.thumbnailMedia',
        ]);

        $products = Product::query()
            ->where('is_published', true)
            ->with('media')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $areaPages = Page::query()
            ->published()
            ->serviceAreas()
            ->with('areaCardMedia')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $videos = $this->resolveVideos($page);

        return [
            'page' => $page,
            'sections' => $page->publishedSections,
            'products' => $products,
            'videos' => $videos,
            'areaPages' => $areaPages,
            'mediaMap' => $this->mediaMap($page, $products, $videos, $areaPages),
            'settings' => $this->settings,
            'waNumber' => (string) $this->settings->get('contact.whatsapp_number'),
            'generalWaMessage' => $this->settings->text('contact.general_message', $page->service_area),

            // Layout variables consumed by the SEO head of the site layout.
            'pageTitle' => $page->meta_title ?? $page->title,
            'pageDescription' => $page->meta_description,
            'canonicalUrl' => $this->canonicalUrl($page),
            'serviceArea' => $page->service_area,
            'breadcrumbLabel' => $page->navigation_label,
        ];
    }

    private function resolveVideos(Page $page): Collection
    {
        if ($page->publishedVideos->isNotEmpty()) {
            return $page->publishedVideos;
        }

        // Pages without their own videos show the videos of the home page,
        // matching the previous site-wide video block.
        return Page::query()
            ->published()
            ->where('slug', 'home')
            ->whereKeyNot($page->getKey())
            ->with(['publishedVideos.media', 'publishedVideos.thumbnailMedia'])
            ->first()
            ?->publishedVideos
            ?? collect();
    }

    /**
     * Preload every media item referenced by the page (sections, products,
     * videos, area cards, and site identity settings) keyed by identifier.
     *
     * @param  Collection<int, Product>  $products
     * @param  Collection<\App\Models\Video>  $videos
     * @param  Collection<int, Page>  $areaPages
     */
    private function mediaMap(Page $page, Collection $products, Collection $videos, Collection $areaPages): Collection
    {
        $mediaIds = $this->collectSectionMediaIds($page);

        $products->each(fn (Product $product) => $mediaIds->push(...$product->media->modelKeys()));

        $videos->each(function (\App\Models\Video $video) use ($mediaIds): void {
            $mediaIds->push($video->media_id, $video->thumbnail_media_id);
        });

        $areaPages->each(fn (Page $areaPage) => $mediaIds->push(...$areaPage->areaCardMedia->modelKeys()));

        foreach (['identity.logo_media_id', 'identity.og_image_media_id'] as $settingKey) {
            $mediaIds->push($this->settings->get($settingKey));
        }

        return Media::query()
            ->whereIn('id', $mediaIds->filter()->unique()->values())
            ->get()
            ->keyBy('id');
    }

    /**
     * Walk the section content payloads and collect referenced media ids.
     *
     * @return \Illuminate\Support\Collection<int, int|null>
     */
    private function collectSectionMediaIds(Page $page): Collection
    {
        $mediaIds = collect();

        $walk = function (array $value) use (&$walk, $mediaIds): void {
            foreach ($value as $key => $item) {
                if (is_array($item)) {
                    $walk($item);
                } elseif (str_ends_with((string) $key, 'media_id') && is_numeric($item)) {
                    $mediaIds->push((int) $item);
                }
            }
        };

        $page->publishedSections->each(fn ($section) => $walk($section->content ?? []));

        return $mediaIds;
    }

    private function canonicalUrl(Page $page): string
    {
        if (is_string($page->canonical_path) && $page->canonical_path !== '') {
            return rtrim(config('seo.site_url'), '/').$page->canonical_path;
        }

        return $page->url();
    }
}
