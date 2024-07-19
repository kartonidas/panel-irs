<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrencyRatesHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency_rates_history', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('symbol', 5)->nullable();
            $table->date('effective_date');
            $table->decimal('rate', 8, 2)->nullable();
            
            $table->index(['symbol', 'effective_date'], 'symbol_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currency_rates_history');
    }
}
