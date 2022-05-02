<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('purchase_request_item_id');
            $table->string('supplier_no');
            $table->string('is_preferred');
            $table->string('supplier_cost');
            $table->string('payment_method');
            $table->string('eta');
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
        Schema::dropIfExists('supplier_details');
    }
}
