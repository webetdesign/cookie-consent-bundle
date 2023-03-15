<?php

declare(strict_types=1);

/*
 * This file is part of the WebEtDesign CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace WebEtDesign\CookieConsentBundle\Enum;

class ThemeEnum
{
    const THEME_LIGHT = 'light';
    const THEME_DARK  = 'dark';

    /**
     * @var array
     */
    private static $themes = [
        self::THEME_LIGHT,
        self::THEME_DARK,
    ];

    /**
     * Get all cookie consent themes.
     */
    public static function getAvailableThemes(): array
    {
        return self::$themes;
    }
}
