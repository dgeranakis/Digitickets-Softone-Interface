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
        Schema::create('domain_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('domain_id');
            $table->text('description')->nullable();
            $table->string('locale')->index();

            $table->unique(['domain_id', 'locale']);
            $table->foreign('domain_id')->references('id')->on('domains')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domain_translations');
    }
};
