<?php
/**
 * This file is part of {@see arabcoders\notification} package.
 *
 * (c) 2014-2016 Abdul.Mohsen B. A. A.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace arabcoders\notification\Abstracts;

use arabcoders\notification\
{
    interfaces\Provider as ProviderInterface
};

/**
 * Provider Abstract
 *
 * @author Abdul.Mohsen B. A. A. <admin@arabcoders.org>
 */
abstract class Provider implements ProviderInterface
{
    const VERSION = '1.0.0';

    /**
     * @var string  Developer/API key.
     */
    protected $key;

    /**
     * @var array  Tokens.
     */
    protected $tokens = [ ];

    /**
     * @var array  Tokens verification.
     */
    protected $verify = [ ];

    /**
     * @var array  Tokens Remaining.
     */
    protected $remaining = [ ];

    /**
     * @var array  Tokens reset Date.
     */
    protected $reset = [ ];

    /**
     * @var string  Message Priority
     */
    protected $priority;

    /**
     * @var array  Provider Custom Options.
     */
    protected $custom = [ ];

    /**
     * @var string  notification text.
     */
    protected $message;

    /**
     * @var string notification title.
     */
    protected $title;

    /**
     * @var string notification url.
     */
    protected $url;

    /**
     * Provider Constructor.
     *
     * @param array $options Provider Options.
     */
    abstract function __construct( array $options = [ ] );

    public function setKey( string $key ): ProviderInterface
    {
        $this->key = $key;

        return $this;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setTokens( array $tokens ): ProviderInterface
    {
        $this->tokens = $tokens;

        return $this;
    }

    public function getTokens(): array
    {
        return $this->tokens;
    }

    public function setTitle( string $title ): ProviderInterface
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setPriority( $priority ): ProviderInterface
    {
        $this->priority = $priority;

        return $this;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function setMessage( string $message ): ProviderInterface
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setUrl( string $url ): ProviderInterface
    {
        $this->url = $url;

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setCustom( array $custom = [ ] ): ProviderInterface
    {
        $this->custom = $custom;

        return $this;
    }

    public function getCustom(): array
    {
        return $this->custom;
    }

    public function setVerify( $verify ): ProviderInterface
    {
        $this->verify = $verify;

        return $this;
    }

    public function setRemaining( $remaining ): ProviderInterface
    {
        $this->remaining = $remaining;

        return $this;
    }

    public function getRemaining(): array
    {
        return $this->remaining;
    }

    public function setResetDate( $reset ): ProviderInterface
    {
        $this->reset = $reset;

        return $this;
    }

    public function getResetDate(): array
    {
        return $this->reset;
    }

    /**
     * Send Request.
     *
     * @param  string $url     api url
     * @param  string $method  Request type
     * @param  array  $options request options.
     *
     * @return array
     */
    protected function sendRequest( $url, $method = 'POST', array $options = [ ] )
    {

        $curl = curl_init( $url );

        curl_setopt( $curl, CURLOPT_HEADER, 0 );

        $userAgent = ( !empty( $options['userAgent'] ) ) ? $options['userAgent'] : 'Notification/' . self::VERSION;

        curl_setopt( $curl, CURLOPT_USERAGENT, $userAgent );
        curl_setopt( $curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, !empty( $options['VerifySSL'] ) );

        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );

        if ( $method == 'POST' )
        {
            curl_setopt( $curl, CURLOPT_POST, true );

            if ( !empty( $options['params'] ) )
            {
                curl_setopt( $curl, CURLOPT_POSTFIELDS, $options['params'] );
            }
        }

        if ( !empty( $options['auth'] ) && is_array( $options['auth'] ) )
        {
            curl_setopt( $curl, CURLOPT_USERPWD, $options['auth']['user'] . ':' . $options['auth']['password'] );
        }

        if ( !empty( $options['proxy'] ) )
        {
            curl_setopt( $curl, CURLOPT_HTTPPROXYTUNNEL, true );
            curl_setopt( $curl, CURLOPT_PROXY, $options['proxy'] );

            if ( !empty( $options['proxyPassword'] ) )
            {
                curl_setopt( $curl, CURLOPT_PROXYUSERPWD, $options['proxyPassword'] );
            }
        }

        $postBack = [
            'content' => curl_exec( $curl ),
            'info'    => curl_getinfo( $curl ),
        ];

        curl_close( $curl );

        return $postBack;
    }
}