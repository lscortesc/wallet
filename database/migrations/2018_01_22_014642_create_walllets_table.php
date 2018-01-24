<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWallletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('balance', 20, 2);
            $table->unsignedInteger('customer_id');
            $table->string('currency_id', 3);
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('CASCADE');
            $table->foreign('currency_id')->references('id')->on('currencies');
        });

        DB::update("alter table wallets auto_increment = 100000");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->dropForeign('wallets_customer_id_foreign');
            $table->dropForeign('wallets_currency_id_foreign');
        });
        Schema::dropIfExists('wallets');
    }
}
