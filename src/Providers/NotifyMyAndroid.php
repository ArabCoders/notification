<?php
/**
 * This file is part of {@see arabcoders\notification} package.
 *
 * (c) 2014-2016 Abdul.Mohsen B. A. A.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace arabcoders\notification\Providers;

use arabcoders\notification\
{
    Exceptions\APIServiceException,
    Abstracts\Provider as ProviderAbstract
};

/**
 * Notify My Android API
 *
 * @author Abdul.Mohsen B. A. A. <admin@arabcoders.org>
 */
class NotifyMyAndroid extends ProviderAbstract
{
    /**
     * @var string API url
     */
    const API_URL = 'https://www.notifymyandroid.com/publicapi/notify';

    /**
     * @var string API Verify Token
     */
    const VERIFY_URL = 'https://www.notifymyandroid.com/publicapi/verify';

    /**
     * @var int check for verify state.
     */
    private $verifyState = 0;

    public function __construct( array $options = [ ] )
    {
        if ( !function_exists( 'curl_init' ) )
        {
            throw new \RuntimeException( 'Curl extentsion is not loaded.' );
        }
    }

    public function send()
    {
        $responseData = [ ];

        $tokens = $this->getTokens();

        if ( empty( $tokens ) )
        {
            throw new APIServiceException( 'There are no tokens to send notification to.' );
        }

        foreach ( $tokens as $id => $token )
        {

            $params = [
                'apikey'      => $token,
                'description' => $this->getMessage(),
                'event'       => $this->getTitle(),
                'url'         => $this->getUrl(),
                'priority'    => $this->getPriority(),
            ];

            if ( !empty( $this->getCustom()['requestParams'] ) )
            {
                $params = array_merge( $this->getCustom()['requestParams'], $params );
            }

            $responseData[] = $this->parseResponse( $this->sendRequestAlt( self::API_URL, 'POST', $params ), $token );
        }

        return $responseData;
    }

    public function sendOnce()
    {
        $tokens = $this->getTokens();

        if ( empty( $tokens ) )
        {
            throw new APIServiceException( 'There are no tokens to send notification to.' );
        }

        $params = [
            'apikey'   => join( ',', $tokens ),
            'message'  => $this->getMessage(),
            'event'    => $this->getTitle(),
            'url'      => $this->getUrl(),
            'priority' => $this->getPriority(),
        ];

        if ( !empty( $this->getCustom()['requestParams'] ) )
        {
            $params = array_merge( $this->getCustom()['requestParams'], $params );
        }

        return $this->parseResponse( $this->sendRequestAlt( self::API_URL, 'POST', $params ) );
    }

    public function verify(): array
    {
        $this->verifyState = 1;

        $responseData = [ ];
        $tokens       = $this->getTokens();

        if ( empty( $tokens ) )
        {
            throw new \Exception( 'There are no tokens to send notification to.' );
        }

        foreach ( $tokens as $id => $token )
        {
            if ( !empty( $this->verify[$token] ) )
            {
                continue;
            }

            $params = [
                'apikey'       => $token,
                'developerkey' => $this->getKey(),
            ];

            if ( !empty( $this->getCustom()['requestParams'] ) )
            {
                $params = array_merge( $this->getCustom()['requestParams'], $params );
            }

            $response = $this->sendRequestAlt( self::VERIFY_URL, 'GET', $params );

            $responseData[] = $this->parseResponse( $response, $token );
        }

        $this->verifyState = 0;

        return $this->verify;
    }

    public function getKey(): string
    {
        if ( $this->key )
        {
            return $this->key;
        }

        return '';
    }

    public function getRemaining(): array
    {
        $this->verify();

        return $this->remaining;
    }

    public function getResetDate(): array
    {
        $this->verify();

        return $this->reset;
    }

    /**
     * getResponseObj method.
     *
     * @param array  $response Response
     * @param string $token
     *
     * @return object
     * @throws APIServiceException
     */
    private function parseResponse( $response, $token = '' )
    {
        if ( false === is_array( $response ) )
        {
            throw new APIServiceException( 'Unexpected Data return' );
        }

        if ( !( $xml = simplexml_load_string( $response['content'] ) ) )
        {
            throw new APIServiceException( '[ ' . join( ' ][ ', libxml_get_errors() ) . ' ]' );
        }

        if ( isset( $xml->success ) && $this->verifyState )
        {
            $this->verify[$token]    = (int) $xml->success->attributes()->code;
            $this->remaining[$token] = (int) $xml->success->attributes()->remaining;
            $this->reset[$token]     = (int) $xml->success->attributes()->resettimer;
        }

        $response['xml'] = $xml;

        return $response;
    }

    /**
     * sendRequestAlt
     *
     * @param string $url
     * @param string $verb
     * @param array  $params
     *
     * @return array
     * @throws APIServiceException
     */
    protected function sendRequestAlt( $url, $verb = 'POST', array $params = [ ] )
    {

        $cparams = [
            'http' => [
                'method'        => $verb,
                'ignore_errors' => true
            ]
        ];

        if ( empty( $params ) )
        {
            throw new APIServiceException( 'this api requires all calls to have params' );
        }

        $params = http_build_query( $params );
        if ( $verb == 'POST' )
        {
            $cparams['http']['header']  = 'Content-Type: application/x-www-form-urlencoded';
            $cparams['http']['content'] = $params;
        }
        else
        {
            $url .= '?' . $params;
        }

        $context = stream_context_create( $cparams );

        $fp = fopen( $url, 'rb', false, $context );

        $res = ( !$fp ) ? false : stream_get_contents( $fp );

        if ( $res === false )
        {
            throw new APIServiceException( "{$verb} {$url} failed" );
        }

        return [ 'content' => $res ];
    }
}