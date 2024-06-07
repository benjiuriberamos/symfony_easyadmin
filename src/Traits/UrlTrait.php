<?php

namespace App\Traits;

trait UrlTrait
{
    public function getHostUrl(string $url): ?string
    {
        $parseUrl = parse_url(trim($url, '/'));

        $scheme = array_key_exists('scheme', $parseUrl) ? $parseUrl['scheme'] : null;
        if (!$scheme) {
            return null;
        }

        $host = array_key_exists('host', $parseUrl) ? $parseUrl['host'] : null;
        if (!$host) {
            return null;
        }

        $host = trim($host, '/');

        return $scheme . '://' . $host;
    }

    public function getPathUrl(string $url): string
    {
        $parseUrl = parse_url(trim($url, '/'));
        $path = array_key_exists('path', $parseUrl) ? trim($parseUrl['path'], '/') : '';
        return $path ? '/' . $path : '';
    }

    public function addPath(string $path, string $add): string
    {
        $path = $this->getPathUrl($path);
        $add = $this->getPathUrl($add);
        return $path . $add;
    }
}
