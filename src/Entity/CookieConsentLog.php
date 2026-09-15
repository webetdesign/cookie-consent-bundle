<?php

declare(strict_types=1);

/*
 * This file is part of the WebEtDesign CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace WebEtDesign\CookieConsentBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'ch_cookieconsent_log')]
class CookieConsentLog
{
    /**
     * @var int
     */
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    protected $id;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    protected $ipAddress;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    protected $cookieConsentKey;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    protected $cookieName;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    protected $cookieValue;

    /**
     * @var DateTime
     */
    #[ORM\Column(type: 'datetime')]
    protected $timestamp;

    public function getId(): int
    {
        return $this->id;
    }

    public function setIpAddress(string $ipAddress): self
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    public function setCookieConsentKey(string $cookieConsentKey): self
    {
        $this->cookieConsentKey = $cookieConsentKey;

        return $this;
    }

    public function getCookieConsentKey(): string
    {
        return $this->cookieConsentKey;
    }

    public function setCookieName(string $cookieName): self
    {
        $this->cookieName = $cookieName;

        return $this;
    }

    public function getCookieName(): string
    {
        return $this->cookieName;
    }

    public function setCookieValue(string $cookieValue): self
    {
        $this->cookieValue = $cookieValue;

        return $this;
    }

    public function getCookieValue(): string
    {
        return $this->cookieValue;
    }

    public function setTimestamp(DateTime $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getTimestamp(): DateTime
    {
        return $this->timestamp;
    }
}
