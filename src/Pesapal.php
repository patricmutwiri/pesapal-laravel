<?php
/*
 * Copyright (c) 2023.
 * @author Patrick Mutwiri on 1/7/23, 5:37 PM
 * @twitter https://twitter.com/patric_mutwiri
 *
 */

namespace Patricmutwiri\Pesapal;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Pesapal
{
    protected string $env="sandbox";
    protected string $key;
    protected string $secret;
    protected string $baseURL;
    protected ?string $token=null;
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
        
        // Init client
        $this->client = new Client([
            'base_uri' => $this->baseURL,
            'headers' => $this->headers,
            'verify' => $verify
        ]);
        error_log(__METHOD__." base url ".$this->baseURL);
    }

    /*
     * Get auth token
     * */
    public function authenticate(): ?string
    {
        $url = config('pesapal.pesapal-endpoint')['auth'];
        error_log(__METHOD__." request endpoint {$url}");
        $params = array(
            'consumer_key' => $this->key,
            'consumer_secret' => $this->secret,
        );
        $results = [];
        try {
            $response = $this->client->request('POST', $url, ['json' => $params, 'headers' => $this->headers]);
            $results = $response->getBody()->getContents();
            if (!empty($results)){
                $authRes = $results;
                $this->token = $authRes->token ?? '';
                $this->expires = $authRes->expiryDate ?? '';
                $this->headers['Authorization'] = 'Bearer '.$this->token;
            }
        } catch (GuzzleException $e) {
            error_log(__METHOD__." error making request to {$url}. Details ".print_r($e, true));
        }
        error_log(__METHOD__." response from {$url} : ".$results);
        return $results;
    }

    /*
     * Register IPN url
     * */
    public function IPNRegister(): ?string
    {
        $this->authenticate();
        $url = config('pesapal.pesapal-endpoint')['ipn-register'];
        error_log(__METHOD__." request endpoint {$url}");
        $params = array(
            'id' => config('pesapal.pesapal-ipn'),
            'ipn_notification_type' => 'GET',
        );
        $results = [];
        try {
            $response = $this->client->request('POST', $url, ['json' => $params, 'headers' => $this->headers]);
            $results = $response->getBody()->getContents();
        } catch (GuzzleException $e) {
            error_log(__METHOD__." exception registering IPN URLs at {$url}. Details ".print_r($e, true));
        }
        error_log(__METHOD__." response from {$url} : ".$results);
        return $results;
    }

    /*
     * Make a payment request to Pesapal
     * */
    public function paymentRequest($params): ?string
    {
        $this->authenticate();
        $url = config('pesapal.pesapal-endpoint')['payment-request'];
        error_log(__METHOD__." request endpoint {$url}");
        $results = [];
        try {
            $response = $this->client->request('POST', $url, ['json' => $params, 'headers' => $this->headers]);
            $results = $response->getBody()->getContents();
        } catch (GuzzleException $e){
            error_log(__METHOD__." exception making a payment request to {$url}. Details ".print_r($e, true));
        }
        error_log(__METHOD__." response from {$url} : ".$results);
        return $results;
    }

    /*
     * List registered IPN URLs
     * */
    public function IPNList(): ?string
    {
        $this->authenticate();
        $url = config('pesapal.pesapal-endpoint')['ipn-list'];
        error_log(__METHOD__." request endpoint {$url}");
        $results = [];
        try {
            $response = $this->client->request('GET', $url, ['headers' => $this->headers]);
            $results = $response->getBody()->getContents();
        } catch (GuzzleException $e) {
            error_log(__METHOD__." exception fetching registered IPN URLs from {$url}. Details ".print_r($e, true));
        }
        error_log(__METHOD__." response from {$url} : ".$results);
        return $results;
    }

    /*
     * Get transaction status from pesapal using OrderTrackingId
     * */
    public function transactionStatus($id): ?string
    {
        $this->authenticate();
        $url = config('pesapal.pesapal-endpoint')['tsq'];
        $url .= "?orderTrackingId={$id}";
        error_log(__METHOD__." request endpoint {$url}");
        $results = [];
        try {
            $response = $this->client->request('GET', $url, ['headers' => $this->headers]);
            $results = $response->getBody()->getContents();
        } catch (GuzzleException $e) {
            error_log(__METHOD__." exception fetching transaction status from {$url}. Details ".print_r($e, true));
        }
        error_log(__METHOD__." response from {$url} : ".$results);
        return $results;
    }

    private function recordTRX($payload){}

    private function updateDB($payload){}
}
