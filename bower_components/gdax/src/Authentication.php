<?php

namespace Hellovoid\Gdax;


class Authentication
{
    private $apiKey;
    private $apiSecret;
    private $apiPassphrase;

    /**
     * Authentication constructor.
     * @param $apiKey
     * @param $apiSecret
     * @param $apiPassphrase
     */
    public function __construct($apiKey, $apiSecret, $apiPassphrase)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->apiPassphrase = $apiPassphrase;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getApiSecret()
    {
        return $this->apiSecret;
    }

    /**
     * @param $apiSecret
     */
    public function setApiSecret($apiSecret)
    {
        $this->apiSecret = $apiSecret;
    }

    public function getApiPassphrase()
    {
        return $this->apiPassphrase;
    }

    /**
     * @param $apiPassphrase
     */
    public function setApiPassphrase($apiPassphrase)
    {
        $this->apiPassphrase = $apiPassphrase;
    }

    /**
     * @param $method
     * @param $path
     * @param $body
     * @return array
     */
    public function getRequestHeaders($method, $path, $body)
    {
        $timestamp = $this->getTimestamp();
        $signature = $this->getHash('sha256', $timestamp.$method.$path.$body, $this->apiSecret);

        return [
            'CB-ACCESS-KEY'        => $this->apiKey,
            'CB-ACCESS-SIGN'       => $signature,
            'CB-ACCESS-TIMESTAMP'  => $timestamp,
            'CB-ACCESS-PASSPHRASE' => $this->apiPassphrase,
        ];
    }

    protected function getTimestamp()
    {
        return time();
    }

    /**
     * @param $algo
     * @param $data
     * @param $secret
     * @return string
     */
    protected function getHash($algo, $data, $secret)
    {
        return base64_encode(hash_hmac($algo, $data, base64_decode($secret), true));
    }
}
