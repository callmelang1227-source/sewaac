<?php

namespace App\Services\Cms;

use Illuminate\Support\Str;

class YouTubeUrl
{
    /**
     * Extract the YouTube video identifier from common URL formats.
     * Supports watch, embed, short, and youtu.be links. Returns null when the
     * value does not reference a YouTube video.
     */
    public static function extractVideoId(string $url): ?string
    {
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', trim($url))) {
            return trim($url);
        }

        $parsed = parse_url(trim($url));

        if ($parsed === false || ! isset($parsed['host'])) {
            return null;
        }

        $host = Str::lower($parsed['host']);
        $knownHosts = ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtu.be', 'www.youtu.be'];

        if (! in_array($host, $knownHosts, true)) {
            return null;
        }

        $path = $parsed['path'] ?? '';
        $query = [];
        parse_str($parsed['query'] ?? '', $query);

        if ($host === 'youtu.be') {
            $candidate = Str::after($path, '/');
        } elseif (preg_match('#^/(embed|shorts|v)/([a-zA-Z0-9_-]{11})#', $path, $matches)) {
            $candidate = $matches[2];
        } elseif (isset($query['v']) && is_string($query['v'])) {
            $candidate = $query['v'];
        } else {
            return null;
        }

        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $candidate)) {
            return $candidate;
        }

        return null;
    }

    /**
     * Validate that the given URL references a playable YouTube video.
     */
    public static function isValidVideoUrl(string $url): bool
    {
        return self::extractVideoId($url) !== null;
    }
}
