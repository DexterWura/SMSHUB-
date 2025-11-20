<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Definations for `type`
     * 0 - Open
     * 1 - Registered Users Only
     * 2 - Private (Shared by Admin)
     * 3 - Shared Buy (Multiple Users can Access)
     * 4 - Private Buy (Dedicated Access)
     *
     * @return void
     */
    public function up() {
        Schema::create('numbers', function (Blueprint $table) {
            $table->id();
            $table->string('number', 20);
            $table->string('country', 2);
            $table->text('meta')->nullable();
            $table->unsignedTinyInteger('type')->default(0);
            $table->unsignedTinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('numbers');
    }
};
