<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\MediaAttachment;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

/**
 * Seeds the CMS with the content of the existing static website. Every value
 * mirrors what is currently rendered by the frontend so the pages keep the
 * exact same copy, images, and links after the database migration.
 */
class CmsContentSeeder extends Seeder
{
    public function run(): void
    {
        $media = $this->seedMedia();
        $pages = $this->seedPages();

        foreach ($pages as $page) {
            $this->seedSections($page, $media);
        }

        $this->seedAreaCardMedia($pages, $media);
        $this->seedProducts($media);
        $this->seedVideos($pages['home'], $media);
        $this->seedSettings($media);

        Cache::forget('cms.site-settings');
    }

    /**
     * @return array<string, Media>
     */
    private function seedMedia(): array
    {
        $images = [
            'images/logo/logo2.png' => 'Logo Sinar Sumatra',
            'images/1.webp' => 'Unit AC standing dalam ruang acara',
            'images/2.webp' => 'Beberapa AC standing di lokasi kegiatan',
            'images/3.webp' => 'AC standing portable warna hitam',
            'images/4.webp' => 'AC standing portable di area indoor',
            'images/5.webp' => 'AC standing portable di ruang acara',
            'images/7.webp' => 'AC standing di area acara indoor',
            'images/8.webp' => 'Unit AC standing di dalam ruangan',
            'images/9.webp' => 'AC standing di area gedung',
            'images/10.webp' => 'AC standing untuk kebutuhan acara',
            'images/11.webp' => 'AC standing portable di ruang kegiatan',
            'images/12.webp' => 'AC standing portable di ruang acara dengan meja dan kursi',
        ];

        $media = [];

        foreach ($images as $path => $alt) {
            $media[$path] = $this->firstOrCreateImage($path, $alt);
        }

        $media['vidio/sewa-ac.mp4'] = $this->firstOrCreateVideo('vidio/sewa-ac.mp4');
        $media['vidio/sewa-ac2.mp4'] = $this->firstOrCreateVideo('vidio/sewa-ac2.mp4');

        return $media;
    }

    private function firstOrCreateImage(string $path, string $alt): Media
    {
        return Media::query()->firstOrCreate(
            ['disk' => 'site', 'path' => $path],
            [
                'uploaded_by' => null,
                'original_filename' => basename($path),
                'filename' => basename($path),
                'mime_type' => str_ends_with($path, '.png') ? 'image/png' : 'image/webp',
                'extension' => str_ends_with($path, '.png') ? 'png' : 'webp',
                'type' => 'image',
                'size' => (int) @filesize(public_path($path)) ?: 0,
                'width' => $this->imageWidth(public_path($path)),
                'height' => $this->imageHeight(public_path($path)),
                'alt_text' => $alt,
            ],
        );
    }

    private function firstOrCreateVideo(string $path): Media
    {
        return Media::query()->firstOrCreate(
            ['disk' => 'site', 'path' => $path],
            [
                'uploaded_by' => null,
                'original_filename' => basename($path),
                'filename' => basename($path),
                'mime_type' => 'video/mp4',
                'extension' => 'mp4',
                'type' => 'video',
                'size' => (int) @filesize(public_path($path)) ?: 0,
                'width' => null,
                'height' => null,
                'alt_text' => 'Video dokumentasi sewa AC standing',
            ],
        );
    }

    private function imageWidth(string $file): ?int
    {
        $info = @getimagesize($file);

        return $info === false ? null : (int) $info[0];
    }

    private function imageHeight(string $file): ?int
    {
        $info = @getimagesize($file);

        return $info === false ? null : (int) $info[1];
    }

