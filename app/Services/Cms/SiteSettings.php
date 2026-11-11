<?php

namespace App\Services\Cms;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SiteSettings
{
    /** @var array<string, mixed>|null */
    private ?array $cache = null;

    /**
     * Setting defaults mirroring the original static website content. Keys
     * follow the "<group>.<key>" convention stored in the site_settings table.
     *
     * @return array<string, mixed>
     */
    public static function defaults(): array
    {
        return [
            'identity.name' => 'Sewa AC Standing',
            'identity.logo_media_id' => null,
            'identity.logo_alt' => 'Logo Sinar Sumatra',
            'identity.brand_line1' => 'SEWA AC',
            'identity.brand_line2' => 'Standing portable',
            'identity.og_image_media_id' => null,
            'identity.og_image_alt' => 'Unit AC standing portable untuk acara',

            'contact.whatsapp_number' => '6285279761446',
            'contact.whatsapp_display' => '+62 852-7976-1446',
            'contact.phone_number' => '+6285279761446',
            'contact.float_message' => 'Halo, saya butuh informasi sewa AC.',
            'contact.general_message' => 'Halo, saya ingin menanyakan sewa AC standing di :area.',

            'header.cta_label' => 'Hubungi Kami',
            'header.nav_links' => [
                ['label' => 'Beranda', 'type' => 'route', 'value' => 'home'],
                ['label' => 'Produk', 'type' => 'fragment', 'value' => 'produk'],
                ['label' => 'Layanan', 'type' => 'fragment', 'value' => 'layanan'],
                ['label' => 'Galeri', 'type' => 'fragment', 'value' => 'galeri'],
                ['label' => 'Kontak', 'type' => 'fragment', 'value' => 'kontak'],
            ],
            'header.mobile_nav_links' => [
                ['label' => 'Beranda', 'type' => 'route', 'value' => 'home'],
                ['label' => 'Produk', 'type' => 'fragment', 'value' => 'produk'],
                ['label' => 'Layanan', 'type' => 'fragment', 'value' => 'layanan'],
                ['label' => 'Cara pesan', 'type' => 'fragment', 'value' => 'proses'],
                ['label' => 'Galeri foto', 'type' => 'fragment', 'value' => 'galeri'],
                ['label' => 'Video', 'type' => 'fragment', 'value' => 'video'],
                ['label' => 'Area layanan', 'type' => 'fragment', 'value' => 'area'],
                ['label' => 'FAQ', 'type' => 'fragment', 'value' => 'faq'],
            ],

            'footer.description' => 'Sewa AC standing portable untuk acara di :area.',
            'footer.nav_heading' => 'Jelajahi halaman',
            'footer.nav_links' => [
                ['label' => 'Beranda', 'type' => 'route', 'value' => 'home'],
                ['label' => 'Produk AC', 'type' => 'fragment', 'value' => 'produk'],
                ['label' => 'Layanan', 'type' => 'fragment', 'value' => 'layanan'],
                ['label' => 'Galeri', 'type' => 'fragment', 'value' => 'galeri'],
                ['label' => 'Area layanan', 'type' => 'fragment', 'value' => 'area'],
                ['label' => 'FAQ', 'type' => 'fragment', 'value' => 'faq'],
            ],
            'footer.contact_heading' => 'Hubungi kami',
            'footer.contact_area_label' => 'Melayani area :area',
            'footer.button_label' => 'Chat via WhatsApp',
            'footer.copyright' => '© :year Sewa AC Standing. Semua hak dilindungi.',
            'footer.area_text' => 'Tangerang & Ciledug',
        ];
    }

    /**
     * Resolve a setting value, falling back to the bundled default.
     */
    public function get(string $key): mixed
    {
        return $this->all()[$key] ?? null;
    }

    /**
     * Resolve a setting value with the ":area" and ":year" placeholders
     * substituted using the page context.
     */
    public function text(string $key, ?string $serviceArea = null): ?string
    {
        $value = $this->get($key);

        if (! is_string($value)) {
            return null;
        }

        $replacements = [
            ':area' => $serviceArea ?? (string) $this->get('footer.area_text'),
            ':year' => (string) now()->year,
        ];

        return strtr($value, $replacements);
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        if ($this->cache !== null) {
            return $this->cache;
        }

        $cached = Cache::get(self::cacheKey());

        $values = is_array($cached) ? $cached : null;

        if ($values === null) {
            $values = SiteSetting::query()->pluck('value', 'key')
                ->map(fn ($value) => $value)
                ->all();

            Cache::forever(self::cacheKey(), $values);
        }

        return $this->cache = [...self::defaults(), ...$values];
    }

    /**
     * Persist one or more settings, replacing any cached values.
     *
     * @param  array<string, mixed>  $values
     */
    public function set(array $values): void
    {
        DB::transaction(function () use ($values): void {
            foreach ($values as $key => $value) {
                SiteSetting::query()->updateOrCreate(
                    ['key' => $key],
                    ['group_name' => Str::before($key, '.'), 'value' => $value],
                );
            }
        });

        $this->flush();
    }

    public function flush(): void
    {
        $this->cache = null;
        Cache::forget(self::cacheKey());
    }

    private static function cacheKey(): string
    {
        return 'cms.site-settings';
    }
}
