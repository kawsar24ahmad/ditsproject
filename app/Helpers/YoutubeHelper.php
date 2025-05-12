<?php

namespace App\Helpers;

class YouTubeHelper
{
    public static function extractYoutubeId($url)
    {
        $parsed = parse_url($url);

        // Handle youtu.be short links
        if (isset($parsed['host']) && $parsed['host'] === 'youtu.be') {
            return trim($parsed['path'], '/');
        }

        // Handle youtube.com links with ?v=VIDEO_ID
        if (isset($parsed['query'])) {
            parse_str($parsed['query'], $queryParams);
            return $queryParams['v'] ?? null;
        }

        return null;
    }
}
