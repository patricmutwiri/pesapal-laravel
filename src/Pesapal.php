<?php
/*
 * Copyright (c) 2023.
 * @author Patrick Mutwiri on 1/5/23, 5:26 PM
 * @twitter https://twitter.com/patricmutwiri
 *
 */

namespace Patricmutwiri\Pesapal;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Pesapal
{
    protected string $env;
    protected string $key;
    protected string $secret;
    protected string $baseURL;
    protected string $token;
    protected string $expires;
    protected Client $client;
    protected array $headers;

    /*
     * Let's bootstrap our class.
     * */
    public function __construct(){
        $verify = false;
        $this->env = strtolower(config('pesapal.pesapal-env'));
        if ($this->env == "production"){
            $verify = true;
        }
        $this->key = config('pesapal.pesapal-key');
        $this->secret = config('pesapal.pesapal-secret');
        $this->baseURL = config('pesapal.pesapal-base')[$this->env];
        $this->headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'debug' => true
        ];
        if (!empty($this->token)){
            $tokenExpiry = strtotime(date('Y-m-d H:i:s', strtotime($this->expires) ) );
            $currentDate = strtotime(date('Y-m-d H:i:s', time()));
            if($tokenExpiry > $currentDate) {
                $this->headers['Authorization'] = 'Bearer ' .$this->token;
            } else {
                error_log(__METHOD__." expired token. Authenticate. ");
            }
        } else {
            error_log(__METHOD__." token not found. Authenticate. ");
        }
        // Init client
        $this->client = new Client([
            'base_uri' => $this->baseURL,
            'headers' => $this->headers,
            'verify' => $verify
        ]);
        error_log(__METHOD__." this element ".print_r($this, true));
    }

    /*
     * Get auth token
     * */
    public function authenticate(): ?string
    {
        $url = config('pesapal.pesapal-endpoint')['auth'];
        $params = array(
            'consumer_key' => $this->key,
            'consumer_secret' => $this->secret,
        );
        $results = null;
        try {
            $response = $this->client->request('POST', $url, ['json' => $params]);
            $results = $response->getBody()->getContents();
            error_log(__METHOD__." get token response : ".$results);
            if (!empty($results)){
                $authRes = $results;
                $this->token = $authRes->token ?? '';
                $this->expires = $authRes->expiryDate ?? '';
            }
        } catch (GuzzleException $e) {
            error_log(__METHOD__." error making request to {$url}. Details ".print_r($e, true));
        }
        return $results;
    }

    /*
     * Register IPN url
     * */
    public function IPNRegister(): ?string
    {
        $url = config('pesapal.pesapal-endpoint')['ipn-register'];
        $params = array(
            'id' => config('pesapal.pesapal-ipn'),
            'ipn_notification_type' => 'GET',
        );
        $results = null;
        try {
            $response = $this->client->request('POST', $url, ['json' => $params]);
            $results = $response->getBody()->getContents();
            error_log(__METHOD__." ipn registration response : ".$results);
        } catch (GuzzleException $e) {
            error_log(__METHOD__." exception registering IPN URLs at {$url}. Details ".print_r($e, true));
        }
        return $results;
    }

    /*
     * Make a payment request to Pesapal
     * */
    public function paymentRequest(){}

    /*
     * List registered IPN URLs
     * */
    public function IPNList(): ?string
    {
        $url = config('pesapal.pesapal-endpoint')['ipn-list'];
        $results = null;
        try {
            $response = $this->client->request('GET', $url);
            $results = $response->getBody()->getContents();
            error_log(__METHOD__." ipn urls list response : ".$results);
        } catch (GuzzleException $e) {
            error_log(__METHOD__." exception fetching registered IPN URLs from {$url}. Details ".print_r($e, true));
        }
        return $results;
    }

    /*
     * Get transaction status from pesapal using OrderTrackingId
     * */
    public function transactionStatus($id): ?string
    {
        $url = config('pesapal.pesapal-endpoint')['tsq'];
        $url .= "?orderTrackingId={$id}";
        $results = null;
        try {
            $response = $this->client->request('GET', $url);
            $results = $response->getBody()->getContents();
            error_log(__METHOD__." transaction status response : ".$results);
        } catch (GuzzleException $e) {
            error_log(__METHOD__." exception fetching transaction status from {$url}. Details ".print_r($e, true));
        }
        return $results;
    }
}
