<?php

declare(strict_types=1);

/*
 * This file is part of the WebEtDesign CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace WebEtDesign\CookieConsentBundle\Twig;

use WebEtDesign\CookieConsentBundle\Cookie\CookieChecker;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class WDCookieConsentTwigExtension extends AbstractExtension
{
    private CookieChecker $cookieChecker;

    public function __construct(CookieChecker $cookieChecker)
    {
        $this->cookieChecker = $cookieChecker;
    }

    /**
     * Register all custom twig functions.
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction(
                'WDCookieConsent_isCookieConsentSavedByUser',
                [$this, 'isCookieConsentSavedByUser'],
                ['needs_context' => true]
            ),
            new TwigFunction(
                'WDCookieConsent_isCategoryAllowedByUser',
                [$this, 'isCategoryAllowedByUser'],
                ['needs_context' => true]
            ),
        ];
    }

    /**
     * Checks if user has sent cookie consent form.
     */
    public function isCookieConsentSavedByUser(array $context): bool
    {
        return $this->cookieChecker->isCookieConsentSavedByUser();
    }

    /**
     * Checks if user has given permission for cookie category.
     */
    public function isCategoryAllowedByUser(array $context, string $category): bool
    {
        return $this->cookieChecker->isCategoryAllowedByUser($category);
    }

    /**
     * Get instance of CookieChecker.
     */
}
