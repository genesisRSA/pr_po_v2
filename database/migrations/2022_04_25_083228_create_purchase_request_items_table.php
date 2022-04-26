<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseRequestItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_request_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('purchase_request_list_id');
            $table->string('part_name');
            $table->string('material');
            $table->string('dimension');
            $table->string('quantity');
            $table->string('remarks');
            $table->string('supplier_one');
            $table->string('supplier_two');
            $table->string('supplier_three');
            $table->string('target_cost');
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
        Schema::dropIfExists('purchase_request_items');
    }
}
