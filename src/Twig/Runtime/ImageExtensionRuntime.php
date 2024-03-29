<?php

namespace App\Twig\Runtime;

use Gregwar\ImageBundle\ImageHandler;
use Gregwar\ImageBundle\Services\ImageHandling;
use Twig\Extension\RuntimeExtensionInterface;

class ImageExtensionRuntime implements RuntimeExtensionInterface
{
    private const SUPPORTED_EXTENSIONS = ['jpg', 'jpeg', 'png'];

    /**
     * @param ImageHandling $imageHandling
     */
    public function __construct(private ImageHandling $imageHandling) {}

    public function resizeImage(?string $path, ?int $width, ?int $height = null): string
    {
        if (!$path) {
            return '';
        }

        if (!in_array(strtolower($this->getExtension($path)), self::SUPPORTED_EXTENSIONS)) {
            return $path;
        }

        return (string)$this->image($path)->cropResize($width, $height);
    }

    public function zoomCropImage(?string $path, int $width, int $height, int $background = 0xffffff, string|int $xPos = 'center', string|int $yPos = 'center'): string
    {
        if (!$path) {
            return '';
        }

        if (!in_array(strtolower($this->getExtension($path)), self::SUPPORTED_EXTENSIONS)) {
            return $path;
        }

        return (string)$this->image($path)->zoomCrop($width, $height, $background, $xPos, $yPos);
    }

    public function generateImageAltFromPath(?string $path): string
    {
        if ($path === null) {
            return '';
        }

        return str_replace(['_', '-'], ' ', pathinfo($path, PATHINFO_FILENAME));
    }

    public function image(?string $path): ImageHandler
    {
        if ($path !== null && $path[0] === '/') {
            $path = urldecode(mb_substr($path, 1));
        }

        return $this->imageHandling->open($path);
    }

    public function getImageSize(string $path): array
    {
        if ($path[0] === '/') {
            $path = urldecode(mb_substr($path, 1));
        }
        try {
            return getimagesize($path);
        } catch (\Exception $e) {
            return [0, 0];
        }
    }

    public function getExtension(string $path): string
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }
}
