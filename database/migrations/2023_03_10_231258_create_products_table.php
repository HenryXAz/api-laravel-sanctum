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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('detail');
            $table->float('price');
            $table->boolean('itsOnSale')->nullable()->default(false);
            $table->timestamps();
            $table->foreignId('category_id')
              ->nullable();
              // ->constrained('categories')
              // ->cascadeOnUpdate()
              // ->nullOnDelete();
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
};