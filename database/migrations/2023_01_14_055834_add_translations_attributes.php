<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('lang')->after('header')->nullable()->default(null);
        });
        Schema::table('sections', function (Blueprint $table) {
            $table->string('lang')->after('content')->nullable()->default(null);
        });
        Schema::table('features', function (Blueprint $table) {
            $table->string('lang')->after('description')->nullable()->default(null);
            $table->foreignId('translate_parent_id')->after('lang')->nullable()->default(null)->references('id')->on('features')->onDelete('cascade');
        });
        Schema::table('menus', function (Blueprint $table) {
            $table->text('translations')->after('status')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('lang');
        });
        Schema::table('sections', function (Blueprint $table) {
            $table->dropColumn('lang');
        });
        Schema::table('features', function (Blueprint $table) {
            $table->dropColumn('lang');
            $table->dropConstrainedForeignId('translate_parent_id');
        });
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('translations');
        });
    }
};
