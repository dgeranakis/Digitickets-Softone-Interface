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
        Schema::create('enumeration_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enumeration_id');
            $table->text('description')->nullable();
            $table->string('locale')->index();

            $table->unique(['enumeration_id', 'locale']);
            $table->foreign('enumeration_id')->references('id')->on('enumerations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enumeration_translations');
    }
};
