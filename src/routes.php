<?php
/*
 * Copyright (c) 2023.
 * @author Patrick Mutwiri on 1/5/23, 1:03 AM
 * @twitter https://twitter.com/patricmutwiri
 *
 */

use Illuminate\Support\Facades\Route;
use Patricmutwiri\Pesapal\PesapalController;

Route::get("/pesapal/ipn", [PesapalController::class, 'ipn'])
    ->name("pesapal.ipn");

Route::get("/pesapal/callback", [PesapalController::class, 'callback'])
    ->name("pesapal.callback");
