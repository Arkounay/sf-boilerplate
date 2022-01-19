<?php

namespace App\Twig;

use Gregwar\ImageBundle\ImageHandler;
use Gregwar\ImageBundle\Services\ImageHandling;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ImageExtension extends AbstractExtension
{
    private const SUPPORTED_EXTENSIONS = ['jpg', 'jpeg', 'png'];

    /**
     * @param ImageHandling $imageHandling
     */
    public function __construct(private ImageHandling $imageHandling){}

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('gImage', [$this, 'image'], ['is_safe' => ['html']]),
            new TwigFunction('resize_image', [$this, 'resizeImage'], ['is_safe' => ['html']]),
            new TwigFunction('zoom_crop_image', [$this, 'zoomCropImage'], ['is_safe' => ['html']]),
            new TwigFunction('image_size', [$this, 'getImageSize'])
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new TwigFilter('alt', [$this, 'generateImageAltFromPath']),
            new TwigFilter('extension', [$this, 'getExtension']),
        ];
    }

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

        dump($path);

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
