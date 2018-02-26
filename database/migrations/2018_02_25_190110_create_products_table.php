<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_id');
            $table->string('variant_id');
            $table->string('complete_name');
            $table->string('code');
            $table->string('image');
            $table->string('producer_name');
            $table->string('producer_home_page_url');
            $table->text('description_summary');
            $table->integer('amount_step');
            $table->string('amount_unit');
            $table->integer('price_with_vat_for_customer');
            $table->boolean('sale_sticker');
            $table->boolean('new_sticker');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}

