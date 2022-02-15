<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuoteRowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote_rows', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('quote_id')->unsigned();
            $table->foreign('quote_id')->references('id')->on('quotes')->onDelete('cascade');;
            $table->string('article_type');
            $table->integer('article_id')->unsigned();
            $table->decimal('quantity');
            $table->string('unity')->default('day(s)');
            $table->decimal('vat_rate');
            $table->decimal('unit_price');
            $table->decimal('discount_euro')->nullable();
            $table->string('discount_unit')->nullable();
            $table->string('description',1024)->nullable();
            $table->decimal('sell_total');
            $table->integer('order');
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
        Schema::dropIfExists('quote_rows');
    }
}
