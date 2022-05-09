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
            $table->string('order_id')->nullable();
            $table->integer('number_of_order_items');
            $table->double('total_without_tax', 10, 2);
            $table->double('tax', 10, 2);
            $table->double('service_charge', 10, 2)->nullable();
            $table->double('total_with_tax', 10, 2);
            $table->double('total_amount_paid', 10, 2);
            $table->double('change', 10, 2);
            $table->string('payment_method');
            $table->integer('status');
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
        Schema::dropIfExists('orders');
    }
}
