<?php

declare(strict_types=1);

/*
 * This file is part of the WebEtDesign CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace WebEtDesign\CookieConsentBundle\Tests\Twig;

use WebEtDesign\CookieConsentBundle\Twig\WDCookieConsentTwigExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Twig\AppVariable;
use Symfony\Component\HttpFoundation\Request;

class WDCookieConsentTwigExtensionTest extends TestCase
{
    /**
     * @var WDCookieConsentTwigExtension
     */
    private $WDCookieConsentTwigExtension;

    public function setUp(): void
    {
        $this->WDCookieConsentTwigExtension = new WDCookieConsentTwigExtension();
    }

    public function testGetFunctions(): void
    {
        $functions = $this->WDCookieConsentTwigExtension->getFunctions();

        $this->assertCount(2, $functions);
        $this->assertSame('WDCookieConsent_isCookieConsentSavedByUser', $functions[0]->getName());
        $this->assertSame('WDCookieConsent_isCategoryAllowedByUser', $functions[1]->getName());
    }

    public function testIsCookieConsentSavedByUser(): void
    {
        $request  = new Request();

        $appVariable = $this->createMock(AppVariable::class);
        $appVariable
            ->expects($this->once())
            ->method('getRequest')
            ->wilLReturn($request);

        $context = ['app' => $appVariable];
        $result  = $this->WDCookieConsentTwigExtension->isCookieConsentSavedByUser($context);

        $this->assertSame($result, false);
    }

    public function testIsCategoryAllowedByUser(): void
    {
        $request  = new Request();

        $appVariable = $this->createMock(AppVariable::class);
        $appVariable
            ->expects($this->once())
            ->method('getRequest')
            ->wilLReturn($request);

        $context = ['app' => $appVariable];
        $result  = $this->WDCookieConsentTwigExtension->isCategoryAllowedByUser($context, 'analytics');

        $this->assertSame($result, false);
    }
}