    /**
     * @return array<string, Page>
     */
    private function seedPages(): array
    {
        $definitions = [
            'home' => [
                'title' => 'Sewa AC Standing Tangerang & Ciledug | Rental AC Acara',
                'service_area' => 'Tangerang & Ciledug',
                'navigation_label' => null,
                'meta_description' => 'Sewa AC standing portable untuk pernikahan, seminar, pameran, dan acara di Tangerang dan Ciledug. Hubungi kami untuk cek unit dan biaya sewa.',
                'hero_intro' => 'Sewa AC standing portable untuk acara di Tangerang dan Ciledug. Hubungi kami untuk menanyakan unit dan biaya sewa.',
                'sort_order' => 0,
            ],
            'sewa-ac-tangerang' => [
                'title' => 'Sewa AC Standing Tangerang | Rental AC untuk Acara',
                'service_area' => 'Tangerang',
                'navigation_label' => 'Sewa AC Tangerang',
                'meta_description' => 'Sewa AC standing untuk acara di Tangerang. Tanyakan ketersediaan unit dan biaya dengan mengirim tanggal serta alamat acara melalui WhatsApp.',
                'hero_intro' => 'Mencari sewa AC standing di Tangerang? Kirim tanggal dan alamat acara untuk mengecek ketersediaan unit serta rincian biaya.',
                'sort_order' => 1,
            ],
            'sewa-ac-ciledug' => [
                'title' => 'Sewa AC Standing Ciledug | Rental AC untuk Acara',
                'service_area' => 'Ciledug',
                'navigation_label' => 'Sewa AC Ciledug',
                'meta_description' => 'Sewa AC standing untuk acara di Ciledug. Kirim tanggal acara dan alamat melalui WhatsApp untuk menanyakan ketersediaan unit dan biaya sewa.',
                'hero_intro' => 'Butuh AC standing untuk acara di Ciledug? Hubungi kami dengan tanggal dan alamat agar ketersediaan unit serta biaya dapat dikonfirmasi.',
                'sort_order' => 2,
            ],
        ];

        $pages = [];

        foreach ($definitions as $slug => $definition) {
            $pages[$slug] = Page::query()->firstOrCreate(
                ['slug' => $slug],
                [
                    'title' => $definition['title'],
                    'template' => $slug === 'home' ? 'home' : 'service_area',
                    'service_area' => $definition['service_area'],
                    'navigation_label' => $definition['navigation_label'],
                    'meta_title' => $definition['title'],
                    'meta_description' => $definition['meta_description'],
                    'meta_keywords' => null,
                    'meta_robots' => 'index,follow,max-image-preview:large',
                    'canonical_path' => null,
                    'open_graph_title' => null,
                    'open_graph_description' => null,
                    'is_published' => true,
                    'published_at' => now(),
                    'sort_order' => $definition['sort_order'],
                ],
            );
        }

        return $pages;
    }

