<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\IconExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class IconExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('icon', [IconExtensionRuntime::class, 'icon'], ['is_safe' => ['html']]),
        ];
    }
}
