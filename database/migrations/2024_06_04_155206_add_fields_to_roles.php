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
        Schema::table('roles', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_user')->after('guard_name')->nullable();
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
        Schema::table('roles', function (Blueprint $table) {
            $table->dropForeign(['created_by_user']);
            $table->dropForeign(['updated_by_user']);
            $table->dropColumn(['created_by_user', 'updated_by_user']);
        });
    }
};
