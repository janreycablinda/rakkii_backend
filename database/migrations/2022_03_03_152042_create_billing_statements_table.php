<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingStatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_statements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_order_id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('insurance_id')->nullable();
            $table->integer('billing_statement_no');
            $table->string('tin')->nullable();
            $table->string('address')->nullable();
            $table->datetime('date');
            $table->string('plate_no')->nullable();
            $table->string('ref_claim_no')->nullable();
            $table->string('buss_style')->nullable();
            $table->string('payment_for')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('billing_statements');
    }
}
