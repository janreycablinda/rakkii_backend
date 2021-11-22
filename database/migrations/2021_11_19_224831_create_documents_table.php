<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('vehicle_or_cr')->nullable();
            $table->string('drivers_license')->nullable();
            $table->string('police_report')->nullable();
            $table->string('comprehensive_insurance')->nullable();
            $table->string('pictures')->nullable();
            $table->string('certificate_of_claim')->nullable();
            $table->string('trip_ticket')->nullable();
            $table->string('authorization_government')->nullable();
            $table->string('authorization_individual')->nullable();
            $table->string('request_for_qoutation')->nullable();
            $table->string('mayors_permit')->nullable();
            $table->string('philgeps')->nullable();
            $table->string('omnibus')->nullable();
            $table->string('tax_clearance')->nullable();
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
        Schema::dropIfExists('documents');
    }
}
