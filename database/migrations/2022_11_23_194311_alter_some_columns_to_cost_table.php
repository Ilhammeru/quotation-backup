<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cost', function (Blueprint $table) {
            $table->double('material_cost')->default(0);
            $table->double('process_cost')->default(0);
            $table->double('purchase_cost')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cost', function (Blueprint $table) {
            $table->dropColumn('material_cost');
            $table->dropColumn('process_cost');
            $table->dropColumn('purchase_cost');
        });
    }
};
