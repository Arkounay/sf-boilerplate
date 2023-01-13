<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\ImageExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ImageExtension extends AbstractExtension
{

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('gImage', [ImageExtensionRuntime::class, 'image'], ['is_safe' => ['html']]),
            new TwigFunction('image_resize', [ImageExtensionRuntime::class, 'resizeImage'], ['is_safe' => ['html']]),
            new TwigFunction('image_zoom_crop', [ImageExtensionRuntime::class, 'zoomCropImage'], ['is_safe' => ['html']]),
            new TwigFunction('image_get_size', [ImageExtensionRuntime::class, 'getImageSize'])
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('alt', [ImageExtensionRuntime::class, 'generateImageAltFromPath']),
            new TwigFilter('extension', [ImageExtensionRuntime::class, 'getExtension']),
        ];
    }
}
