<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrderListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('pr_id');
            $table->unsignedBigInteger('user_id');
            $table->string('pr_no');
            $table->string('so_no');
            $table->string('department');
            $table->string('item_category');
            $table->string('status');
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
        Schema::dropIfExists('purchase_order_lists');
    }
}
