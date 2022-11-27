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
        Schema::create('cost_purchase_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            // foreign
            $table->foreignId('cost_id')
                ->constrained('cost')
                ->onDelete('cascade');
            $table->integer('currency');
            $table->integer('currency_type');
            $table->integer('currency_value_id');
            $table->string('part_name', 150);
            $table->string('part_no', 150);
            $table->double('over_head')->default(0);
            $table->double('quantity')->default(0);
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
        Schema::table('cost_purchase_details', function(Blueprint $table) {
            $table->dropForeign('cost_purchase_details_cost_id_foreign');
        });

        Schema::dropIfExists('cost_purchase_details');
    }
};
