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
            $table->text('redirect_url')->nullable();
            $table->text('notes')->default('Request Received at '.date('Y-m-d H:i:s'));
            $table->text('confirmation_code')->nullable();
            $table->text('payment_status_description')->nullable();
            $table->text('description')->nullable();
            $table->string('reference')->default('0');
            $table->string('amount')->default('0');
            $table->string('currency')->default('KES');
            $table->string('status')->default('NEW');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('narration')->default('Pesapal TRX');
            $table->string('ipn_id')->nullable();
            $table->string('ipn_id')->nullable();
            $table->string('added_by')->default('0');
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
