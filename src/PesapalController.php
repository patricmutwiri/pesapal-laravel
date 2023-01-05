<?php
/*
 * Copyright (c) 2023.
 * @author Patrick Mutwiri on 1/5/23, 4:12 PM
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

class PesapalController extends Controller
{
    public function ipn(){
        error_log(__METHOD__." IPN hit. Details ".print_r($_REQUEST, true));
        try {
            //
        } catch (\Exception $e){
            error_log(__METHOD__." error processing IPN. Details ".print_r($e, true));
        }
    }

    public function callback(){
        error_log(__METHOD__." Callback hit. Details ".print_r($_REQUEST, true));
        try {
            //
        } catch (\Exception $e){
            error_log(__METHOD__." error processing callback. Details ".print_r($e, true));
        }
    }
}