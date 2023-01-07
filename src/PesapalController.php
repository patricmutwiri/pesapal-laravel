<?php
/*
 * Copyright (c) 2023.
 * @author Patrick Mutwiri on 1/7/23, 4:28 PM
 * @twitter https://twitter.com/patric_mutwiri
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
            $results = \Pesapal::paymentRequest($paymentParams);
            error_log(__METHOD__." payment response ".$results);
            $data = [
                'order_tracking_id' => $results->order_tracking_id ?? null,
                'merchant_reference' => $results->merchant_reference ?? null,
                'redirect_url' => $results->redirect_url ?? null,
                'error' => $results->error ?? null,
                'status' => $results->status ?? null,
            ];
        } catch (\Exception $e){
            error_log(__METHOD__." error making a payment. Details ".print_r($e, true));
        }
        return view('pesapal.pay-now', compact('data'));
    }

    public function viewRegisterUrl(){
        return view('pesapal.register-url');
    }

    public function registerUrl(Request $request){
        $validated = $request->validate([
            'ipn_url' => 'required',
            'ipn_method' => 'required',
        ]);
        dd($validated);
        $url = $request->ipn_url ?? '';
        $method = $request->ipn_method ?? 'GET';
        try {
            $ipn_reg = \Pesapal::IPNRegister();
            error_log(__METHOD__." IPN Register response ".json_encode($ipn_reg));
        } catch(\Exception $e){
            error_log(__METHOD__." error registering IPN URLs. Details ".$e->getMessage());
        }
        return $this->registeredUrls();
    }

    public function registeredUrls() {
        $ipns = [];
        $ipn_reg = [];
        try {
            $ipns = \Pesapal::IPNList();
            if (empty($ipns)) {
                error_log(__METHOD__." register configured IPN URL. ".config('pesapal.pesapal-ipn'));
                $ipn_reg = \Pesapal::IPNRegister();
            }
        } catch (\Exception $e){
            error_log(__METHOD__." error loading registered IPNs. Details ".print_r($e, true));
        }
        return view('pesapal.ipn-urls', compact('ipns', 'ipn_reg'));
    }

    public function ipn(Request $request){
        error_log(__METHOD__." IPN hit. Details ".print_r($request->all(), true));
        //{"orderNotificationType":"IPNCHANGE","orderTrackingId":"d0fa69d6-f3cd-433b-858e-df86555b86c8","orderMerchantReference":"1515111111","status":200}
        $results = [
            'orderNotificationType' => $request->get('OrderNotificationType'),
            'orderTrackingId' => $request->get('OrderTrackingId'),
            'orderMerchantReference' => $request->get('OrderMerchantReference'),
        ];
        $status = null;
        try {
            $results['status'] = 200;
            // Successful IPN, get TRX status
            if ($request->get('OrderNotificationType') == "IPNCHANGE") {
                $status = \Pesapal::transactionStatus($request->get('OrderTrackingId'));
            }
            error_log(__METHOD__." transaction status response ".$status);
        } catch (\Exception $e){
            $results['status'] = 500;
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
        $data = $request->all();
        $status = null;
        try {
            // if callback url, get TRX status and go to confirmation page
            if ($orderNotificationType == "CALLBACKURL") {
                $status = \Pesapal::transactionStatus($orderTrackingId);
            }
            error_log(__METHOD__." trx status {$status}");
        } catch (\Exception $e){
            error_log(__METHOD__." error processing callback. Details ".print_r($e, true));
        }
        return view('pesapal.confirmation', compact('data', 'status'));
    }
}