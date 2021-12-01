<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobOrderScopeSubServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_order_scope_sub_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_order_scope_services_id');
            $table->foreign('job_order_scope_services_id')->references('id')->on('job_order_scope_services')->cascadeOnDelete();
            $table->unsignedBigInteger('sub_services_id');
            $table->integer('labor_fee')->default(0);
            $table->integer('parts_fee')->default(0);
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
        Schema::dropIfExists('job_order_scope_sub_services');
    }
}
