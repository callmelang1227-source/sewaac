<?php

namespace App\Services\Cms;

use Illuminate\Validation\Rule;

/**
 * Declares the editable content structure for each page section type. The
 * schema powers the admin section editor, validation, and the defaults used
 * when a new section is created.
 */
class SectionSchema
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public static function types(): array
    {
        return [
            'hero' => [
                'label' => 'Bagian utama (hero)',
                'fields' => [
                    'headline_1' => ['label' => 'Judul baris pertama', 'type' => 'text'],
                    'headline_2' => ['label' => 'Judul baris kedua (aksen hijau)', 'type' => 'text'],
                    'intro' => ['label' => 'Teks pengantar', 'type' => 'textarea'],
                    'cta_primary_label' => ['label' => 'Label tombol utama', 'type' => 'text'],
                    'cta_secondary_label' => ['label' => 'Label tombol kedua', 'type' => 'text'],
                    'cta_secondary_href' => ['label' => 'Tautan tombol kedua', 'type' => 'text'],
                ],
                'repeaters' => [
                    'bullets' => [
                        'label' => 'Poin singkat',
                        'fields' => [
                            'text' => ['label' => 'Teks poin', 'type' => 'text'],
                        ],
                    ],
                ],
            ],
            'quick_links' => [
                'label' => 'Kartu pintasan',
                'fields' => [
                    'area_title' => ['label' => 'Judul kartu area', 'type' => 'text'],
                    'area_subtitle' => ['label' => 'Subjudul kartu area', 'type' => 'text'],
                ],
                'repeaters' => [
                    'items' => [
                        'label' => 'Kartu',
                        'fields' => [
                            'title' => ['label' => 'Judul', 'type' => 'text'],
                            'subtitle' => ['label' => 'Subjudul', 'type' => 'text'],
                            'href' => ['label' => 'Tautan (mis. #produk)', 'type' => 'text'],
                            'media_id' => ['label' => 'Gambar', 'type' => 'media'],
                        ],
                    ],
                ],
            ],
            'intro' => [
                'label' => 'Pengantar',
                'fields' => [
                    'heading' => ['label' => 'Judul bagian', 'type' => 'textarea'],
                ],
                'repeaters' => [
                    'steps' => [
                        'label' => 'Poin langkah',
                        'fields' => [
                            'title' => ['label' => 'Teks poin', 'type' => 'text'],
                        ],
                    ],
                ],
            ],
            'products' => [
                'label' => 'Produk',
                'fields' => [
                    'eyebrow' => ['label' => 'Label kecil', 'type' => 'text'],
                    'title' => ['label' => 'Judul bagian', 'type' => 'text'],
                    'note' => ['label' => 'Catatan kanan atas', 'type' => 'text'],
                    'footnote' => ['label' => 'Catatan bawah', 'type' => 'text'],
                ],
                'repeaters' => [],
            ],
            'services' => [
                'label' => 'Layanan',
                'fields' => [
                    'eyebrow' => ['label' => 'Label kecil', 'type' => 'text'],
                    'title' => ['label' => 'Judul bagian', 'type' => 'text'],
                    'link_label' => ['label' => 'Label tautan WhatsApp', 'type' => 'text'],
                    'link_wa_message' => ['label' => 'Pesan tautan WhatsApp (:area diganti area halaman)', 'type' => 'text'],
                ],
                'repeaters' => [
                    'cards' => [
                        'label' => 'Kartu layanan',
                        'fields' => [
                            'eyebrow' => ['label' => 'Label kecil kartu', 'type' => 'text'],
                            'title' => ['label' => 'Judul kartu', 'type' => 'text'],
                            'subtitle' => ['label' => 'Subjudul kartu', 'type' => 'text'],
                            'wa_message' => ['label' => 'Pesan WhatsApp', 'type' => 'text'],
                            'media_id' => ['label' => 'Gambar (kosongkan untuk kartu warna)', 'type' => 'media'],
                        ],
                    ],
                ],
            ],
            'process' => [
                'label' => 'Cara memesan',
                'fields' => [
                    'eyebrow' => ['label' => 'Label kecil', 'type' => 'text'],
                    'title' => ['label' => 'Judul bagian', 'type' => 'text'],
                    'cta_label' => ['label' => 'Label tombol WhatsApp', 'type' => 'text'],
                ],
                'repeaters' => [
                    'steps' => [
                        'label' => 'Langkah',
                        'fields' => [
                            'title' => ['label' => 'Judul langkah', 'type' => 'text'],
                            'description' => ['label' => 'Deskripsi langkah', 'type' => 'text'],
                        ],
                    ],
                ],
            ],
            'contact_cta' => [
                'label' => 'Ajakan kontak',
                'fields' => [
                    'eyebrow' => ['label' => 'Label kecil', 'type' => 'text'],
                    'title' => ['label' => 'Judul bagian', 'type' => 'text'],
                    'button_label' => ['label' => 'Label tombol', 'type' => 'text'],
                ],
                'repeaters' => [],
            ],
            'gallery' => [
                'label' => 'Galeri foto',
                'fields' => [
                    'title' => ['label' => 'Judul bagian', 'type' => 'text'],
                    'description' => ['label' => 'Deskripsi bagian', 'type' => 'text'],
                ],
                'repeaters' => [
                    'items' => [
                        'label' => 'Foto galeri',
                        'fields' => [
                            'caption' => ['label' => 'Keterangan foto', 'type' => 'text'],
                            'alt' => ['label' => 'Teks alternatif (alt)', 'type' => 'text'],
                            'span' => ['label' => 'Posisi khusus', 'type' => 'select', 'options' => [
                                '' => 'Normal',
                                'sm:row-span-2' => 'Tinggi 2 baris (tablet+)',
                                'lg:row-span-2' => 'Tinggi 2 baris (desktop)',
                            ]],
                            'media_id' => ['label' => 'Foto', 'type' => 'media'],
                        ],
                    ],
                ],
            ],
            'videos' => [
                'label' => 'Video',
                'fields' => [
                    'title' => ['label' => 'Judul bagian', 'type' => 'text'],
                ],
                'repeaters' => [],
            ],
            'areas' => [
                'label' => 'Area layanan',
                'fields' => [
                    'badge' => ['label' => 'Teks lencana', 'type' => 'text'],
                    'title' => ['label' => 'Judul bagian', 'type' => 'text'],
                    'description' => ['label' => 'Deskripsi bagian', 'type' => 'text'],
                ],
                'repeaters' => [],
            ],
            'faq' => [
                'label' => 'FAQ',
                'fields' => [
                    'badge' => ['label' => 'Teks lencana', 'type' => 'text'],
                    'title' => ['label' => 'Judul bagian', 'type' => 'text'],
                    'description' => ['label' => 'Deskripsi bagian', 'type' => 'text'],
                    'figure_caption' => ['label' => 'Keterangan gambar', 'type' => 'text'],
                    'image_media_id' => ['label' => 'Gambar samping', 'type' => 'media'],
                ],
                'repeaters' => [
                    'items' => [
                        'label' => 'Pertanyaan',
                        'fields' => [
                            'question' => ['label' => 'Pertanyaan', 'type' => 'text'],
                            'answer' => ['label' => 'Jawaban', 'type' => 'textarea'],
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function label(string $type): string
    {
        return self::types()[$type]['label'] ?? $type;
    }

    public static function exists(string $type): bool
    {
        return isset(self::types()[$type]);
    }

    /**
     * Default content payload for a freshly created section of the given type.
     *
     * @return array<string, mixed>
     */
    public static function defaultContent(string $type): array
    {
        $definition = self::types()[$type] ?? [];

        $content = [];

        foreach ($definition['fields'] ?? [] as $key => $field) {
            $content[$key] = $field['type'] === 'media' ? null : '';
        }

        foreach ($definition['repeaters'] ?? [] as $key => $repeater) {
            $content[$key] = [];
        }

        return $content;
    }

    /**
     * Validation rules for the content payload of the given section type.
     *
     * @return array<string, list<mixed>>
     */
    public static function rules(string $type): array
    {
        $definition = self::types()[$type] ?? [];
        $rules = [];

        foreach ($definition['fields'] ?? [] as $key => $field) {
            $rules["content.{$key}"] = self::fieldRules($field);
        }

        foreach ($definition['repeaters'] ?? [] as $key => $repeater) {
            $rules["content.{$key}"] = ['nullable', 'array', 'max:50'];

            foreach ($repeater['fields'] as $itemKey => $itemField) {
                $rules["content.{$key}.*.{$itemKey}"] = self::fieldRules($itemField);
            }
        }

        return $rules;
    }

    /**
     * @param  array<string, mixed>  $field
     * @return list<mixed>
     */
    private static function fieldRules(array $field): array
    {
        return match ($field['type']) {
            'textarea' => ['nullable', 'string', 'max:4000'],
            'media' => ['nullable', 'integer', 'exists:media,id'],
            'select' => ['nullable', 'string', Rule::in(array_keys($field['options']))],
            default => ['nullable', 'string', 'max:500'],
        };
    }
}
