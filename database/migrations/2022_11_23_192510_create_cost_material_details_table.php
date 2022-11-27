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
        Schema::create('cost_material_details', function (Blueprint $table) {
            $table->bigIncrements('id');
             // foreign
             $table->foreignId('cost_id')
                ->constrained('cost')
                ->onDelete('cascade');
            $table->string('part_no', 150);
            $table->string('part_name', 150);
            $table->integer('material_rate_id');
            $table->integer('material_currency_value_id');
            $table->double('exchange_rate');
            $table->double('usage_part');
            $table->double('over_head');
            $table->double('total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cost_material_details', function(Blueprint $table) {
            $table->dropForeign('cost_material_details_cost_id_foreign');
        });
        Schema::dropIfExists('cost_material_details');
    }
};
