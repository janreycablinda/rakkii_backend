<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('billing_statement_id');
            $table->unsignedBigInteger('services_id')->nullable();
            $table->unsignedBigInteger('sub_services_id')->nullable();
            $table->string('description')->nullable();
            $table->integer('unit_cost');
            $table->integer('qty');
            $table->integer('labor');
            $table->integer('materials');
            $table->string('type')->nullable();
            $table->boolean('custom')->default(0);
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
        Schema::dropIfExists('billing_details');
    }
}
