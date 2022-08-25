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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedDouble('total');
            $table->foreignId('product_coupoun_id')->nullable()->constrained()->nullOnDelete();
            $table->string('coupoun_type')->nullable();
            $table->unsignedBigInteger('coupoun_value')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('order_token')->unique();
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
};
