<?php
/**
 * This file is part of {@see arabcoders\notification} package.
 *
 * (c) 2014-2016 Abdul.Mohsen B. A. A.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace arabcoders\notification\Interfaces;

/**
 * Provider Interface.
 *
 * @author Abdul.Mohsen B. A. A. <admin@arabcoders.org>
 */

interface Provider
{
    /**
     * Set API key.
     *
     * @param string $apikey Developer/API key.
     *
     * @return Provider
     **/
    public function setKey( string $apikey ): Provider;

    /**
     * get API key.
     *
     * @return string
     **/
    public function getKey(): string;

    /**
     * Set Tokens
     *
     * @param   array $tokens one or array of Tokens.
     *
     * @return Provider
     **/
    public function setTokens( array $tokens ): Provider;

    /**
     * Get Tokens
     *
     * @return array
     **/
    public function getTokens(): array;

    /**
     * Set notification title
     *
     * @param string $title
     *
     * @return Provider
     **/
    public function setTitle( string $title ): Provider;

    /**
     * Get notification title
     *
     * @return string
     **/
    public function getTitle(): string;

    /**
     * Set notification url
     *
     * @param string $url
     *
     * @return Provider
     **/
    public function setUrl( string $url ): Provider;

    /**
     * Get notification url
     *
     * @return string
     **/
    public function getUrl(): string;


    /**
     * Set Message Priority.
     *
     * @param int $priority
     *
     * @return Provider
     **/
    public function setPriority( $priority ): Provider;

    /**
     * Get Message Priority.
     *
     * @return int
     **/
    public function getPriority(): int;

    /**
     * Set Message.
     *
     * @param string $message
     *
     * @return Provider
     **/
    public function setMessage( string $message ): Provider;

    /**
     * Get Message.
     *
     * @return string
     **/
    public function getMessage(): string;

    /**
     * Set Custom.
     *
     * @param array $custom custom array for provider.
     *
     * @return Provider
     **/
    public function setCustom( array $custom = [ ] ): Provider;

    /**
     * Get Custom.
     *
     * @return array
     **/
    public function getCustom(): array;

    /**
     * Send Notification
     *
     * @return mixed
     **/
    public function send();

    /**
     * Set Verified Tokens
     *
     * @param array $verify
     *
     * @return Provider
     **/
    public function setVerify( $verify ): Provider;

    /**
     * Verify Tokens
     *
     * @return array
     **/
    public function verify(): array;

    /**
     * set token Remaining API calls.
     *
     * @param array $remaining
     *
     * @return Provider
     **/
    public function setRemaining( $remaining ): Provider;


    /**
     * Get Remaining API calls.
     *
     * @return array
     **/
    public function getRemaining(): array;

    /**
     * Get Reset Date for Tokens.
     *
     * @param array $reset
     *
     * @return Provider
     **/
    public function setResetDate( $reset ): Provider;

    /**
     * Get Reset Date for Tokens.
     *
     * @return array
     **/
    public function getResetDate(): array;
}