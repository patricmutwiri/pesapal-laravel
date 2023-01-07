<?php
/*
 * Copyright (c) 2023.
 * @author Patrick Mutwiri on 1/7/23, 3:18 PM
 * @twitter https://twitter.com/patric_mutwiri
 *
 */

use Illuminate\Support\Facades\Route;
use Patricmutwiri\Pesapal\PesapalController;

Route::get("/pesapal/ipn", [PesapalController::class, 'ipn'])
    ->name("pesapal.ipn");

Route::get("/pesapal/ipn-urls", [PesapalController::class, 'registeredUrls'])
    ->name("pesapal.ipn.urls");

Route::get("/pesapal/callback", [PesapalController::class, 'callback'])
    ->name("pesapal.callback");
