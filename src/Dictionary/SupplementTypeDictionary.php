<?php

namespace App\Dictionary;

class SupplementTypeDictionary
{
    public const SUPPLEMENT_TYPE_MALUS = 'malus';
    public const SUPPLEMENT_TYPE_BONUS = 'bonus';
    public const SUPPLEMENT_TYPE_ULTRA_BONUS = 'ultra-bonus';

    public const ALL = [
        'SUPPLEMENT_TYPE_MALUS' => self::SUPPLEMENT_TYPE_MALUS,
        'SUPPLEMENT_TYPE_BONUS' => self::SUPPLEMENT_TYPE_BONUS,
        'SUPPLEMENT_TYPE_ULTRA_BONUS' => self::SUPPLEMENT_TYPE_ULTRA_BONUS,
    ];
}