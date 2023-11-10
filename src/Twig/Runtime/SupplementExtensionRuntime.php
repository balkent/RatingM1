<?php

namespace App\Twig\Runtime;

use App\Dictionary\SupplementTypeDictionary;
use Twig\Extension\RuntimeExtensionInterface;

class SupplementExtensionRuntime implements RuntimeExtensionInterface
{
    public function supplementValue($value)
    {
        return ucfirst(SupplementTypeDictionary::ALL[$value]);
    }
}
