<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('billing_statement_id');
            $table->date('date_paid')->nullable();
            $table->string('receipt_no');
            $table->string('payment_type')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('check_no')->nullable();
            $table->string('account_name')->nullable();
            $table->string('check_date')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('status');
            $table->boolean('is_deleted')->default(0);
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
        Schema::dropIfExists('billing_payments');
    }
}
