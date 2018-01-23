<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('amount', 10, 5);
            $table->boolean('authorized');
            $table->string('message');
            $table->string('transaction_number', 20);
            $table->string('type', 10);
            $table->string('status', 10);
            $table->unsignedInteger('wallet_id');
            $table->timestamps();

            $table->foreign('wallet_id')->references('id')->on('wallets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function(Blueprint $table) {
            $table->dropForeign('transactions_wallet_id_foreign');
        });
        Schema::dropIfExists('transactions');
    }
}
