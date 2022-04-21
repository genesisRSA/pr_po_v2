<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRfqRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rfq_records', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pr_code');
            $table->string('item_code');
            $table->string('description')->nullable();
            $table->string('quantity');
            $table->string('unit_of_measure');
            $table->string('delivery_date');
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
        Schema::dropIfExists('rfq_records');
    }
}
