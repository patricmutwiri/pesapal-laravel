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
use Illuminate\Support\Facades\DB;

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
     * Get authentication token from Pesapal
     * */
    public function authenticate()
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
            $results = json_decode($response->getBody()->getContents());
            if (!empty($results)){
                $authRes = $results;
                $this->token = $authRes->token ?? '';
                $this->expires = $authRes->expiryDate ?? '';
                $this->headers['Authorization'] = 'Bearer '.$this->token;
            }
        } catch (GuzzleException $e) {
            error_log(__METHOD__." error making request to {$url}. Details ".print_r($e, true));
        }
        error_log(__METHOD__." response from {$url} : ".json_encode($results));
        return $results;
    }

    /*
     * Register IPN url
     * */
    public function IPNRegister($ipnURL="", $method="GET")
    {
        $this->authenticate();
        $url = config('pesapal.pesapal-endpoint')['ipn-register'];
        error_log(__METHOD__." request endpoint {$url}");
        $ipn_url = config('pesapal.pesapal-ipn');
        if (!empty($ipnURL)) {
            $ipn_url = $ipnURL;
        }
        $params = array(
            'url' => $ipn_url,
            'ipn_notification_type' => $method,
        );
        error_log(__METHOD__." register IPN params ".json_encode($params));
        $results = [];
        try {
            $response = $this->client->request('POST', $url, ['json' => $params, 'headers' => $this->headers]);
            $results = json_decode($response->getBody()->getContents());
            self::saveIPN($params, $results);
        } catch (GuzzleException $e) {
            error_log(__METHOD__." exception registering IPN URLs at {$url}. Details ".print_r($e, true));
        }
        error_log(__METHOD__." response from {$url} : ".json_encode($results));
        return $results;
    }

    /*
     * Make a payment request to Pesapal
     * */
    public function paymentRequest($params)
    {
        $record_id = self::savePaymentRequest($params);
        $this->authenticate();
        $url = config('pesapal.pesapal-endpoint')['payment-request'];
        error_log(__METHOD__." request endpoint {$url}");
        $results = [];
        $error = null;
        try {
            $response = $this->client->request('POST', $url, ['json' => $params, 'headers' => $this->headers]);
            $results = json_decode($response->getBody()->getContents());
        } catch (GuzzleException $e){
            error_log(__METHOD__." exception making a payment request to {$url}. Details ".print_r($e, true));
            $error = $e->getMessage();
        }
        error_log(__METHOD__." response from {$url} : ".json_encode($results));
        try {
            self::updatePaymentRequest($record_id, $results, $error);
        } catch (\Exception $e){
            error_log(__METHOD__." exception updating transaction {$record_id} with results. Error: ".$e->getMessage());
        }
        return $results;
    }

    /*
     * List registered IPN URLs
     * */
    public function IPNList(): ?array
    {
        $this->authenticate();
        $url = config('pesapal.pesapal-endpoint')['ipn-list'];
        error_log(__METHOD__." request endpoint {$url}");
        $results = [];
        try {
            $response = $this->client->request('GET', $url, ['headers' => $this->headers]);
            $results = json_decode($response->getBody()->getContents());
        } catch (GuzzleException $e) {
            error_log(__METHOD__." exception fetching registered IPN URLs from {$url}. Details ".print_r($e, true));
        }
        error_log(__METHOD__." response from {$url} : ".json_encode($results));
        return $results;
    }

    /*
     * Get transaction status from pesapal using OrderTrackingId
     * */
    public function transactionStatus($id)
    {
        $this->authenticate();
        $url = config('pesapal.pesapal-endpoint')['tsq'];
        $url .= "?orderTrackingId={$id}";
        $results = [];
        try {
            $response = $this->client->request('GET', $url, ['headers' => $this->headers]);
            $results = json_decode($response->getBody()->getContents());
            self::updateTransactionStatus($id, $results);
        } catch (GuzzleException $e) {
            error_log(__METHOD__." exception fetching transaction status from {$url}. Details ".print_r($e, true));
        }
        error_log(__METHOD__." response from {$url} : ".json_encode($results));
        return $results;
    }

    private function recordTRX($payload){}

    private function updateDB($payload){}

    /*
     * Update the payment request after status check from Pesapal
     * */
    private static function updateTransactionStatus($orderTrackingId, $results) {
        try {
            $transactions = DB::select('select * from pesapal_transactions where order_tracking_id = :id', ['id' => $orderTrackingId]);
            foreach ($transactions as $transaction){
                switch ($results->status_code) {
                    case 0:
                        $status = 'INVALID';
                        break;
                    case 1:
                        $status = 'COMPLETED';
                        break;
                    case 2:
                        $status = 'FAILED';
                        break;
                    case 3:
                        $status = 'REVERSED';
                        break;
                    default:
                        $status = 'PROCESSING';
                }
                $notes =  sprintf("%s#%s", $transaction->notes, $results->description);
                $description =  sprintf("%s#%s", $transaction->description, $results->message);
                $toUpdate = array(
                    'payment_method' => $results->payment_method ?? '',
                    'notes' => $notes,
                    'created_date' => $results->created_date,
                    'payment_account' => $results->payment_account,
                    'confirmation_code' => $results->confirmation_code,
                    'payment_status_description' => $results->payment_status_description,
                    'description' => $description,
                    'status' => $status,
                    'status_code' => $results->status,
                    'trx_status_code' => $results->status_code,
                    'payment_status_code' => $results->payment_status_code,
                );
                DB::table('pesapal_transactions')
                    ->where('id', $transaction->id)
                    ->update($toUpdate);
            }
        } catch (\Exception $e){
            error_log(__METHOD__.' exception updating transaction status! Error: '.$e->getMessage());
        }
    }
    /*
    * Update payment request after posting with status from Pesapal
    * */
    private static function updatePaymentRequest($id, $results, $error)
    {
        $status = 'PROCESSING';
        if (!is_null($error) || !is_null($results->error)){
            $status = 'FAILED';
        }

        $toUpdate = [
            'order_tracking_id' => $results->order_tracking_id ?? null,
            'merchant_reference' => $results->merchant_reference ?? null,
            'redirect_url' => $results->redirect_url ?? null,
            'errors' => json_encode($results->error) ?? null,
            'status_code' => $results->status ?? null,
            'status' => $status
        ];

        try {
            DB::table('pesapal_transactions')
                ->where('id', $id)
                ->update($toUpdate);
        } catch (\Exception $e){
            error_log(__METHOD__." exception updating payment request " . $e->getMessage());
        }
    }

    /*
     * Save payment request for reference.
     * */
    public static function savePaymentRequest($data): ?int
    {
         $record_id = null;
         $toSave = array(
             'our_ref' => $data['id'],
             'payment_method' => '',
             'order_tracking_id' => '',
             'merchant_reference' => '',
             'redirect_url' => '',
             'notes' => $data['description'],
             'confirmation_code' => '',
             'payment_status_description' => 'NEW Transaction',
             'description' => $data['description'],
             'reference' => $data['id'],
             'amount' => $data['amount'],
             'currency' => $data['currency'],
             'status' => 'NEW',
             'email' => $data['billing_address']['email_address'],
             'phone' => $data['billing_address']['phone_number'],
             'first_name' => $data['billing_address']['first_name'],
             'last_name' => $data['billing_address']['last_name'],
             'narration' => $data['description'],
             'ipn_id' => $data['notification_id'],
             'added_by' => auth()->user()->id ?? 0,
         );
         error_log(__METHOD__." save payment request ".json_encode($toSave));
         try {
             $record_id = DB::table('pesapal_transactions')->insertGetId($toSave);
         } catch (\Exception $e) {
             error_log(__METHOD__." error saving payment request " . $e->getMessage());
         }
         return $record_id;
     }

     /*
      * Record IPN URL registrations
      * */
    public static function saveIPN($params, $results){
        try {
            $ipn = array(
                'ipn_id' => $results->ipn_id,
                'url' => $params['url'],
                'http_method' => $params['ipn_notification_type'],
                'created_date' => date('Y-m-d H:i:s',time()),
                'created_by' => auth()->user()->id ?? 0,
                'status' => $results->status,
                'error' => json_encode($results->error)
            );
            DB::table('pesapal_ipn_urls')->insert($ipn);
        } catch (\Exception $e){
            error_log(__METHOD__." error saving IPN URL " . $e->getMessage());
        }
    }
}