<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variants', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('variant_id');
            $table->integer('product_id')->unsigned();
            $table->string('name');
            $table->integer('price_with_vat_for_customer');
            $table->integer('stock_count');

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unique(['variant_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('variants');
    }
}
