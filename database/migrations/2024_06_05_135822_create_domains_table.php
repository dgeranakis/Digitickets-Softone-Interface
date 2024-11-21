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
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->timestamps();
        });

        Schema::table('domains', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_user')->after('code')->nullable();
            $table->unsignedBigInteger('updated_by_user')->after('created_at')->nullable();

            $table->foreign('created_by_user')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by_user')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};
