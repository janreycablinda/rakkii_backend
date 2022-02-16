<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->integer('job_order_no');
            $table->string('date');
            $table->unsignedBigInteger('insurance_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->string('status');
            $table->date('car_in')->nullable();
            $table->date('car_out')->nullable();
            $table->string('job_order_no_str');
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
        Schema::dropIfExists('job_orders');
    }
}
