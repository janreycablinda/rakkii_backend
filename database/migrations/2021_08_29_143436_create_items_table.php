<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('item');
            $table->string('description')->nullable();
            $table->string('brand');
            $table->string('unit');
            $table->integer('price');
            $table->integer('unit_cost');
            $table->integer('qty');
            $table->UnsignedBigInteger('group_id');
            $table->UnsignedBigInteger('user_id');
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
        Schema::dropIfExists('items');
    }
}
