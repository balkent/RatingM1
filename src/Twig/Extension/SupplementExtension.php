<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\SupplementExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class SupplementExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('supplementValue', [SupplementExtensionRuntime::class, 'supplementValue']),
        ];
    }
}
