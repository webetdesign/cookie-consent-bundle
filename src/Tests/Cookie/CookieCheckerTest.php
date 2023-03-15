<?php

declare(strict_types=1);

/*
 * This file is part of the WebEtDesign CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace WebEtDesign\CookieConsentBundle\Tests\Cookie;

use WebEtDesign\CookieConsentBundle\Cookie\CookieChecker;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class CookieCheckerTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $request;

    /**
     * @var CookieChecker
     */
    private $cookieChecker;

    public function setUp(): void
    {
        $this->request       = $this->createMock(Request::class);
        $this->cookieChecker = new CookieChecker($this->request);
    }

    /**
     * @dataProvider isCookieConsentSavedByUserDataProvider
     *
     * Test CookieChecker:isCookieConsentSavedByUser
     */
    public function testIsCookieConsentSavedByUser(array $cookies = [], bool $expected): void
    {
        $this->request->cookies = new ParameterBag($cookies);

        $this->assertSame($expected, $this->cookieChecker->isCookieConsentSavedByUser());
    }

    /**
     * Data provider for testIsCookieConsentSavedByUser.
     */
    public function isCookieConsentSavedByUserDataProvider(): array
    {
        return [
            [['Cookie_Consent' => date('r')], true],
            [['Cookie_Consent' => 'true'], true],
            [['Cookie_Consent' => ''], true],
            [['Cookie Consent' => 'true'], false],
            [['CookieConsent' => 'true'], false],
            [[], false],
        ];
    }

    /**
     * @dataProvider isCategoryAllowedByUserDataProvider
     *
     * Test CookieChecker:isCategoryAllowedByUser
     */
    public function testIsCategoryAllowedByUser(array $cookies = [], string $category, bool $expected): void
    {
        $this->request->cookies = new ParameterBag($cookies);

        $this->assertSame($expected, $this->cookieChecker->isCategoryAllowedByUser($category));
    }

    /**
     * Data provider for testIsCategoryAllowedByUser.
     */
    public function isCategoryAllowedByUserDataProvider(): array
    {
        return [
            [['Cookie_Category_analytics' => 'true'], 'analytics', true],
            [['Cookie_Category_marketing' => 'true'], 'marketing', true],
            [['Cookie_Category_analytics' => 'false'], 'analytics', false],
            [['Cookie Category analytics' => 'true'], 'analytics', false],
            [['Cookie_Category_Analytics' => 'true'], 'analytics', false],
            [['analytics' => 'true'], 'analytics', false],
        ];
    }
}
