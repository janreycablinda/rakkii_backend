<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScopeOfWorkSubServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scope_of_work_sub_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scope_of_work_services_id');
            $table->foreign('scope_of_work_services_id')->references('id')->on('scope_of_work_services')->cascadeOnDelete();
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
        Schema::dropIfExists('scope_of_work_sub_services');
    }
}
