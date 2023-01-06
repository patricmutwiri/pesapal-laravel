<?php
/*
 * Copyright (c) 2023.
 * @author Patrick Mutwiri on 1/6/23, 8:36 AM
 * @twitter https://twitter.com/patricmutwiri
 *
 */

/**
 * Date: 1/5/23
 * @author Patrick Mutwiri
 * @patric_mutwiri
 */

namespace Patricmutwiri\Pesapal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PesapalController extends Controller
{
    public function payNow(Request $request){
        $results = null;
        $data = [];
        $ref = uniqid()."_".time();
        try {
            $paymentParams = [
                "id" => $ref,
                "currency" => config('pesapal.pesapal-currency','KES'),
                "amount" => $request->get('amount',1),
                "description" => $request->get('description', "Lexserve Payment {$ref}"),
                "callback_url" => config('pesapal.pesapal-callback'),
                "notification_id" => $request->get('ipn_id'),
                "billing_address" => [
                    "email_address" => $request->get('email', 'patwiri@gmail.com'),
                    "phone_number" => null,
                    "country_code" => "",
                    "first_name" => $request->get('first_name', "Patrick"),
                    "middle_name" => "",
                    "last_name" =>  $request->get('last_name', "Mutwiri"),
                    "line_1" => "",
                    "line_2" => "",
                    "city" => "",
                    "state" => "",
                    "postal_code" => null,
                    "zip_code" => null
                ]
            ];
            $pesapal = new Pesapal();
            $results = $pesapal->paymentRequest($paymentParams);
            error_log(__METHOD__." payment response ".$results);
            $data = [
                'order_tracking_id' => $results->order_tracking_id ?? null,
                'merchant_reference' => $results->merchant_reference ?? null,
                'redirect_url' => $results->redirect_url ?? null,
                'error' => $results->error ?? null,
                'status' => $results->status ?? null,
            ];
            return view('pesapal.pay-now', compact('data'));
        } catch (\Exception $e){
            error_log(__METHOD__." error making a payment. Details ".print_r($e, true));
        }
        return $results;
    }

    public function ipn(Request $request){
        error_log(__METHOD__." IPN hit. Details ".print_r($request->all(), true));
        //{"orderNotificationType":"IPNCHANGE","orderTrackingId":"d0fa69d6-f3cd-433b-858e-df86555b86c8","orderMerchantReference":"1515111111","status":200}
        $results = [
            'orderNotificationType' => $request->get('OrderNotificationType'),
            'orderTrackingId' => $request->get('OrderTrackingId'),
            'orderMerchantReference' => $request->get('OrderMerchantReference'),
        ];
        try {
            $results['status'] = 200;
        } catch (\Exception $e){
            error_log(__METHOD__." error processing IPN. Details ".print_r($e, true));
        }
        $results = json_encode($results);
        error_log(__METHOD__." response to give pesapal ".$results);
        return $results;
    }

    public function callback(Request $request){
        error_log(__METHOD__." Callback hit. Details ".print_r($request->all(), true));
        $orderNotificationType = $request->get('OrderNotificationType');
        $orderTrackingId = $request->get('OrderTrackingId');
        $orderMerchantReference = $request->get('OrderMerchantReference');
        try {
            // check trx status
        } catch (\Exception $e){
            error_log(__METHOD__." error processing callback. Details ".print_r($e, true));
        }
        $data = $request->all();
        return view('pesapal.pay-now', compact('data'));
    }
}