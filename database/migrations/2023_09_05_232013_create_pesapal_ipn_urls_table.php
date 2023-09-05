<?php
/*
 * Copyright (c) 2023.
 * @author Patrick Mutwiri on 9/5/23, 11:21 PM
 * @twitter https://twitter.com/patric_mutwiri
 *
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePesapalIpnUrlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pesapal_ipn_urls', function (Blueprint $table) {
            $table->id();
            $table->string('ipn_id')->nullable();
            $table->string('url')->nullable();
            $table->string('http_method')->nullable();
            $table->string('created_date')->nullable();
            $table->string('status')->nullable();
            $table->string('error')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pesapal_ipn_urls');
    }
}