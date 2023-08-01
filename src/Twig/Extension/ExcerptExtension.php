<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\ExcerptExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ExcerptExtension extends AbstractExtension
{

    public function getFunctions(): array
    {
        return [
            new TwigFunction('excerpt', [ExcerptExtensionRuntime::class, 'excerpt']),
        ];
    }
}
