<?php

namespace App\Services\Cms;

use App\Models\Media;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaManager
{
    /**
     * Extensions allowed for image uploads.
     *
     * @var list<string>
     */
    public const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    /**
     * Extensions allowed for video uploads.
     *
     * @var list<string>
     */
    public const VIDEO_EXTENSIONS = ['mp4', 'webm', 'ogg'];

    public const MAX_IMAGE_SIZE = 10240; // kilobytes

    public const MAX_VIDEO_SIZE = 102400; // kilobytes

    /**
     * Store an uploaded image or video file on the public disk and register it
     * in the media library. The filename is generated randomly; the original
     * extension is validated against an allow list.
     *
     * @throws \InvalidArgumentException
     */
    public function upload(UploadedFile $file, ?User $uploader, ?string $altText = null): Media
    {
        $extension = Str::lower($file->getClientOriginalExtension() ?: '');
        $mimeType = $file->getMimeType() ?: 'application/octet-stream';

        $type = $this->resolveType($extension, $mimeType);

        if ($type === null) {
            throw new \InvalidArgumentException('Jenis berkas tidak didukung. Gunakan gambar (jpg, png, webp, gif) atau video (mp4, webm).');
        }

        $maxSize = $type === 'image' ? self::MAX_IMAGE_SIZE : self::MAX_VIDEO_SIZE;

        if ($file->getSize() > $maxSize * 1024) {
            throw new \InvalidArgumentException(sprintf('Ukuran berkas melebihi batas %d KB.', $maxSize));
        }

        [$width, $height] = $this->dimensions($file, $type);

        $filename = Str::uuid()->toString().'.'.$extension;
        $directory = 'media/'.now()->format('Y/m');
        $path = $file->storeAs($directory, $filename, 'public');

        if ($path === false) {
            throw new \RuntimeException('Berkas gagal disimpan ke penyimpanan publik.');
        }

        return Media::query()->create([
            'uploaded_by' => $uploader?->id,
            'disk' => 'public',
            'path' => $path,
            'original_filename' => Str::limit($file->getClientOriginalName() ?: $filename, 240),
            'filename' => $filename,
            'mime_type' => $mimeType,
            'extension' => $extension,
            'type' => $type,
            'size' => $file->getSize(),
            'width' => $width,
            'height' => $height,
            'alt_text' => $altText,
        ]);
    }

    /**
     * Replace the descriptive alt text of a media item.
     */
    public function updateAltText(Media $media, ?string $altText): Media
    {
        $media->update(['alt_text' => $altText]);

        return $media->refresh();
    }

    /**
     * Delete a media item together with its stored file. Refuses to remove
     * media that is still referenced by pages, products, or videos.
     */
    public function delete(Media $media): bool
    {
        if ($media->isInUse()) {
            return false;
        }

        return DB::transaction(function () use ($media): bool {
            $media->delete();

            if ($media->disk !== '' && Storage::disk($media->disk)->exists($media->path)) {
                Storage::disk($media->disk)->delete($media->path);
            }

            return true;
        });
    }

    /**
     * Resolve the media type from the extension and the sniffed MIME type.
     * Both must agree with the allow list to prevent disguised uploads.
     */
    private function resolveType(string $extension, string $mimeType): ?string
    {
        if (in_array($extension, self::IMAGE_EXTENSIONS, true) && str_starts_with($mimeType, 'image/')) {
            return 'image';
        }

        if (in_array($extension, self::VIDEO_EXTENSIONS, true) && str_starts_with($mimeType, 'video/')) {
            return 'video';
        }

        return null;
    }

    /**
     * Read real image dimensions to validate that the payload is an image.
     *
     * @return array{0: ?int, 1: ?int}
     */
    private function dimensions(UploadedFile $file, string $type): array
    {
        if ($type !== 'image') {
            return [null, null];
        }

        $info = @getimagesize($file->getRealPath());

        if ($info === false) {
            throw new \InvalidArgumentException('Berkas gambar tidak valid atau rusak.');
        }

        return [(int) $info[0], (int) $info[1]];
    }
}
