<?php

namespace App\Entity\Enum;


abstract class Enum
{

    protected static array $choices;

    public static function getChoices(): array
    {
        return array_flip(static::$choices);
    }

    public static function getRawChoices(): array
    {
        return static::$choices;
    }

    public static function toString($value): string
    {
        return static::$choices[$value] ?? '-';
    }

}