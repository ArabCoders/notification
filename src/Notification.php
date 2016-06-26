<?php
/**
 * This file is part of {@see arabcoders\notification} package.
 *
 * (c) 2014-2016 Abdul.Mohsen B. A. A.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace arabcoders\notification;

use arabcoders\notification\
{
    Interfaces\Provider,
    Interfaces\Notification as NotificationInterface
};

/**
 * Notification Manager.
 *
 * @author  Abdul.Mohsen B. A. A. <admin@arabcoders.org>
 */
class Notification implements NotificationInterface
{
    /**
     * @var Provider
     */
    private $provider;

    public function __construct( Provider $provider, array $options = [ ] )
    {
        $this->provider = $provider;
    }

    public function setKey( string $apikey ): Provider
    {
        $this->provider->setKey( $apikey );

        return $this;
    }

    public function getKey(): string
    {
        return $this->provider->getKey();
    }

    public function setTokens( array $tokens ): Provider
    {
        $this->provider->setTokens( $tokens );

        return $this;
    }

    public function getTokens(): array
    {
        return $this->provider->getTokens();
    }

    public function setTitle( string $title ): Provider
    {
        $this->provider->setTitle( $title );

        return $this;
    }

    public function getTitle(): string
    {
        return $this->provider->getTitle();
    }

    public function setPriority( $priority ): Provider
    {
        $this->provider->setPriority( $priority );

        return $this;
    }

    public function getPriority(): int
    {
        return $this->provider->getPriority();
    }

    public function setMessage( string $message ): Provider
    {
        $this->provider->setMessage( $message );

        return $this;
    }

    public function getMessage(): string
    {
        return $this->provider->getMessage();
    }

    public function setUrl( string $url ): Provider
    {
        $this->provider->setUrl( $url );

        return $this;
    }

    public function getUrl(): string
    {
        return $this->provider->getUrl();
    }

    public function setCustom( array $custom = [ ] ): Provider
    {
        $this->provider->setCustom( $custom );

        return $this;
    }

    public function getCustom(): array
    {
        return $this->provider->getCustom();
    }

    public function send()
    {
        return $this->provider->send();
    }

    public function setVerify( $verify ): Provider
    {
        $this->provider->setVerify( $verify );

        return $this;
    }

    public function verify(): array
    {
        return $this->provider->verify();
    }

    public function setRemaining( $remaining ): Provider
    {
        $this->provider->setRemaining( $remaining );

        return $this;
    }

    public function getRemaining(): array
    {
        return $this->provider->getRemaining();
    }

    public function setResetDate( $reset ): Provider
    {
        $this->provider->setResetDate( $reset );

        return $this;
    }

    public function getResetDate(): array
    {
        return $this->provider->getResetDate();
    }
}