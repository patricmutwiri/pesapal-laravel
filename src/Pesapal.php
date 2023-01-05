<?php
/*
 * Copyright (c) 2023.
 * @author Patrick Mutwiri on 1/5/23, 4:12 PM
 * @twitter https://twitter.com/patricmutwiri
 *
 */

namespace Patricmutwiri\Pesapal;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Pesapal
{
    protected $env = null;
    protected $key = null;
    protected $secret = null;
    protected $baseURL = null;
    protected $token = null;
    protected $client = null;
    protected $headers = [];

    public function __construct(){
        $this->env = config('pesapal.pesapal-env');
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
        ]);
        error_log(__METHOD__." this element ".print_r($this, true));
    }

    public function authenticate(): ?\Psr\Http\Message\ResponseInterface
    {
        $url = config('pesapal.pesapal-endpoint')['auth'];
        $params = array(
            'consumer_key' => $this->key,
            'consumer_secret' => $this->secret,
        );
        $response = null;
        try {
            $response = $this->client->request('POST', $url, ['json' => $params]);
            $results = $response->getBody()->getContents();
            if (!empty($results)){
                $authRes = $results;
                $this->token = $authRes->token ?? '';
            }
        } catch (GuzzleException $e) {
            error_log(__METHOD__." error making request to {$url}. Details ".print_r($e, true));
        }
        return $response;
    }

    public function IPNRegister(){

    }

    public function paymentRequest(){}

    public function IPNList(){}

    public function transactionStatus(){}
}
