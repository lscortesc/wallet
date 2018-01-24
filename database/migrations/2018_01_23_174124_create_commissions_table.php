<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commissions', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('amount', 20, 2);
            $table->decimal('percentage', 5, 2);
            $table->decimal('fixed_rate', 10, 2);
            $table->unsignedInteger('transaction_id');
            $table->timestamps();

            $table->foreign('transaction_id')->references('id')->on('transactions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commissions', function (Blueprint $table) {
            $table->dropForeign('commissions_transaction_id_foreign');
        });
        Schema::dropIfExists('commissions');
    }
}
