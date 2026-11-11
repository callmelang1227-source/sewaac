<?php

namespace App\Services\Cms;

use Illuminate\Validation\Rule;

/**
 * Declares the editable site settings groups for the admin panel. Keys map to
 * the "<group>.<key>" entries persisted in the site_settings table.
 */
class SettingSchema
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public static function groups(): array
    {
        return [
            'identity' => [
                'label' => 'Identitas bisnis',
                'description' => 'Nama bisnis, logo, dan gambar berbagi sosial.',
                'fields' => [
                    'identity.name' => ['label' => 'Nama bisnis', 'type' => 'text', 'required' => true],
                    'identity.brand_line1' => ['label' => 'Teks merek baris pertama', 'type' => 'text'],
                    'identity.brand_line2' => ['label' => 'Teks merek baris kedua', 'type' => 'text'],
                    'identity.logo_media_id' => ['label' => 'Logo', 'type' => 'media'],
                    'identity.logo_alt' => ['label' => 'Teks alternatif logo', 'type' => 'text'],
                    'identity.og_image_media_id' => ['label' => 'Gambar Open Graph', 'type' => 'media'],
                    'identity.og_image_alt' => ['label' => 'Teks alternatif gambar Open Graph', 'type' => 'text'],
                ],
                'repeaters' => [],
            ],
            'contact' => [
                'label' => 'Kontak',
                'description' => 'Nomor WhatsApp dan telepon yang tampil di seluruh situs.',
                'fields' => [
                    'contact.whatsapp_number' => ['label' => 'Nomor WhatsApp (format internasional tanpa +, contoh 628...)', 'type' => 'text', 'required' => true],
                    'contact.whatsapp_display' => ['label' => 'Nomor WhatsApp yang ditampilkan', 'type' => 'text', 'required' => true],
                    'contact.phone_number' => ['label' => 'Nomor telepon (tautan tel:)', 'type' => 'text', 'required' => true],
                    'contact.float_message' => ['label' => 'Pesan tombol WhatsApp mengambang', 'type' => 'text'],
                    'contact.general_message' => ['label' => 'Pesan WhatsApp tombol utama (:area diganti area halaman)', 'type' => 'text'],
                ],
                'repeaters' => [],
            ],
            'header' => [
                'label' => 'Header',
                'description' => 'Navigasi dan tombol ajakan di header.',
                'fields' => [
                    'header.cta_label' => ['label' => 'Label tombol header', 'type' => 'text'],
                ],
                'repeaters' => [
                    'header.nav_links' => [
                        'label' => 'Menu navigasi desktop',
                        'fields' => [
                            'label' => ['label' => 'Label menu', 'type' => 'text'],
                            'type' => ['label' => 'Jenis tautan', 'type' => 'select', 'options' => ['route' => 'Halaman', 'fragment' => 'Bagian halaman']],
                            'value' => ['label' => 'Nilai (nama rute atau ID bagian)', 'type' => 'text'],
                        ],
                    ],
                    'header.mobile_nav_links' => [
                        'label' => 'Menu navigasi seluler',
                        'fields' => [
                            'label' => ['label' => 'Label menu', 'type' => 'text'],
                            'type' => ['label' => 'Jenis tautan', 'type' => 'select', 'options' => ['route' => 'Halaman', 'fragment' => 'Bagian halaman']],
                            'value' => ['label' => 'Nilai (nama rute atau ID bagian)', 'type' => 'text'],
                        ],
                    ],
                ],
            ],
            'footer' => [
                'label' => 'Footer',
                'description' => 'Teks, tautan, dan informasi kontak di footer.',
                'fields' => [
                    'footer.description' => ['label' => 'Deskripsi bisnis (:area diganti area halaman)', 'type' => 'textarea'],
                    'footer.nav_heading' => ['label' => 'Judul menu footer', 'type' => 'text'],
                    'footer.contact_heading' => ['label' => 'Judul kontak footer', 'type' => 'text'],
                    'footer.contact_area_label' => ['label' => 'Teks area layanan (:area diganti area halaman)', 'type' => 'text'],
                    'footer.button_label' => ['label' => 'Label tombol WhatsApp', 'type' => 'text'],
                    'footer.copyright' => ['label' => 'Teks hak cipta (:year diganti tahun)', 'type' => 'text'],
                    'footer.area_text' => ['label' => 'Teks area footer', 'type' => 'text'],
                ],
                'repeaters' => [
                    'footer.nav_links' => [
                        'label' => 'Menu footer',
                        'fields' => [
                            'label' => ['label' => 'Label menu', 'type' => 'text'],
                            'type' => ['label' => 'Jenis tautan', 'type' => 'select', 'options' => ['route' => 'Halaman', 'fragment' => 'Bagian halaman']],
                            'value' => ['label' => 'Nilai (nama rute atau ID bagian)', 'type' => 'text'],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Validation rules covering every declared setting.
     *
     * @return array<string, list<mixed>>
     */
    public static function rules(): array
    {
        $rules = [];

        foreach (self::groups() as $group) {
            foreach ($group['fields'] as $key => $field) {
                $rules[$key] = self::fieldRules($field);
            }

            foreach ($group['repeaters'] ?? [] as $key => $repeater) {
                $rules[$key] = ['nullable', 'array', 'max:30'];

                foreach ($repeater['fields'] as $itemKey => $itemField) {
                    $rules["{$key}.*.{$itemKey}"] = self::fieldRules($itemField);
                }
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
        $rules = match ($field['type']) {
            'textarea' => ['nullable', 'string', 'max:1000'],
            'media' => ['nullable', 'integer', 'exists:media,id'],
            'select' => ['nullable', 'string', Rule::in(array_keys($field['options']))],
            default => ['nullable', 'string', 'max:500'],
        };

        if ($field['required'] ?? false) {
            $rules[] = 'required';
        }

        return $rules;
    }
}
