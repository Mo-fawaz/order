<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('order_type',['Receive','delivery']);
            $table->unsignedBigInteger('location_id')->nullable();
            $table->foreign('location_id')->on('locations')->references('id')->onDelete('cascade');
            $table->dropColumn('total_price');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('order_type');
            $table->dropForeign(['location_id']);
            $table->dropColumn('location_id');
            $table->integer('total_price');
        });
    }
};
