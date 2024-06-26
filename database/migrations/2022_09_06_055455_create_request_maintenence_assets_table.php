<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestMaintenenceAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_maintenence_asset', function (Blueprint $table) {
    
            $table->increments('id');
            $table->string('problem_description');
            $table->integer('proposal_id')->unsigned();
            $table->foreign('proposal_id')->references('id')->on('proposal')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('inventory_item_id')->unsigned();
            $table->foreign('inventory_item_id')->references('id')->on('inventory_item')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('request_maintenence_asset');
    }
}
