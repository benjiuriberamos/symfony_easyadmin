<?php

namespace App\Controller\Api;

use App\Traits\ImageTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\UrlHelper;

class BaseApiController extends AbstractController
{
    use ImageTrait;

    protected Packages $packages;
    protected UrlHelper $urlHelper;

    public function __construct(Packages $packages, UrlHelper $urlHelper)
    {
        $this->packages = $packages;
        $this->urlHelper = $urlHelper;
    }

    public function absoluteUrl(string $path): string
    {
        return $this->urlHelper->getAbsoluteUrl($this->packages->getUrl($path));
    }

    public function absoluteUrlFromImage($image): ?string
    {
        if (is_null($image) or (!is_array($image) and !is_string($image))) {
            return null;
        }

        $path = $image;

        if (is_array($image)) {
            $path = $this->pathFromImage($image);
        }

        return $this->absoluteUrl($path);
    }

    public function arrayAbsoluteUrlFromImage($array, $key): ?array
    {
        $fn = function($e) use($key) {
            $e[$key] = $this->absoluteUrlFromImage($e[$key]);
            return $e;
        }; 

        return array_map($fn, $array);
    }
}
