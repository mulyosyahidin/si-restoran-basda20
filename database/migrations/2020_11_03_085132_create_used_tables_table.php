<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsedTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('used_tables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('table_id')->nullable();

            $table->index('order_id');
            $table->index('table_id');

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('CASCADE')->onUpdate('NO ACTION');
            $table->foreign('table_id')->references('id')->on('tables')->onDelete('CASCADE')->onUpdate('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('used_tables');
    }
}
