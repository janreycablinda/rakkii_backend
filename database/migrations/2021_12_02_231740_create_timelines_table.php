<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimelinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timelines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_order_id');
            $table->unsignedBigInteger('services_type_id');
            $table->date('date_start')->nullable();
            $table->date('date_done')->nullable();
            $table->date('commitment_date')->nullable();
            $table->integer('panels')->nullable();
            $table->string('remarks')->nullable();
            $table->unsignedBigInteger('personnel_id')->nullable();
            $table->string('status');
            $table->unsignedBigInteger('user_id');
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
        Schema::dropIfExists('timelines');
    }
}
