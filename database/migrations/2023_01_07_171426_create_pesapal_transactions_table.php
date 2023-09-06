<?php
/*
 * Copyright (c) 2023.
 * @author Patrick Mutwiri on 1/7/23, 5:37 PM
 * @twitter https://twitter.com/patric_mutwiri
 *
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePesapalTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pesapal_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('our_ref')->unique();
            $table->string('payment_method')->nullable();
            $table->string('order_tracking_id')->nullable();
            $table->string('merchant_reference')->nullable();
            $table->string('payment_status_code')->nullable();
            $table->string('redirect_url')->nullable();
            $table->string('notes')->nullable();
            $table->string('confirmation_code')->nullable();
            $table->string('payment_status_description')->nullable();
            $table->string('description')->nullable();
            $table->string('message')->nullable();
            $table->string('payment_account')->nullable();
            $table->string('reference')->default('0');
            $table->string('amount')->default('0');
            $table->string('currency')->default('KES');
            $table->string('status')->default('NEW');
            $table->string('trx_status_code')->nullable();
            $table->string('status_code')->nullable();
            $table->string('errors')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('narration')->default('Pesapal TRX');
            $table->string('ipn_id')->nullable();
            $table->string('added_by')->default('0');
            $table->string('created_date')->nullable();
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
        Schema::dropIfExists('pesapal_transactions');
    }
}