    /**
     * @param  array<string, Media>  $media
     */
    private function seedSections(Page $page, array $media): void
    {
        $serviceArea = $page->service_area;

        $sections = [
            'hero' => [
                'type' => 'hero',
                'sort_order' => 0,
                'content' => [
                    'headline_1' => 'Sewa AC',
                    'headline_2' => 'Standing',
                    'intro' => $this->heroIntro($page),
                    'cta_primary_label' => 'Hubungi WhatsApp',
                    'cta_secondary_label' => 'Lihat produk',
                    'cta_secondary_href' => '#produk',
                    'bullets' => [
                        ['text' => 'AC standing portable'],
                        ['text' => 'Cek ketersediaan unit'],
                        ['text' => 'Konfirmasi biaya sewa'],
                    ],
                ],
            ],
            'quick_links' => [
                'type' => 'quick_links',
                'sort_order' => 1,
                'content' => [
                    'area_title' => 'Area Layanan',
                    'area_subtitle' => 'Tangerang & Ciledug',
                    'items' => [
                        ['title' => 'AC Standing', 'subtitle' => 'Portable · Acara', 'href' => '#produk', 'media_id' => $media['images/3.webp']->id],
                        ['title' => 'Event & Pernikahan', 'subtitle' => 'Acara indoor', 'href' => '#layanan', 'media_id' => $media['images/12.webp']->id],
                        ['title' => 'Seminar & Pameran', 'subtitle' => 'Dokumentasi acara', 'href' => '#galeri', 'media_id' => $media['images/1.webp']->id],
                        ['title' => 'Gathering & Event', 'subtitle' => 'Untuk kegiatan', 'href' => '#layanan', 'media_id' => $media['images/2.webp']->id],
                    ],
                ],
            ],
            'intro' => [
                'type' => 'intro',
                'sort_order' => 2,
                'content' => [
                    'heading' => "Sewa AC standing portable di {$serviceArea}",
                    'steps' => [
                        ['title' => 'Pilih kebutuhan AC'],
                        ['title' => 'Cek unit dan jadwal'],
                        ['title' => 'Konfirmasi biaya sewa'],
                    ],
                ],
            ],
            'products' => [
                'type' => 'products',
                'sort_order' => 3,
                'content' => [
                    'eyebrow' => 'Unit portable',
                    'title' => 'Pilih unit AC',
                    'note' => 'Tanyakan spesifikasi dan harga melalui WhatsApp.',
                    'footnote' => 'Foto dokumentasi unit AC standing portable; jumlah, spesifikasi, dan harga dikonfirmasi saat pemesanan.',
                ],
            ],
            'services' => [
                'type' => 'services',
                'sort_order' => 4,
                'content' => [
                    'eyebrow' => 'Layanan untuk acara',
                    'title' => 'Sewa AC untuk berbagai acara',
                    'link_label' => 'Tanya layanan',
                    'link_wa_message' => 'Halo, saya ingin menanyakan layanan sewa AC di :area.',
                    'cards' => [
                        [
                            'eyebrow' => 'Untuk acara',
                            'title' => 'Sewa AC untuk event',
                            'subtitle' => null,
                            'wa_message' => 'Halo, saya ingin tanya sewa AC standing untuk event.',
                            'media_id' => $media['images/12.webp']->id,
                        ],
                        [
                            'eyebrow' => 'Unit portable / 01',
                            'title' => 'Sewa AC standing',
                            'subtitle' => 'Tanya unit dan harga',
                            'wa_message' => 'Halo, saya ingin tanya sewa AC standing.',
                            'media_id' => null,
                        ],
                    ],
                ],
            ],
            'process' => [
                'type' => 'process',
                'sort_order' => 5,
                'content' => [
                    'eyebrow' => 'Cara memesan',
                    'title' => 'Pemesanan dalam tiga langkah',
                    'cta_label' => 'Mulai lewat WhatsApp',
                    'steps' => [
                        ['title' => 'Pilih kebutuhan AC', 'description' => 'Tentukan kebutuhan unit untuk acara.'],
                        ['title' => 'Hubungi WhatsApp', 'description' => 'Kirim tanggal dan alamat acara.'],
                        ['title' => 'Konfirmasi jadwal', 'description' => 'Tanyakan ketersediaan dan biaya.'],
                    ],
                ],
            ],
            'contact_cta' => [
                'type' => 'contact_cta',
                'sort_order' => 6,
                'content' => [
                    'eyebrow' => 'Hubungi kami',
                    'title' => 'Butuh AC untuk acara?',
                    'button_label' => 'Hubungi WhatsApp',
                ],
            ],
            'gallery' => [
                'type' => 'gallery',
                'sort_order' => 7,
                'content' => [
                    'title' => 'Unit, ruang, dan momen',
                    'description' => 'Foto dokumentasi AC standing dan penempatannya di lokasi kegiatan.',
                    'items' => [
                        ['caption' => 'Dokumentasi acara', 'alt' => 'Unit AC standing dalam ruang acara', 'span' => 'sm:row-span-2', 'media_id' => $media['images/1.webp']->id],
                        ['caption' => 'Unit AC standing', 'alt' => 'Beberapa AC standing di lokasi kegiatan', 'span' => '', 'media_id' => $media['images/2.webp']->id],
                        ['caption' => 'Detail unit', 'alt' => 'AC standing portable warna hitam', 'span' => '', 'media_id' => $media['images/3.webp']->id],
                        ['caption' => 'Unit di lokasi', 'alt' => 'AC standing portable di area indoor', 'span' => '', 'media_id' => $media['images/4.webp']->id],
                        ['caption' => 'Suasana acara', 'alt' => 'AC standing portable di ruang acara', 'span' => 'lg:row-span-2', 'media_id' => $media['images/5.webp']->id],
                        ['caption' => 'Penempatan unit', 'alt' => 'Unit AC standing di dalam ruangan', 'span' => '', 'media_id' => $media['images/8.webp']->id],
                        ['caption' => 'Dokumentasi lokasi', 'alt' => 'AC standing di area gedung', 'span' => '', 'media_id' => $media['images/9.webp']->id],
                        ['caption' => 'AC di acara', 'alt' => 'AC standing untuk kebutuhan acara', 'span' => '', 'media_id' => $media['images/10.webp']->id],
                        ['caption' => 'Ruang kegiatan', 'alt' => 'Unit AC standing di ruang kegiatan', 'span' => '', 'media_id' => $media['images/11.webp']->id],
                        ['caption' => 'Acara dalam ruangan', 'alt' => 'Unit AC standing di ruang acara dengan meja dan kursi', 'span' => '', 'media_id' => $media['images/12.webp']->id],
                        ['caption' => 'Unit di lokasi acara', 'alt' => 'AC standing di area acara indoor', 'span' => '', 'media_id' => $media['images/7.webp']->id],
                    ],
                ],
            ],
            'videos' => [
                'type' => 'videos',
                'sort_order' => 8,
                'content' => [
                    'title' => 'Lihat dokumentasi video',
                ],
            ],
            'areas' => [
                'type' => 'areas',
                'sort_order' => 9,
                'content' => [
                    'badge' => 'Area layanan',
                    'title' => 'Pilih area untuk informasi sewa AC',
                    'description' => 'Konfirmasikan cakupan layanan berdasarkan alamat acara.',
                ],
            ],
            'faq' => [
                'type' => 'faq',
                'sort_order' => 10,
                'content' => [
                    'badge' => 'FAQ',
                    'title' => 'Sebelum memesan',
                    'description' => 'Jawaban singkat untuk membantu Anda menyiapkan sewa AC.',
                    'figure_caption' => 'Dokumentasi unit AC standing portable',
                    'image_media_id' => $media['images/11.webp']->id,
                    'items' => [
                        [
                            'question' => 'Informasi apa yang perlu dikirim untuk cek sewa AC?',
                            'answer' => 'Kirim tanggal acara, alamat, jenis acara, dan perkiraan jumlah unit agar ketersediaan serta biaya bisa dikonfirmasi.',
                        ],
                        [
                            'question' => 'Untuk acara apa AC standing dapat disewa?',
                            'answer' => 'Tanyakan unit untuk kebutuhan seperti pernikahan, seminar, pameran, gathering, dan acara lainnya.',
                        ],
                        [
                            'question' => 'Berapa harga sewa AC per hari?',
                            'answer' => 'Harga belum dicantumkan karena bergantung pada unit, durasi, dan alamat. Hubungi kami untuk meminta rincian sebelum memesan.',
                        ],
                    ],
                ],
            ],
        ];

        foreach ($sections as $sectionKey => $definition) {
            PageSection::query()->updateOrCreate(
                ['page_id' => $page->id, 'section_key' => $sectionKey],
                [
                    'type' => $definition['type'],
                    'content' => $definition['content'],
                    'is_published' => true,
                    'sort_order' => $definition['sort_order'],
                ],
            );
        }
    }

