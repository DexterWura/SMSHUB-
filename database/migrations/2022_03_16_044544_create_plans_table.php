<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * `validity` is number of days the plan is valid after purchased
     *
     * @return void
     */
    public function up() {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedFloat('price', 8, 2);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('validity');
            $table->string('validity_type')->default('day');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('plans');
    }
};
