<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('food_id')->nullable();
            $table->tinyInteger('item_qty');
            $table->decimal('item_price');

            $table->index('order_id');
            $table->index('food_id');

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('CASCADE')->onUpdate('NO ACTION');
            $table->foreign('food_id')->references('id')->on('foods')->onDelete('SET NULL')->onUpdate('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
