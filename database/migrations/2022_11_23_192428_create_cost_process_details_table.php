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
        Schema::create('cost_process_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            // foreign
            $table->foreignId('cost_id')
                ->constrained('cost')
                ->onDelete('cascade');
            $table->string('part_no', 150);
            $table->string('part_name', 150);
            $table->integer('process_rate_id');
            $table->double('cycle_time')->default(0);
            $table->double('over_head')->default(0);
            $table->double('total')->default(0);
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
        // drop foreign
        Schema::table('cost_process_details', function(Blueprint $table) {
            $table->dropForeign('cost_process_details_cost_id_foreign');
        });

        Schema::dropIfExists('cost_process_details');
    }
};
