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
        Schema::create('user_product', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected']);

            $table->foreignId('user_id')
                ->constrained()
                ->OnUpdate('cascade')
                ->OnDelete('cascade');

            $table->foreignId('product_id')
                ->constrained()
                ->OnUpdate('cascade')
                ->OnDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_product');
    }
};
