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
        DB::table('roles')->insert(array(
            array('id' => 1, 'name' => 'Super Admin', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")),
            array('id' => 2, 'name' => 'Customer', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"))
        ));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('roles')->delete();
    }
};
