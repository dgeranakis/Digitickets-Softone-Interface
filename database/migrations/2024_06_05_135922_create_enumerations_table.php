<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('enumerations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('domain_id');
            $table->string('code', 50);
            $table->boolean('active')->default(1);
            $table->timestamps();

            $table->unique(['domain_id', 'code']);
            $table->foreign('domain_id')->references('id')->on('domains')->onDelete('cascade');
        });

        Schema::table('enumerations', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_user')->after('active')->nullable();
            $table->unsignedBigInteger('updated_by_user')->after('created_at')->nullable();

            $table->foreign('created_by_user')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by_user')->references('id')->on('users')->onDelete('set null');
        });

        DB::table('permissions')->insert(array(
            array('id' => 1, 'name' => 'view selection lists', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")),
            array('id' => 2, 'name' => 'create selection lists', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")),
            array('id' => 3, 'name' => 'edit selection lists', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")),
            array('id' => 4, 'name' => 'filter selection lists', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")),
            array('id' => 5, 'name' => 'export selection lists', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")),
            array('id' => 6, 'name' => 'delete selection lists', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"))
        ));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enumerations');
        DB::table('permissions')->whereIn('id', [1, 2, 3, 4, 5, 6])->delete();
    }
};
