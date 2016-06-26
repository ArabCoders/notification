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
 * Prowl Notification API
 *
 * @author Abdul.Mohsen B. A. A. <admin@arabcoders.org>
 */
class Prowl extends ProviderAbstract
{
    /**
     * @var string API url
     */
    const API_URL = 'https://api.prowlapp.com/publicapi/add';

    /**
     * @var string API Verify Token
     */
    const VERIFY_URL = 'https://api.prowlapp.com/publicapi/verify?apikey=%s&providerkey=%s';

    /**
     * @var array
     */
    private $status = [ ];

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
                'providerkey' => $this->getKey(),
                'description' => trim( $this->getMessage() ) . PHP_EOL . PHP_EOL,
                'event'       => $this->getTitle(),
                'url'         => $this->getUrl(),
                'priority'    => $this->getPriority(),
            ];

            if ( !empty( $this->getCustom()['requestParams'] ) )
            {
                $params = array_merge( $this->getCustom()['requestParams'], $params );
            }

            $responseData[] = $this->parseResponse( $this->sendRequest( self::API_URL, 'POST', [ 'params' => $params ] ), $token );
        }

        return $responseData;
    }

    public function verify(): array
    {
        $tokens = $this->getTokens();

        if ( empty( $tokens ) )
        {
            throw new \Exception( 'There are no tokens to verify' );
        }

        foreach ( $tokens as $id => $token )
        {
            if ( array_key_exists( $token, $this->verify ) )
            {
                continue;
            }

            $this->parseResponse( $this->sendRequest( sprintf( self::VERIFY_URL, $token, $this->getKey() ), 'GET' ), $token );
        }

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
     * Parse Response.
     *
     * @param array  $response Response
     * @param string $token
     *
     * @return array
     * @throws APIServiceException
     */
    private function parseResponse( $response, $token )
    {
        if ( false === is_array( $response ) )
        {
            throw new APIServiceException( 'Unexpected data return' );
        }

        if ( !( $xml = simplexml_load_string( $response['content'] ) ) )
        {
            throw new APIServiceException( '[ ' . join( ' ][ ', libxml_get_errors() ) . ' ]' );
        }

        if ( isset( $xml->error ) )
        {
            if ( !empty( $xml->error->attributes()->code ) )
            {
                $this->status[$token] = (int) $xml->error->attributes()->code;
            }

            if ( !empty( $xml->error->attributes()->remaining ) )
            {
                $this->remaining[$token] = (int) $xml->error->attributes()->remaining;
            }

            if ( !empty( $xml->error->attributes()->resetdate ) )
            {
                $this->reset[$token] = (int) $xml->error->attributes()->resetdate;
            }
        }

        if ( isset( $xml->success ) )
        {
            $this->verify[$token]    = (int) $xml->success->attributes()->code;
            $this->remaining[$token] = (int) $xml->success->attributes()->remaining;
            $this->reset[$token]     = (int) $xml->success->attributes()->resetdate;
        }

        $response['xml'] = $xml;

        return $response;
    }
}