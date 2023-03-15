<?php

declare(strict_types=1);

/*
 * This file is part of the WebEtDesign CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace WebEtDesign\CookieConsentBundle\Enum;

class PositionEnum
{
    const POSITION_TOP     = 'top';
    const POSITION_BOTTOM  = 'bottom';

    /**
     * @var array
     */
    private static $positions = [
        self::POSITION_TOP,
        self::POSITION_BOTTOM,
    ];

    /**
     * Get all cookie consent positions.
     */
    public static function getAvailablePositions(): array
    {
        return self::$positions;
    }
}
