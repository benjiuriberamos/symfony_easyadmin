<?php

namespace App\Traits;

trait ImageTrait {
    public function pathFromImage(array $image = null): string
    {
        if (is_null($image) or !is_array($image)) {
            return '';
        }

        if (!array_key_exists('path', $image)) {
            return '';
        }

        $path = $image['path'];

        if (is_null($path)) {
            return '';
        }

        return $path;
    }

    public function altFromImage(array $image = null): string
    {
        if (is_null($image) or !is_array($image)) {
            return '';
        }

        if (!array_key_exists('alt', $image)) {
            return '';
        }

        $path = $image['alt'];

        if (is_null($path)) {
            return '';
        }

        return $path;
    }

    public function altFromPath($path)
    {
        if ($path === '') {
            return $path;
        }

        $data = explode('/', $path);
        $filename = end($data);
        $dirname = explode('.', $filename);
        $dirname = $dirname[0];
        return str_replace('-', ' ', $dirname);
    }
}
