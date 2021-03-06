<?php


namespace Thinkific;

use Http\Client\Exception;
use stdClass;

class ThinkificWebhooks extends ThinkificResource
{
    /**
     * Returns a list of webhooks currently set up on the account
     *
     * IMPORTANT: Not currently operational as requires API V2 endpoint
     *
     * @param array $options
     * @return stdClass
     */
    public function list($options = []) {

        return $this->client->get('webhooks', $options);
    }

    /**
     * Validates a webhook
     *
     * @see    https://developers.thinkific.com/api/api-documentation/
     * @param  string $body JSON Body from Webhook
     * @param  string|array $header
     * @return bool
     * @throws Exception
     */
    public function validate($body, $header)
    {
        /* We can accept an array or a string */
        if(is_array($header)) {

            $header = isset($header['HTTP_X_THINKIFIC_HMAC_SHA256']) ? $header['HTTP_X_THINKIFIC_HMAC_SHA256'] : false;
        }

        /* We need the header for comparison */
        if(!$header) return false;

        $calculated = hash_hmac('sha256', $body, $this->client->apiToken, false);

        if($calculated === $header) return true;

        return false;

    }
}
