<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('image');
            //Unit price
            $table->boolean('unit_availability');
            $table->decimal('unit_price', 6, 2)->unsigned()->nullable();
            $table->integer('unit_discount_quantity')->nullable();
            $table->decimal('unit_discount_price', 6, 2)->unsigned()->nullable();
            //Weight price
            $table->boolean('weight_availability')->comment('Based on kilograms|Basado en kilogramos');
            $table->decimal('weight_price', 6, 2)->unsigned()->nullable();
            $table->integer('weight_discount_quantity')->nullable();
            $table->decimal('weight_discount_price', 6, 2)->unsigned()->nullable();

            $table->string('unit_name')->nullable();
            $table->boolean('is_available')->default(true);
            $table->foreignId('category_id')->constrained();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};      