    private function heroIntro(Page $page): string
    {
        return match ($page->slug) {
            'home' => 'Sewa AC standing portable untuk acara di Tangerang dan Ciledug. Hubungi kami untuk menanyakan unit dan biaya sewa.',
            'sewa-ac-tangerang' => 'Mencari sewa AC standing di Tangerang? Kirim tanggal dan alamat acara untuk mengecek ketersediaan unit serta rincian biaya.',
            'sewa-ac-ciledug' => 'Butuh AC standing untuk acara di Ciledug? Hubungi kami dengan tanggal dan alamat agar ketersediaan unit serta biaya dapat dikonfirmasi.',
            default => 'Sewa AC standing portable untuk acara Anda. Hubungi kami untuk menanyakan unit dan biaya sewa.',
        };
    }

    /**
     * @param  array<string, Page>  $pages
     * @param  array<string, Media>  $media
     */
    private function seedAreaCardMedia(array $pages, array $media): void
    {
        $cards = [
            'sewa-ac-tangerang' => ['images/11.webp', 0],
            'sewa-ac-ciledug' => ['images/12.webp', 1],
        ];

        foreach ($cards as $slug => [$image, $sortOrder]) {
            MediaAttachment::query()->firstOrCreate(
                [
                    'media_id' => $media[$image]->id,
                    'attachable_type' => Page::class,
                    'attachable_id' => $pages[$slug]->id,
                    'slot' => 'area_card',
                ],
                ['sort_order' => $sortOrder],
            );
        }
    }

