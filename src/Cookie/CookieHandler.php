<?php

declare(strict_types=1);

/*
 * This file is part of the WebEtDesign CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace WebEtDesign\CookieConsentBundle\Cookie;

use WebEtDesign\CookieConsentBundle\Enum\CookieNameEnum;
use DateInterval;
use DateTime;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class CookieHandler
{
    /**
     * @var bool
     */
    private $httpOnly;

    public function __construct(bool $httpOnly)
    {
        $this->httpOnly = $httpOnly;
    }

    /**
     * Save chosen cookie categories in cookies.
     */
    public function save(array $categories, string $key, Response $response): void
    {
        $this->saveCookie(CookieNameEnum::COOKIE_CONSENT_NAME, date('r'), $response);
        $this->saveCookie(CookieNameEnum::COOKIE_CONSENT_KEY_NAME, $key, $response);

        foreach ($categories as $category => $permitted) {
            $this->saveCookie(CookieNameEnum::getCookieCategoryName($category), $permitted, $response);
        }
    }

    /**
     * Add cookie to response headers.
     */
    protected function saveCookie(string $name, string $value, Response $response): void
    {
        $expirationDate = new DateTime();
        $expirationDate->add(new DateInterval('P1Y'));

        $response->headers->setCookie(
            new Cookie($name, $value, $expirationDate, '/', null, null, $this->httpOnly, true)
        );
    }
}
