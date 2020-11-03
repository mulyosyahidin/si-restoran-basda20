<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('waiter_id')->nullable();
            $table->string('order_number', 16);
            $table->string('customer_name');
            $table->tinyInteger('status')->default(1)->comment('1 = dalam proses (oleh dapur), 2 = aktif (dimeja), 3 = selesai (sudah dibayar), 4 = batal');
            $table->tinyInteger('type')->default(1)->comment('1 = dine in, 2 = take away');
            $table->unsignedBigInteger('table_id')->nullable();
            $table->integer('total_item');
            $table->decimal('total_price');
            $table->timestamps();

            $table->index('waiter_id');
            $table->index('table_id');

            $table->foreign('waiter_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('NO ACTION');
            $table->foreign('table_id')->references('id')->on('tables')->onDelete('SET NULL')->onUpdate('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