    /**
     * @param  array<string, Media>  $media
     */
    private function seedProducts(array $media): void
    {
        $products = [
            [
                'name' => 'AC Standing',
                'slug' => 'ac-standing-portable',
                'short_description' => 'Unit portable',
                'description' => null,
                'specifications' => null,
                'pk_capacity' => null,
                'price_label' => null,
                'wa_message' => 'Halo, saya ingin menanyakan unit AC standing portable di :area.',
                'image' => 'images/3.webp',
                'image_position' => null,
                'sort_order' => 0,
            ],
            [
                'name' => 'AC Standing',
                'slug' => 'ac-standing-foto-di-lokasi',
                'short_description' => 'Foto di lokasi',
                'description' => null,
                'specifications' => null,
                'pk_capacity' => null,
                'price_label' => null,
                'wa_message' => 'Halo, saya ingin melihat pilihan foto unit AC standing di :area.',
                'image' => 'images/11.webp',
                'image_position' => 'center_35',
                'sort_order' => 1,
            ],
            [
                'name' => 'AC Standing',
                'slug' => 'ac-standing-untuk-acara',
                'short_description' => 'Untuk acara',
                'description' => null,
                'specifications' => null,
                'pk_capacity' => null,
                'price_label' => null,
                'wa_message' => 'Halo, saya ingin menanyakan unit AC standing untuk acara di :area.',
                'image' => 'images/12.webp',
                'image_position' => 'center_40',
                'sort_order' => 2,
            ],
        ];

        foreach ($products as $definition) {
            $product = Product::query()->firstOrCreate(
                ['slug' => $definition['slug']],
                [
                    'name' => $definition['name'],
                    'short_description' => $definition['short_description'],
                    'description' => $definition['description'],
                    'specifications' => $definition['specifications'],
                    'pk_capacity' => $definition['pk_capacity'],
                    'price_label' => $definition['price_label'],
                    'wa_message' => $definition['wa_message'],
                    'is_available' => true,
                    'is_published' => true,
                    'sort_order' => $definition['sort_order'],
                ],
            );

            $product->media()->syncWithoutDetaching([
                $media[$definition['image']]->id => ['is_cover' => true, 'sort_order' => 0],
            ]);
        }
    }

    /**
     * @param  array<string, Media>  $media
     */
    private function seedVideos(Page $page, array $media): void
    {
        $videos = [
            [
                'title' => 'Dokumentasi sewa AC',
                'description' => 'Pilih untuk memutar video',
                'media' => 'vidio/sewa-ac.mp4',
                'thumbnail' => 'images/7.webp',
                'sort_order' => 0,
            ],
            [
                'title' => 'Video kegiatan',
                'description' => 'Pilih untuk memutar',
                'media' => 'vidio/sewa-ac2.mp4',
                'thumbnail' => 'images/10.webp',
                'sort_order' => 1,
            ],
        ];

        foreach ($videos as $definition) {
            Video::query()->firstOrCreate(
                [
                    'page_id' => $page->id,
                    'media_id' => $media[$definition['media']]->id,
                ],
                [
                    'thumbnail_media_id' => $media[$definition['thumbnail']]->id,
                    'title' => $definition['title'],
                    'description' => $definition['description'],
                    'source_type' => 'local',
                    'external_url' => null,
                    'is_published' => true,
                    'sort_order' => $definition['sort_order'],
                ],
            );
        }
    }

    /**
     * @param  array<string, Media>  $media
     */
    private function seedSettings(array $media): void
    {
        $values = [
            'identity.name' => 'Sewa AC Standing',
            'identity.brand_line1' => 'SEWA AC',
            'identity.brand_line2' => 'Standing portable',
            'identity.logo_media_id' => $media['images/logo/logo2.png']->id,
            'identity.logo_alt' => 'Logo Sinar Sumatra',
            'identity.og_image_media_id' => $media['images/3.webp']->id,
            'identity.og_image_alt' => 'Unit AC standing portable untuk acara',

            'contact.whatsapp_number' => '6285279761446',
            'contact.whatsapp_display' => '+62 852-7976-1446',
            'contact.phone_number' => '+6285279761446',
            'contact.float_message' => 'Halo, saya butuh informasi sewa AC.',
            'contact.general_message' => 'Halo, saya ingin menanyakan sewa AC standing di :area.',

            'header.cta_label' => 'Hubungi Kami',

            'footer.description' => 'Sewa AC standing portable untuk acara di :area.',
            'footer.nav_heading' => 'Jelajahi halaman',
            'footer.contact_heading' => 'Hubungi kami',
            'footer.contact_area_label' => 'Melayani area :area',
            'footer.button_label' => 'Chat via WhatsApp',
            'footer.copyright' => '© :year Sewa AC Standing. Semua hak dilindungi.',
            'footer.area_text' => 'Tangerang & Ciledug',
        ];

        foreach ($values as $key => $value) {
            SiteSetting::query()->firstOrCreate(
                ['key' => $key],
                ['group_name' => strtok($key, '.'), 'value' => $value],
            );
        }
    }
}
