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
 * Push Over Notification API.
 *
 * @author Abdul.Mohsen B. A. A. <admin@arabcoders.org>
 */
class PushOver extends ProviderAbstract
{
    /**
     * @var string API url
     */
    const API_URL = 'https://api.pushover.net/1/messages.json';

    /**
     * @var string API Verify Token
     */
    const VERIFY_URL = 'https://api.pushover.net/1/users/validate.json';

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
                'user'     => $token,
                'token'    => $this->getKey(),
                'message'  => $this->getMessage(),
                'title'    => $this->getTitle(),
                'url'      => $this->getUrl(),
                'priority' => $this->getPriority(),
            ];

            if ( !empty( $this->getCustom()['requestParams'] ) )
            {
                $params = array_merge( $this->getCustom()['requestParams'], $params );
            }

            $response = $this->sendRequest( self::API_URL, 'POST', [ 'params' => $params ] );

            $responseData[] = $this->parseResponse( $response );
        }

        return $responseData;
    }

    public function verify(): array
    {
        $tokens = $this->getTokens();

        if ( empty( $tokens ) )
        {
            throw new APIServiceException( 'There are no tokens to send notification to.' );
        }

        foreach ( $tokens as $id => $token )
        {
            if ( !empty( $this->verify[$token] ) )
            {
                continue;
            }

            $params = [
                'user'  => $token,
                'token' => $this->getKey(),
            ];

            if ( !empty( $this->getCustom()['requestParams'] ) )
            {
                $params = array_merge( $this->getCustom()['requestParams'], $params );
            }

            $response = $this->sendRequest( self::VERIFY_URL, 'POST', [ 'params' => $params ] );

            if ( !( $response = json_decode( $response['content'] ) ) )
            {
                throw new APIServiceException( json_last_error_msg() );
            }

            $this->verify[$token]    = ( property_exists( $response, 'status' ) ) ? $response->status : 0;
            $this->remaining[$token] = ( $this->verify[$token] ) ? 999 : -1;
        }

        return $this->verify;
    }

    public function getRemaining(): array
    {
        $this->verify();

        return $this->remaining;
    }

    /**
     * Parse Response.
     *
     * @param string $response $response Response
     *
     * @return array
     */
    private function parseResponse( $response )
    {
        if ( false === is_array( $response ) )
        {
            throw new APIServiceException( 'Unexpected Data return' );
        }

        if ( !( $json = json_decode( $response['content'] ) ) )
        {
            throw new APIServiceException( json_last_error_msg() );
        }

        if ( property_exists( $json, 'user' ) && $json->user == 'invalid' )
        {
            throw new APIServiceException( sprintf( 'API key is invalid Errors [ %s ]', join( ' ] [ ', $json->errors ) ) );
        }

        if ( property_exists( $json, 'token' ) && $json->token == 'invalid' )
        {
            throw new APIServiceException( 'Token is invalid' );
        }


        $response['json'] = $json;

        return $response;
    }
}