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
        $validated = $request->validate([
            'amount' => 'required',
            'ipn_id' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'id' => 'required'
        ]);
        error_log(__METHOD__." validated request ".json_encode($validated));
        $results = null;
        $data = [];
        $ref = $request->id;
        try {
            $paymentParams = [
                "id" => $ref,
                "currency" => config('pesapal.pesapal-currency','KES'),
                "amount" => $request->amount,
                "description" => $request->get('description', "LX Payment {$ref}"),
                "callback_url" => config('pesapal.pesapal-callback'),
                "notification_id" => $request->get('ipn_id', 'df280e9e-3d8a-4ec7-8e18-df295e04706f'),
                "billing_address" => [
                    "email_address" => $request->get('email', 'patwiri@gmail.com'),
                    "phone_number" => $request->get('phone', ''),
                    "country_code" => "",
                    "first_name" => $request->get('first_name', 'Patrick'),
                    "middle_name" => "",
                    "last_name" =>  $request->get('last_name', 'Mutwiri'),
                    "line_1" => "",
                    "line_2" => "",
                    "city" => "",
                    "state" => "",
                    "postal_code" => null,
                    "zip_code" => null
                ]
            ];
            $results = \Pesapal::paymentRequest($paymentParams);
            error_log(__METHOD__." payment response ".json_encode($results));
            $data = [
                'order_tracking_id' => $results->order_tracking_id ?? null,
                'merchant_reference' => $results->merchant_reference ?? null,
                'redirect_url' => $results->redirect_url ?? null,
                'error' => $results->error ?? null,
                'status' => $results->status ?? null,
            ];
        } catch (\Exception $e){
            error_log(__METHOD__." error making a payment. Details: ".$e->getMessage());
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
        error_log(__METHOD__." validated request ".json_encode($validated));
        $url = $request->ipn_url ?? '';
        $method = $request->ipn_method ?? 'GET';
        $ipn = [];
        try {
            $ipn = \Pesapal::IPNRegister($url, $method);
            error_log(__METHOD__." IPN Register response ".json_encode($ipn));
        } catch(\Exception $e){
            error_log(__METHOD__." error registering IPN URLs. Details ".$e->getMessage());
        }
        return view('pesapal.ipn-url', compact('ipn'));
    }

    public function registeredUrls() {
        $ipns = [];
        try {
            $ipns = \Pesapal::IPNList();
        } catch (\Exception $e){
            error_log(__METHOD__." error loading registered IPNs. Details ".print_r($e, true));
        }
        return view('pesapal.ipn-urls', compact('ipns'));
    }

    public function ipn(Request $request){
        error_log(__METHOD__." IPN hit. Details ".print_r($request->all(), true));
        //{"orderNotificationType":"IPNCHANGE","orderTrackingId":"d0fa69d6-f3cd-433b-858e-df86555b86c8","orderMerchantReference":"1515111111","status":200}
        $results = [
            'orderNotificationType' => $request->get('OrderNotificationType', ''),
            'OrderTrackingId' => $request->get('OrderTrackingId', ''),
            'orderMerchantReference' => $request->get('OrderMerchantReference', ''),
            'status' => $request->get('status', '200'),
        ];
        try {
            $status = \Pesapal::transactionStatus($results['OrderTrackingId']);
        } catch (\Exception $e){
            $results['status'] = 500;
            error_log(__METHOD__." error processing IPN. Error ".$e->getMessage());
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
        $status = [];
        try {
            $status = \Pesapal::transactionStatus($orderTrackingId);
        } catch (\Exception $e){
            error_log(__METHOD__." error processing callback. Details ".$e->getMessage());
        }
        return view('pesapal.confirmation', compact('data', 'status','orderTrackingId'));
    }
}