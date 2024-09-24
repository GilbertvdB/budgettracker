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
        Schema::table('receipts', function (Blueprint $table) {
            $table->after('total', function (Blueprint $table) {
                $table->integer('total_verified')->default(1);
            });
            $table->after('url', function (Blueprint $table) {
                $table->longText('ocr_text')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receipts', function (Blueprint $table) {
            $table->dropColumn('total_verified');
            $table->dropColumn('ocr_text');
        });
    }
};
