<?php

declare(strict_types=1);

/*
 * This file is part of the WebEtDesign CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace WebEtDesign\CookieConsentBundle\Tests\Form;

use WebEtDesign\CookieConsentBundle\Cookie\CookieChecker;
use WebEtDesign\CookieConsentBundle\Form\CookieConsentType;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class CookieConsentTypeTest extends TypeTestCase
{
    /**
     * @var MockObject
     */
    private $cookieChecker;

    public function setUp(): void
    {
        $this->cookieChecker = $this->createMock(CookieChecker::class);

        parent::setUp();
    }

    /**
     * Test submit of CookieConsentType.
     */
    public function testSubmitValidDate(): void
    {
        $formData = [
            'analytics'    => 'true',
            'tracking'     => 'true',
            'marketing'    => 'false',
        ];

        $form = $this->factory->create(CookieConsentType::class);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertSame($formData, $form->getData());
    }

    protected function getExtensions(): array
    {
        $type = new CookieConsentType($this->cookieChecker, ['analytics', 'tracking', 'marketing'], false);

        return [
            new PreloadedExtension([$type], []),
        ];
    }
}
