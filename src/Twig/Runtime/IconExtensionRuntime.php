<?php

namespace App\Twig\Runtime;

use Twig\Environment;
use Twig\Extension\RuntimeExtensionInterface;

class IconExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(private readonly Environment $twig) {}

    public function icon(string $name, int $size = 5, ?string $class = null): string
    {
        return $this->twig->render('main/partials/_icon.html.twig', [
            'name' => $name,
            'size' => $size,
            'class' => $class
        ]);
    }
}
